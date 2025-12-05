@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.beneficiaryCategory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.beneficiary-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiaryCategory.fields.id') }}
                        </th>
                        <td>
                            {{ $beneficiaryCategory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiaryCategory.fields.image') }}
                        </th>
                        <td>
                            @if($beneficiaryCategory->image)
                                <a href="{{ $beneficiaryCategory->image->url }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $beneficiaryCategory->image->thumbnail }}" alt="">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiaryCategory.fields.name') }}
                        </th>
                        <td>
                            {{ $beneficiaryCategory->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiaryCategory.fields.description') }}
                        </th>
                        <td>
                            {{ $beneficiaryCategory->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.beneficiary-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
