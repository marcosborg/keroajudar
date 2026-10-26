@extends('layouts.website')

@section('content')
<main class="py-5 advertising-order-page">
    <div class="container">
        <a href="{{ route('advertising.orders.index') }}" class="text-success text-decoration-none">&larr; Escolher outra posição</a>
        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm"><div class="card-body p-4 p-lg-5">
                    <span class="advertising-kicker">Pedido publicitário</span>
                    <h1 class="h2">{{ $position->name }}</h1>
                    <p class="text-muted">{{ $position->desktopDimensions() }} no desktop e {{ $position->mobileDimensions() }} no telemóvel.</p>

                    @if(!$paymentReady)
                        <div class="alert alert-warning">Esta posição ainda não tem o pagamento configurado. Pode preencher os dados depois de o preço e a conta EuPago serem ativados.</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger"><strong>Reveja os dados indicados.</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif

                    <form method="POST" action="{{ route('advertising.orders.store', $position) }}" id="ad-order-form">
                        @csrf
                        <h2 class="h5 mt-4">Dados de faturação e contacto</h2>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label" for="customer_type">Tipo de cliente</label><select class="form-select" name="customer_type" id="customer_type" required><option value="individual" @selected(old('customer_type') === 'individual')>Particular</option><option value="company" @selected(old('customer_type', 'company') === 'company')>Empresa/organização</option></select></div>
                            <div class="col-md-6"><label class="form-label" for="contact_name">Nome do responsável</label><input class="form-control" id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required></div>
                            <div class="col-12" id="company-field"><label class="form-label" for="company_name">Nome da empresa/organização</label><input class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}"></div>
                            <div class="col-md-6"><label class="form-label" for="tax_number">NIF/NIPC</label><input class="form-control" id="tax_number" name="tax_number" inputmode="numeric" maxlength="9" value="{{ old('tax_number') }}" required></div>
                            <div class="col-md-6"><label class="form-label" for="email">Email</label><input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required></div>
                            <div class="col-md-6"><label class="form-label" for="phone">Telefone</label><input class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required></div>
                            <div class="col-md-6"><label class="form-label" for="country">País</label><input class="form-control" id="country" name="country" value="{{ old('country', 'Portugal') }}" required></div>
                            <div class="col-12"><label class="form-label" for="address">Morada</label><input class="form-control" id="address" name="address" value="{{ old('address') }}" required></div>
                            <div class="col-md-4"><label class="form-label" for="postal_code">Código postal</label><input class="form-control" id="postal_code" name="postal_code" placeholder="0000-000" value="{{ old('postal_code') }}" required></div>
                            <div class="col-md-8"><label class="form-label" for="city">Localidade</label><input class="form-control" id="city" name="city" value="{{ old('city') }}" required></div>
                        </div>

                        <h2 class="h5 mt-5">Campanha</h2>
                        <div class="row g-3">
                            <div class="col-12"><label class="form-label" for="campaign_title">Nome da campanha</label><input class="form-control" id="campaign_title" name="campaign_title" value="{{ old('campaign_title') }}" required></div>
                            <div class="col-12"><label class="form-label" for="destination_url">Endereço de destino</label><input class="form-control" type="url" id="destination_url" name="destination_url" placeholder="https://" value="{{ old('destination_url') }}" required></div>
                            <div class="col-md-6"><label class="form-label" for="starts_at">Data inicial</label><input class="form-control" type="date" id="starts_at" name="starts_at" min="{{ now()->toDateString() }}" value="{{ old('starts_at', now()->toDateString()) }}" required></div>
                            <div class="col-md-6"><label class="form-label" for="ends_at">Data final</label><input class="form-control" type="date" id="ends_at" name="ends_at" min="{{ now()->toDateString() }}" value="{{ old('ends_at', now()->addDays(29)->toDateString()) }}" required></div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <div class="form-check"><input type="hidden" name="targets_all_categories" value="0"><input class="form-check-input target-toggle" type="checkbox" name="targets_all_categories" value="1" id="all-categories" @checked(old('targets_all_categories', '1'))><label class="form-check-label fw-semibold" for="all-categories">Todas as categorias</label></div>
                                <div class="target-options mt-2" data-for="all-categories">@foreach($categories as $code => $label)<div class="form-check"><input class="form-check-input" type="checkbox" name="target_categories[]" value="{{ $code }}" id="category-{{ $code }}" @checked(in_array($code, old('target_categories', [])))><label class="form-check-label" for="category-{{ $code }}">{{ $label }}</label></div>@endforeach</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check"><input type="hidden" name="targets_all_regions" value="0"><input class="form-check-input target-toggle" type="checkbox" name="targets_all_regions" value="1" id="all-regions" @checked(old('targets_all_regions', '1'))><label class="form-check-label fw-semibold" for="all-regions">Todo o país</label></div>
                                <div class="target-options mt-2 advertising-region-options" data-for="all-regions">@foreach($regions as $code => $label)<div class="form-check"><input class="form-check-input" type="checkbox" name="target_regions[]" value="{{ $code }}" id="region-{{ $code }}" @checked(in_array($code, old('target_regions', [])))><label class="form-check-label" for="region-{{ $code }}">{{ $label }}</label></div>@endforeach</div>
                            </div>
                        </div>

                        <h2 class="h5 mt-5">Pagamento</h2>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label" for="payment_method">Método</label><select class="form-select" name="payment_method" id="payment_method" required><option value="multibanco" @selected(old('payment_method') === 'multibanco')>Multibanco</option><option value="mbway" @selected(old('payment_method') === 'mbway')>MB WAY</option></select></div>
                            <div class="col-md-6" id="mbway-field"><label class="form-label" for="mbway_phone">Número MB WAY</label><input class="form-control" id="mbway_phone" name="mbway_phone" inputmode="numeric" maxlength="9" value="{{ old('mbway_phone') }}"></div>
                        </div>
                        <div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="consent_terms" value="1" id="consent_terms" required @checked(old('consent_terms'))><label class="form-check-label" for="consent_terms">Confirmo que os dados são corretos e aceito o tratamento necessário à faturação, pagamento e gestão da campanha.</label></div>
                        <button class="btn btn-success btn-lg mt-4" type="submit" @disabled(!$paymentReady)>Pedir posição e pagar</button>
                    </form>
                </div></div>
            </div>
            <div class="col-lg-4"><div class="card border-success sticky-lg-top ad-order-summary"><div class="card-body">
                <h2 class="h5">Resumo</h2>
                <dl class="row mb-3"><dt class="col-7">Posição</dt><dd class="col-5 text-end">{{ $position->name }}</dd><dt class="col-7">Preço diário</dt><dd class="col-5 text-end">{{ $position->daily_price ? number_format((float) $position->daily_price, 2, ',', '.') . ' €' : '—' }}</dd><dt class="col-7">Dias</dt><dd class="col-5 text-end" id="summary-days">30</dd></dl>
                <div class="d-flex justify-content-between border-top pt-3"><strong>Total</strong><strong class="text-success h4 mb-0" id="summary-total">—</strong></div>
                <p class="small text-muted mt-3 mb-0">O valor é recalculado no servidor. A campanha fica em rascunho após confirmação do pagamento, para validação dos criativos.</p>
            </div></div></div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
