@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.beneficiary.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.beneficiaries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.id') }}
                        </th>
                        <td>
                            {{ $beneficiary->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.photo') }}
                        </th>
                        <td>
                            @if($beneficiary->photo)
                                <a href="{{ $beneficiary->photo->url }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $beneficiary->photo->thumbnail }}" alt="">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.name') }}
                        </th>
                        <td>
                            {{ $beneficiary->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.logo_square') }}
                        </th>
                        <td>
                            @if($beneficiary->logo_square)
                                <a href="{{ $beneficiary->logo_square->url }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $beneficiary->logo_square->thumbnail }}" alt="">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.category') }}
                        </th>
                        <td>
                            {{ $beneficiary->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.vat_number') }}
                        </th>
                        <td>
                            {{ $beneficiary->vat_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.commercial_certificate_code') }}
                        </th>
                        <td>
                            {{ $beneficiary->commercial_certificate_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.iban') }}
                        </th>
                        <td>
                            {{ $beneficiary->iban }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.contact_email') }}
                        </th>
                        <td>
                            {{ $beneficiary->contact_email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.contact_phone') }}
                        </th>
                        <td>
                            {{ $beneficiary->contact_phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.website') }}
                        </th>
                        <td>
                            @if($beneficiary->website)
                                <a href="{{ $beneficiary->website }}" target="_blank" rel="noopener">{{ $beneficiary->website }}</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.address') }}
                        </th>
                        <td>
                            {{ $beneficiary->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.postal_code') }}
                        </th>
                        <td>
                            {{ $beneficiary->postal_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.default_commission_percent') }}
                        </th>
                        <td>
                            {{ number_format((float) $beneficiary->default_commission_percent, 2, ',', '.') }}%
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.city') }}
                        </th>
                        <td>
                            {{ $beneficiary->city }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.country') }}
                        </th>
                        <td>
                            {{ $beneficiary->country }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.description') }}
                        </th>
                        <td>
                            {{ $beneficiary->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.about') }}
                        </th>
                        <td>
                            {!! $beneficiary->about !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.beneficiary.fields.active') }}
                        </th>
                        <td>
                            {{ $beneficiary->active ? trans('global.yes') : trans('global.no') }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.beneficiaries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
