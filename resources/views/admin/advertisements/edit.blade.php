@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.advertisement.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.advertisements.update", [$advertisement->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            @include('admin.advertisements.partials.form', ['advertisement' => $advertisement])
        </form>
    </div>
</div>

@endsection

@section('scripts')
@include('admin.advertisements.partials.logo-dropzone')
@endsection