(function () {
    const dailyPrice = Number(@json((float) $position->daily_price));
    const start = document.getElementById('starts_at');
    const end = document.getElementById('ends_at');
    const daysOutput = document.getElementById('summary-days');
    const totalOutput = document.getElementById('summary-total');
    const customerType = document.getElementById('customer_type');
    const companyField = document.getElementById('company-field');
    const companyName = document.getElementById('company_name');
    const method = document.getElementById('payment_method');
    const mbwayField = document.getElementById('mbway-field');
    const mbwayPhone = document.getElementById('mbway_phone');

    function updateTotal() {
        const from = new Date(start.value + 'T00:00:00');
        const to = new Date(end.value + 'T00:00:00');
        const days = Math.floor((to - from) / 86400000) + 1;
        daysOutput.textContent = days > 0 ? days : '—';
        totalOutput.textContent = days > 0 && dailyPrice > 0 ? (days * dailyPrice).toLocaleString('pt-PT', {style: 'currency', currency: 'EUR'}) : '—';
        end.min = start.value;
    }
    function updateCustomer() {
        const company = customerType.value === 'company';
        companyField.classList.toggle('d-none', !company);
        companyName.required = company;
    }
    function updateMethod() {
        const mbway = method.value === 'mbway';
        mbwayField.classList.toggle('d-none', !mbway);
        mbwayPhone.required = mbway;
    }
    function updateTargets(toggle) {
        const options = document.querySelector('[data-for="' + toggle.id + '"]');
        options.classList.toggle('d-none', toggle.checked);
        options.querySelectorAll('input').forEach(input => input.disabled = toggle.checked);
    }
    [start, end].forEach(input => input.addEventListener('change', updateTotal));
    customerType.addEventListener('change', updateCustomer);
    method.addEventListener('change', updateMethod);
    document.querySelectorAll('.target-toggle').forEach(toggle => { toggle.addEventListener('change', () => updateTargets(toggle)); updateTargets(toggle); });
    updateTotal(); updateCustomer(); updateMethod();
})();
</script>
@endpush
