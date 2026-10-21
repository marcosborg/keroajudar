@extends('layouts.admin')
@section('content')
@can('ad_campaign_create')
<div class="mb-3"><a class="btn btn-success" href="{{ route('admin.ad-campaigns.create') }}">Adicionar campanha</a></div>
@endcan
<div class="card">
    <div class="card-header">Campanhas do centro publicitário</div>
    <div class="card-body"><div class="table-responsive"><table class="table table-bordered table-striped table-hover">
        <thead><tr><th>Campanha</th><th>Anunciante</th><th>Posição</th><th>Estado</th><th>Período</th><th>Impressões</th><th>Cliques</th><th></th></tr></thead>
        <tbody>@foreach($campaigns as $campaign)<tr>
            <td>{{ $campaign->title }}</td><td>{{ $campaign->advertiser_name }}</td><td>{{ $campaign->position->name }}</td>
            <td>{{ \App\Models\AdCampaign::STATUSES[$campaign->status] ?? $campaign->status }}</td>
            <td>{{ $campaign->starts_at->format('d-m-Y H:i') }}<br>{{ $campaign->ends_at->format('d-m-Y H:i') }}</td>
            <td>{{ number_format($campaign->metrics_sum_impressions ?? 0, 0, ',', '.') }}</td><td>{{ number_format($campaign->metrics_sum_clicks ?? 0, 0, ',', '.') }}</td>
            <td class="text-nowrap">
                @can('ad_campaign_edit')<a class="btn btn-xs btn-info" href="{{ route('admin.ad-campaigns.edit', $campaign) }}">Editar</a>@endcan
                @can('ad_campaign_delete')<form action="{{ route('admin.ad-campaigns.destroy', $campaign) }}" method="POST" class="d-inline" onsubmit="return confirm('Remover esta campanha?')">@csrf @method('DELETE')<button class="btn btn-xs btn-danger">Apagar</button></form>@endcan
            </td>
        </tr>@endforeach</tbody>
    </table></div></div>
</div>
@endsection
