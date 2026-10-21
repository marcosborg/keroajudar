@extends('layouts.admin')
@section('content')
<div class="card"><div class="card-header">Editar campanha</div><div class="card-body">
<form method="POST" action="{{ route('admin.ad-campaigns.update', $campaign) }}">@csrf @method('PUT')
    @include('admin.adCampaigns.partials.form', ['campaign' => $campaign])
</form></div></div>
@endsection
@include('admin.adCampaigns.partials.media-scripts', ['campaign' => $campaign])
