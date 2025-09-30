@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.entry.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.entries.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="raffle_code">{{ trans('cruds.entry.fields.raffle_code') }}</label>
                <input class="form-control {{ $errors->has('raffle_code') ? 'is-invalid' : '' }}" type="text" name="raffle_code" id="raffle_code" value="{{ old('raffle_code', '') }}" required>
                @if($errors->has('raffle_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('raffle_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.raffle_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.entry.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="first_name">{{ trans('cruds.entry.fields.first_name') }}</label>
                <input class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ old('first_name', '') }}" required>
                @if($errors->has('first_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('first_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.first_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="last_name">{{ trans('cruds.entry.fields.last_name') }}</label>
                <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', '') }}" required>
                @if($errors->has('last_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('last_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.last_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phone">{{ trans('cruds.entry.fields.phone') }}</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', '') }}">
                @if($errors->has('phone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="amount">{{ trans('cruds.entry.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_company') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_company" value="0">
                    <input class="form-check-input" type="checkbox" name="is_company" id="is_company" value="1" {{ old('is_company', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_company">{{ trans('cruds.entry.fields.is_company') }}</label>
                </div>
                @if($errors->has('is_company'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_company') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.is_company_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nif">{{ trans('cruds.entry.fields.nif') }}</label>
                <input class="form-control {{ $errors->has('nif') ? 'is-invalid' : '' }}" type="text" name="nif" id="nif" value="{{ old('nif', '') }}">
                @if($errors->has('nif'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nif') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.nif_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nipc">{{ trans('cruds.entry.fields.nipc') }}</label>
                <input class="form-control {{ $errors->has('nipc') ? 'is-invalid' : '' }}" type="text" name="nipc" id="nipc" value="{{ old('nipc', '') }}">
                @if($errors->has('nipc'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nipc') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.nipc_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.entry.fields.address') }}</label>
                <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', '') }}">
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="postal_code">{{ trans('cruds.entry.fields.postal_code') }}</label>
                <input class="form-control {{ $errors->has('postal_code') ? 'is-invalid' : '' }}" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', '') }}">
                @if($errors->has('postal_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('postal_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.postal_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="city">{{ trans('cruds.entry.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', '') }}" required>
                @if($errors->has('city'))
                    <div class="invalid-feedback">
                        {{ $errors->first('city') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="country_id">{{ trans('cruds.entry.fields.country') }}</label>
                <select class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" name="country_id" id="country_id" required>
                    @foreach($countries as $id => $entry)
                        <option value="{{ $id }}" {{ old('country_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                    <div class="invalid-feedback">
                        {{ $errors->first('country') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('consent_privacy') ? 'is-invalid' : '' }}">
                    <input class="form-check-input" type="checkbox" name="consent_privacy" id="consent_privacy" value="1" required {{ old('consent_privacy', 0) == 1 ? 'checked' : '' }}>
                    <label class="required form-check-label" for="consent_privacy">{{ trans('cruds.entry.fields.consent_privacy') }}</label>
                </div>
                @if($errors->has('consent_privacy'))
                    <div class="invalid-feedback">
                        {{ $errors->first('consent_privacy') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.consent_privacy_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.entry.fields.contact_via') }}</label>
                @foreach(App\Models\Entry::CONTACT_VIA_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('contact_via') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="contact_via_{{ $key }}" name="contact_via" value="{{ $key }}" {{ old('contact_via', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="contact_via_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('contact_via'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contact_via') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.contact_via_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="source_page">{{ trans('cruds.entry.fields.source_page') }}</label>
                <input class="form-control {{ $errors->has('source_page') ? 'is-invalid' : '' }}" type="text" name="source_page" id="source_page" value="{{ old('source_page', '') }}">
                @if($errors->has('source_page'))
                    <div class="invalid-feedback">
                        {{ $errors->first('source_page') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.source_page_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="client_ip">{{ trans('cruds.entry.fields.client_ip') }}</label>
                <input class="form-control {{ $errors->has('client_ip') ? 'is-invalid' : '' }}" type="text" name="client_ip" id="client_ip" value="{{ old('client_ip', '') }}">
                @if($errors->has('client_ip'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client_ip') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.client_ip_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="user_agent">{{ trans('cruds.entry.fields.user_agent') }}</label>
                <input class="form-control {{ $errors->has('user_agent') ? 'is-invalid' : '' }}" type="text" name="user_agent" id="user_agent" value="{{ old('user_agent', '') }}">
                @if($errors->has('user_agent'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_agent') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.entry.fields.user_agent_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection