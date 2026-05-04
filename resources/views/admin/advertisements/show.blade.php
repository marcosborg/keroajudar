@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.advertisement.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.advertisements.index') }}">{{ trans('global.back_to_list') }}</a>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
                <tr><th>{{ trans('cruds.advertisement.fields.id') }}</th><td>{{ $advertisement->id }}</td></tr>
                <tr><th>{{ trans('cruds.advertisement.fields.type') }}</th><td>{{ \App\Models\Advertisement::TYPES[$advertisement->type] ?? $advertisement->type }}</td></tr>
                <tr><th>{{ trans('cruds.advertisement.fields.title') }}</th><td>{{ $advertisement->title }}</td></tr>
                <tr><th>{{ trans('cruds.advertisement.fields.subtitle') }}</th><td>{{ $advertisement->subtitle }}</td></tr>
                <tr><th>{{ trans('cruds.advertisement.fields.draw_date') }}</th><td>{{ optional($advertisement->draw_date)->format('d-m-Y') }}</td></tr>
                <tr><th>{{ trans('cruds.advertisement.fields.link_url') }}</th><td>{{ $advertisement->link_url }}</td></tr>
                <tr><th>{{ trans('cruds.advertisement.fields.sort_order') }}</th><td>{{ $advertisement->sort_order }}</td></tr>
                <tr><th>{{ trans('cruds.advertisement.fields.active') }}</th><td>{{ $advertisement->active ? 'Sim' : 'Não' }}</td></tr>
                <tr>
                    <th>{{ trans('cruds.advertisement.fields.logo') }}</th>
                    <td>
                        @if($advertisement->logo)
                            <img src="{{ $advertisement->logo->preview }}" alt="{{ $advertisement->title }}" style="max-height: 120px;">
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.advertisements.index') }}">{{ trans('global.back_to_list') }}</a>
        </div>
    </div>
</div>

@endsection
