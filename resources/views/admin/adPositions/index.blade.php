@extends('layouts.admin')
@section('content')
<div class="card"><div class="card-header">Posições fixas da home</div><div class="card-body">
@if(session('message'))<div class="alert alert-success">{{ session('message') }}</div>@endif
<p class="text-muted">As posições são estruturais e não podem ser eliminadas. Configure o preço diário para permitir o pagamento imediato; os IDs AdSense são definidos no ambiente.</p>
<div class="row">
@foreach($positions as $position)
<div class="col-md-6 col-xl-4 mb-3"><div class="card h-100"><div class="card-body">
    <div class="d-flex justify-content-between"><h5>{{ $position->name }}</h5><span class="badge badge-success">{{ $position->code }}</span></div>
    <dl class="row"><dt class="col-6">Faixa</dt><dd class="col-6">{{ $position->row }}</dd><dt class="col-6">Desktop</dt><dd class="col-6">{{ $position->desktopDimensions() }}</dd><dt class="col-6">Mobile</dt><dd class="col-6">{{ $position->mobileDimensions() }}</dd><dt class="col-6">Campanhas ativas</dt><dd class="col-6">{{ $position->active_campaigns_count }}</dd><dt class="col-6">AdSense</dt><dd class="col-6">{{ config('advertising.adsense.slots.'.$position->code) ? 'Configurado' : 'Por configurar' }}</dd></dl>
    @can('ad_campaign_edit')
    <form method="POST" action="{{ route('admin.ad-positions.pricing', $position) }}" class="form-inline align-items-end">
        @csrf @method('PATCH')
        <div class="form-group mr-2 mb-0"><label for="daily-price-{{ $position->id }}" class="d-block">Preço diário (€)</label><input id="daily-price-{{ $position->id }}" class="form-control" type="number" name="daily_price" min="0.01" step="0.01" value="{{ old('daily_price', $position->daily_price) }}" required></div>
        <button class="btn btn-success">Guardar</button>
    </form>
    @endcan
</div></div></div>
@endforeach
</div></div></div>
@endsection
