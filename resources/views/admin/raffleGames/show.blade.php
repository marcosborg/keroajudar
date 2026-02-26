@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.raffleGame.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.raffle-games.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.raffleGame.fields.id') }}</th>
                        <td>{{ $raffleGame->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleGame.fields.name') }}</th>
                        <td>{{ $raffleGame->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleGame.fields.prize') }}</th>
                        <td>{{ $raffleGame->prize->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleGame.fields.starts_at') }}</th>
                        <td>{{ $raffleGame->starts_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleGame.fields.ends_at') }}</th>
                        <td>{{ $raffleGame->ends_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleGame.fields.commission_percent') }}</th>
                        <td>{{ number_format((float) $raffleGame->commission_percent, 2, ',', '.') }}%</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleGame.fields.active') }}</th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $raffleGame->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleGame.fields.description') }}</th>
                        <td>{{ $raffleGame->description }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.raffle-games.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
