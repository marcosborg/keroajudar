@extends('layouts.admin')
@section('content')
<div class="card"><div class="card-header">Posições fixas da home</div><div class="card-body">
<p class="text-muted">As posições são estruturais e não podem ser eliminadas. Os IDs AdSense são definidos no ambiente.</p>
<div class="row">
@foreach($positions as $position)
<div class="col-md-6 col-xl-4 mb-3"><div class="card h-100"><div class="card-body">
    <div class="d-flex justify-content-between"><h5>{{ $position->name }}</h5><span class="badge badge-success">{{ $position->code }}</span></div>
    <dl class="row mb-0"><dt class="col-6">Faixa</dt><dd class="col-6">{{ $position->row }}</dd><dt class="col-6">Desktop</dt><dd class="col-6">{{ $position->desktopDimensions() }}</dd><dt class="col-6">Mobile</dt><dd class="col-6">{{ $position->mobileDimensions() }}</dd><dt class="col-6">Campanhas ativas</dt><dd class="col-6">{{ $position->active_campaigns_count }}</dd><dt class="col-6">AdSense</dt><dd class="col-6">{{ config('advertising.adsense.slots.'.$position->code) ? 'Configurado' : 'Por configurar' }}</dd></dl>
</div></div></div>
@endforeach
</div></div></div>
@endsection
