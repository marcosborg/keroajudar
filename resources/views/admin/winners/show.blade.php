@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.winner.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.winners.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.winner.fields.id') }}
                        </th>
                        <td>
                            {{ $winner->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.winner.fields.entry') }}
                        </th>
                        <td>
                            {{ $winner->entry->raffle_code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.winner.fields.prize') }}
                        </th>
                        <td>
                            {{ $winner->prize->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.winner.fields.draw_date') }}
                        </th>
                        <td>
                            {{ $winner->draw_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.winner.fields.notes') }}
                        </th>
                        <td>
                            {{ $winner->notes }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.winners.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection