<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBeneficiaryDonationRequest;
use App\Models\Beneficiary;
use App\Models\BeneficiaryCategory;
use App\Models\Advertisement;
use App\Models\Entry;
use App\Models\Payment;
use App\Models\RaffleGame;
use App\Models\RaffleNumber;
use App\Models\RaffleRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebsiteController extends Controller
{
    public function index()
    {
        $gameAdvertisements = Advertisement::query()
            ->active()
            ->where('type', Advertisement::TYPE_GAME)
            ->orderBy('sort_order')
            ->orderByDesc('draw_date')
            ->get();

        $sponsorAdvertisements = Advertisement::query()
            ->active()
            ->where('type', Advertisement::TYPE_SPONSOR)
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('website.home', compact('gameAdvertisements', 'sponsorAdvertisements'));
    }

    public function donativo()
    {
        $categories = BeneficiaryCategory::with(['media', 'beneficiaries' => function ($query) {
            $query->where('active', true)->with('media');
        }])->get();

        $beneficiaries = Beneficiary::with(['category', 'media'])
            ->where('active', true)
            ->get();

        return view('website.donativo-list', compact('categories', 'beneficiaries'));
    }

    public function beneficiaryDonation(Beneficiary $beneficiary, $slug = null)
    {
        abort_unless($beneficiary->active, 404);

        $expectedSlug = Str::slug($beneficiary->name);
        if ($slug !== $expectedSlug) {
            return redirect()->route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => $expectedSlug]);
        }

        $beneficiary->load(['category', 'media']);

        $activeGame = $this->resolveActiveGame();
        $rules = $activeGame ? $activeGame->rules : collect();

        $entryQuery = Entry::query()->where('beneficiary_id', $beneficiary->id);
        $stats = [
            'donations' => (clone $entryQuery)->count(),
            'total_amount' => (clone $entryQuery)->sum('amount'),
            'numbers' => RaffleNumber::query()->whereIn('entry_id', $entryQuery->pluck('id'))->count(),
        ];

        return view('website.beneficiary-show', [
            'beneficiary' => $beneficiary,
            'shareUrl' => route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => $expectedSlug]),
            'activeGame' => $activeGame,
            'rules' => $rules,
            'commissionPercent' => $this->resolveCommissionPercent($beneficiary, $activeGame),
            'stats' => $stats,
        ]);
    }

    public function beneficiaryDonate(StoreBeneficiaryDonationRequest $request, Beneficiary $beneficiary, $slug = null)
    {
        abort_unless($beneficiary->active, 404);

        $amount = (float) $request->input('amount');
        $activeGame = $this->resolveActiveGame();

        return DB::transaction(function () use ($request, $beneficiary, $amount, $activeGame) {
            $numbers = [];
            $numbersCount = 0;
            $commissionPercent = $this->resolveCommissionPercent($beneficiary, $activeGame);
            $commissionAmount = round($amount * ($commissionPercent / 100), 2);
            $beneficiaryAmount = round($amount - $commissionAmount, 2);

            if ($activeGame) {
                RaffleGame::query()->whereKey($activeGame->id)->lockForUpdate()->first();

                $numbersCount = $this->resolveRaffleNumbersCount($amount, $activeGame);
                $numbers = $this->generateRaffleNumbers($numbersCount, $activeGame);
            }

            $entry = Entry::create([
                'beneficiary_id' => $beneficiary->id,
                'raffle_game_id' => $activeGame?->id,
                'raffle_code' => $numbers ? (string) $numbers[0] : 'no-game',
                'has_raffle_numbers' => !empty($numbers),
                'email' => $request->input('email'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone' => $request->input('phone'),
                'amount' => $amount,
                'commission_percent' => $commissionPercent,
                'commission_amount' => $commissionAmount,
                'beneficiary_amount' => $beneficiaryAmount,
                'is_company' => $request->boolean('is_company'),
                'nif' => $request->input('nif'),
                'nipc' => $request->input('nipc'),
                'address' => $request->input('address'),
                'postal_code' => $request->input('postal_code'),
                'city' => $request->input('city'),
                'country_id' => $request->input('country_id'),
                'consent_privacy' => $request->boolean('consent_privacy'),
                'contact_via' => null,
                'source_page' => $request->fullUrl(),
                'client_ip' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
            ]);

            if ($numbers) {
                foreach ($numbers as $number) {
                    RaffleNumber::create([
                        'entry_id' => $entry->id,
                        'raffle_game_id' => $activeGame->id,
                        'number' => $number,
                    ]);
                }
            }

            $payment = Payment::create([
                'entry_id' => $entry->id,
                'provider' => 'sandbox',
                'transaction' => 'SBX-' . Str::upper(Str::random(10)),
                'amount' => $amount,
                'currency' => 'EUR',
                'status' => 'confirmed',
                'method' => 'cartao',
                'paid_at' => now(),
                'raw_response' => json_encode([
                    'sandbox' => true,
                    'note' => 'Pagamento simulado',
                    'numbers' => $numbers,
                    'raffle_game_id' => $activeGame?->id,
                    'mode' => $activeGame ? 'raffle' : 'regular',
                    'split' => [
                        'commission_percent' => $commissionPercent,
                        'commission_amount' => $commissionAmount,
                        'beneficiary_amount' => $beneficiaryAmount,
                    ],
                ], JSON_UNESCAPED_SLASHES),
            ]);

            return redirect()
                ->route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => Str::slug($beneficiary->name)])
                ->with('donation', [
                    'entry_id' => $entry->id,
                    'amount' => $amount,
                    'numbers' => $numbers,
                    'transaction' => $payment->transaction,
                    'game_active' => (bool) $activeGame,
                    'commission_percent' => $commissionPercent,
                    'commission_amount' => $commissionAmount,
                    'beneficiary_amount' => $beneficiaryAmount,
                ]);
        });
    }

    private function resolveCommissionPercent(Beneficiary $beneficiary, ?RaffleGame $activeGame): float
    {
        $percent = $activeGame
            ? (float) $activeGame->commission_percent
            : (float) $beneficiary->default_commission_percent;

        if ($percent < 0) {
            return 0.0;
        }

        if ($percent > 100) {
            return 100.0;
        }

        return round($percent, 2);
    }

    private function resolveRaffleNumbersCount(float $amount, RaffleGame $game): int
    {
        $rule = RaffleRule::query()
            ->where('raffle_game_id', $game->id)
            ->where('active', true)
            ->where('amount', '<=', $amount)
            ->orderByDesc('amount')
            ->first();

        if (!$rule) {
            return 1;
        }

        $units = (int) floor($amount / (float) $rule->amount);
        $count = $units * (int) $rule->numbers;

        return max(1, $count);
    }

    private function resolveActiveGame(): ?RaffleGame
    {
        $now = now();

        return RaffleGame::query()
            ->where('active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->with(['prize', 'rules' => function ($query) {
                $query->where('active', true)->orderBy('amount');
            }])
            ->orderByDesc('starts_at')
            ->first();
    }

    private function generateRaffleNumbers(int $count, RaffleGame $game): array
    {
        $max = RaffleNumber::query()
            ->where('raffle_game_id', $game->id)
            ->max('number');

        $start = $max === null ? 0 : ((int) $max + 1);
        $end = $start + max(0, $count - 1);

        return $count > 0 ? range($start, $end) : [];
    }

    public function quemSomos()
    {
        return view('website.quem-somos');
    }

    public function contactos()
    {
        return view('website.contactos');
    }
}
