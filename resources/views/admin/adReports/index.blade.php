@extends('layouts.admin')
@section('content')
<div class="card"><div class="card-header">Relatório de publicidade</div><div class="card-body">
<form method="GET" class="row align-items-end mb-4">
<div class="form-group col-md-2"><label>De</label><input type="date" class="form-control" name="from" value="{{ $from }}"></div>
<div class="form-group col-md-2"><label>Até</label><input type="date" class="form-control" name="to" value="{{ $to }}"></div>
<div class="form-group col-md-3"><label>Campanha</label><select class="form-control" name="campaign_id"><option value="">Todas</option>@foreach($campaigns as $campaign)<option value="{{ $campaign->id }}" {{ (string)request('campaign_id') === (string)$campaign->id ? 'selected' : '' }}>{{ $campaign->title }}</option>@endforeach</select></div>
<div class="form-group col-md-2"><label>Posição</label><select class="form-control" name="position_id"><option value="">Todas</option>@foreach($positions as $position)<option value="{{ $position->id }}" {{ (string)request('position_id') === (string)$position->id ? 'selected' : '' }}>{{ $position->name }}</option>@endforeach</select></div>
<div class="form-group col-md-3"><button class="btn btn-primary">Filtrar</button> <a class="btn btn-success" href="{{ route('admin.ad-reports.export', request()->query()) }}">Exportar CSV</a></div>
</form>
<div class="row mb-4">
<div class="col-md-4"><div class="card bg-light"><div class="card-body"><small>Impressões</small><h3>{{ number_format($totals->impressions, 0, ',', '.') }}</h3></div></div></div>
<div class="col-md-4"><div class="card bg-light"><div class="card-body"><small>Cliques</small><h3>{{ number_format($totals->clicks, 0, ',', '.') }}</h3></div></div></div>
<div class="col-md-4"><div class="card bg-light"><div class="card-body"><small>CTR</small><h3>{{ $totals->impressions ? number_format(($totals->clicks/$totals->impressions)*100, 2, ',', '.') : '0,00' }}%</h3></div></div></div>
</div>
<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Campanha</th><th>Posição</th><th>Impressões</th><th>Cliques</th><th>CTR</th></tr></thead><tbody>
@forelse($rows as $row)<tr><td>{{ $row->campaign?->title }}</td><td>{{ $row->position?->name }}</td><td>{{ number_format($row->impressions,0,',','.') }}</td><td>{{ number_format($row->clicks,0,',','.') }}</td><td>{{ $row->impressions ? number_format(($row->clicks/$row->impressions)*100,2,',','.') : '0,00' }}%</td></tr>@empty<tr><td colspan="5" class="text-center text-muted">Sem dados no período selecionado.</td></tr>@endforelse
</tbody></table></div>
</div></div>
@endsection
