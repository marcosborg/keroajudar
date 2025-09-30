@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.entry.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.id') }}
                        </th>
                        <td>
                            {{ $entry->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.raffle_code') }}
                        </th>
                        <td>
                            {{ $entry->raffle_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.email') }}
                        </th>
                        <td>
                            {{ $entry->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.first_name') }}
                        </th>
                        <td>
                            {{ $entry->first_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.last_name') }}
                        </th>
                        <td>
                            {{ $entry->last_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.phone') }}
                        </th>
                        <td>
                            {{ $entry->phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.amount') }}
                        </th>
                        <td>
                            {{ $entry->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.is_company') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $entry->is_company ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.nif') }}
                        </th>
                        <td>
                            {{ $entry->nif }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.nipc') }}
                        </th>
                        <td>
                            {{ $entry->nipc }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.address') }}
                        </th>
                        <td>
                            {{ $entry->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.postal_code') }}
                        </th>
                        <td>
                            {{ $entry->postal_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.city') }}
                        </th>
                        <td>
                            {{ $entry->city }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.country') }}
                        </th>
                        <td>
                            {{ $entry->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.consent_privacy') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $entry->consent_privacy ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.contact_via') }}
                        </th>
                        <td>
                            {{ App\Models\Entry::CONTACT_VIA_RADIO[$entry->contact_via] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.source_page') }}
                        </th>
                        <td>
                            {{ $entry->source_page }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.client_ip') }}
                        </th>
                        <td>
                            {{ $entry->client_ip }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entry.fields.user_agent') }}
                        </th>
                        <td>
                            {{ $entry->user_agent }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection