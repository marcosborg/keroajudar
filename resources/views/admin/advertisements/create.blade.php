@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.advertisement.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.advertisements.store") }}" enctype="multipart/form-data">
            @csrf
            @include('admin.advertisements.partials.form', ['advertisement' => null])
        </form>
    </div>
</div>

@endsection

@section('scripts')
@include('admin.advertisements.partials.logo-dropzone')
@endsection
