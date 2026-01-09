<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBeneficiaryDonationRequest;
use App\Models\Beneficiary;
use App\Models\BeneficiaryCategory;
use App\Models\Entry;
use App\Models\Payment;
use App\Models\RaffleNumber;
use App\Models\RaffleRule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.home');
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

        $rules = RaffleRule::query()
            ->where('active', true)
            ->orderBy('amount')
            ->get();

        $entryQuery = Entry::query()->where('beneficiary_id', $beneficiary->id);
        $stats = [
            'donations' => (clone $entryQuery)->count(),
            'total_amount' => (clone $entryQuery)->sum('amount'),
            'numbers' => RaffleNumber::query()->whereIn('entry_id', $entryQuery->pluck('id'))->count(),
        ];

        return view('website.beneficiary-show', [
            'beneficiary' => $beneficiary,
            'shareUrl' => route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => $expectedSlug]),
            'rules' => $rules,
            'stats' => $stats,
        ]);
    }

    public function beneficiaryDonate(StoreBeneficiaryDonationRequest $request, Beneficiary $beneficiary, $slug = null)
    {
        abort_unless($beneficiary->active, 404);

        $amount = (float) $request->input('amount');
        $numbersCount = $this->resolveRaffleNumbersCount($amount);

        $entry = Entry::create([
            'beneficiary_id' => $beneficiary->id,
            'raffle_code' => 'pending',
            'email' => $request->input('email'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'amount' => $amount,
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

        $numbers = $this->generateRaffleNumbers($numbersCount);
        $entry->raffle_code = (string) $numbers[0];
        $entry->save();

        foreach ($numbers as $number) {
            RaffleNumber::create([
                'entry_id' => $entry->id,
                'number' => $number,
            ]);
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
            ], JSON_UNESCAPED_SLASHES),
        ]);

        return redirect()
            ->route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => Str::slug($beneficiary->name)])
            ->with('donation', [
                'entry_id' => $entry->id,
                'amount' => $amount,
                'numbers' => $numbers,
                'transaction' => $payment->transaction,
            ]);
    }

    private function resolveRaffleNumbersCount(float $amount): int
    {
        $rule = RaffleRule::query()
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

    private function generateRaffleNumbers(int $count): array
    {
        $numbers = [];

        while (count($numbers) < $count) {
            $candidate = random_int(10000, 999999);
            if (in_array($candidate, $numbers, true)) {
                continue;
            }
            if (RaffleNumber::where('number', $candidate)->exists()) {
                continue;
            }
            $numbers[] = $candidate;
        }

        return $numbers;
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
