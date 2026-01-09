@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.raffleRule.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.raffle-rules.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.raffleRule.fields.id') }}</th>
                        <td>{{ $raffleRule->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleRule.fields.amount') }}</th>
                        <td>{{ $raffleRule->amount }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleRule.fields.numbers') }}</th>
                        <td>{{ $raffleRule->numbers }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.raffleRule.fields.active') }}</th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $raffleRule->active ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.raffle-rules.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
