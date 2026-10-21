@extends('layouts.admin')
@section('content')
<div class="card"><div class="card-header">Nova campanha</div><div class="card-body">
<form method="POST" action="{{ route('admin.ad-campaigns.store') }}">@csrf
    @include('admin.adCampaigns.partials.form', ['campaign' => null])
</form></div></div>
@endsection
@include('admin.adCampaigns.partials.media-scripts', ['campaign' => null])
