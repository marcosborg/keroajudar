<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdOrderRequest;
use App\Models\AdOrder;
use App\Models\AdPosition;
use App\Models\Beneficiary;
use App\Services\EuPagoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AdvertisingOrderController extends Controller
{
    public function index()
    {
        abort_unless(config('advertising.booking_enabled'), 404);

        $positions = AdPosition::query()->where('active', true)->orderBy('sort_order')->get();

        return view('website.advertising-orders.index', compact('positions'));
    }

    public function create(AdPosition $position)
    {
        abort_unless(config('advertising.booking_enabled') && $position->active, 404);

        return view('website.advertising-orders.create', [
            'position' => $position,
            'categories' => config('advertising.categories'),
            'regions' => config('advertising.regions'),
            'beneficiaries' => Beneficiary::query()
                ->where('active', true)
                ->with('category')
                ->orderBy('name')
                ->get(),
            'paymentReady' => $this->paymentReady($position),
        ]);
    }

    public function store(StoreAdOrderRequest $request, AdPosition $position, EuPagoService $euPago)
    {
        abort_unless(config('advertising.booking_enabled') && $position->active, 404);
        abort_unless($this->paymentReady($position), 503, 'O pagamento desta posição ainda não está configurado.');

        $validated = $request->validated();
        $startsAt = Carbon::parse($validated['starts_at'])->startOfDay();
        $endsAt = Carbon::parse($validated['ends_at'])->startOfDay();
        $days = $startsAt->diffInDays($endsAt) + 1;

        if ($days > 365) {
            return back()->withInput()->withErrors(['ends_at' => 'O pedido não pode exceder 365 dias.']);
        }

        $dailyPrice = round((float) $position->daily_price, 2);
        $amount = round($dailyPrice * $days, 2);
        $publicId = (string) Str::uuid();
        $paymentIdentifier = 'AD'.Str::upper(str_replace('-', '', $publicId));

        $order = AdOrder::create([
            'public_id' => $publicId,
            'ad_position_id' => $position->id,
            'beneficiary_id' => $validated['beneficiary_id'],
            'payment_identifier' => $paymentIdentifier,
            'status' => AdOrder::STATUS_PAYMENT_PENDING,
            'payment_method' => $validated['payment_method'],
            'customer_type' => $validated['customer_type'],
            'contact_name' => $validated['contact_name'],
            'company_name' => $validated['company_name'] ?? null,
            'tax_number' => $validated['tax_number'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'campaign_title' => $validated['campaign_title'],
            'destination_url' => $validated['destination_url'],
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'targets_all_categories' => $validated['targets_all_categories'],
            'target_categories' => $validated['targets_all_categories'] ? null : $validated['target_categories'],
            'targets_all_regions' => $validated['targets_all_regions'],
            'target_regions' => $validated['targets_all_regions'] ? null : $validated['target_regions'],
            'billable_days' => $days,
            'daily_price' => $dailyPrice,
            'amount' => $amount,
            'consent_terms' => true,
        ]);

        $order->addMediaFromRequest('desktop_creative')->toMediaCollection('desktop_creative');
        $order->addMediaFromRequest('mobile_creative')->toMediaCollection('mobile_creative');

        $paymentData = [
            'valor' => $amount,
            'identificador' => $paymentIdentifier,
            'beneficiarios' => [[
                'externKey' => config('eupago.advertising_extern_key'),
                'valor_part' => $amount,
            ]],
        ];

        try {
            if ($validated['payment_method'] === 'mbway') {
                $euPago->createMbwaySplitPayment($paymentData + [
                    'alias' => $validated['mbway_phone'],
                    'descricao' => 'Publicidade '.$position->code,
                ]);
            } else {
                $euPago->createMultibancoSplitPayment($paymentData + [
                    'data_limite' => now()->addDays(3)->toDateString(),
                    'permite_multiplos' => false,
                ]);
            }
        } catch (Throwable $exception) {
            $order->update([
                'status' => AdOrder::STATUS_PAYMENT_FAILED,
                'failure_message' => 'Não foi possível iniciar o pagamento. Tente novamente ou contacte-nos.',
            ]);
            Log::channel('eupago')->error('Falha no pedido publicitário', [
                'ad_order_id' => $order->id,
                'exception' => $exception->getMessage(),
            ]);
        }

        return redirect()->route('advertising.orders.show', $order);
    }

    public function show(Request $request, AdOrder $order)
    {
        $order->load(['position', 'payment']);

        return view('website.advertising-orders.show', compact('order'));
    }

    private function paymentReady(AdPosition $position): bool
    {
        return (float) $position->daily_price > 0
            && filled(config('eupago.api_key'))
            && filled(config('eupago.advertising_extern_key'));
    }
}
