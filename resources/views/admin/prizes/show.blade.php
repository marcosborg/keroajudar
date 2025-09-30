@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.prize.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.prizes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.prize.fields.id') }}
                        </th>
                        <td>
                            {{ $prize->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prize.fields.name') }}
                        </th>
                        <td>
                            {{ $prize->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prize.fields.description') }}
                        </th>
                        <td>
                            {{ $prize->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prize.fields.value') }}
                        </th>
                        <td>
                            {{ $prize->value }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.prizes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection