@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.payment.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.payments.update", [$payment->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="entry_id">{{ trans('cruds.payment.fields.entry') }}</label>
                <select class="form-control select2 {{ $errors->has('entry') ? 'is-invalid' : '' }}" name="entry_id" id="entry_id" required>
                    @foreach($entries as $id => $entry)
                        <option value="{{ $id }}" {{ (old('entry_id') ? old('entry_id') : $payment->entry->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('entry'))
                    <div class="invalid-feedback">
                        {{ $errors->first('entry') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.entry_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="provider">{{ trans('cruds.payment.fields.provider') }}</label>
                <input class="form-control {{ $errors->has('provider') ? 'is-invalid' : '' }}" type="text" name="provider" id="provider" value="{{ old('provider', $payment->provider) }}">
                @if($errors->has('provider'))
                    <div class="invalid-feedback">
                        {{ $errors->first('provider') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.provider_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="transaction">{{ trans('cruds.payment.fields.transaction') }}</label>
                <input class="form-control {{ $errors->has('transaction') ? 'is-invalid' : '' }}" type="text" name="transaction" id="transaction" value="{{ old('transaction', $payment->transaction) }}">
                @if($errors->has('transaction'))
                    <div class="invalid-feedback">
                        {{ $errors->first('transaction') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.transaction_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="amount">{{ trans('cruds.payment.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" step="0.01">
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="currency">{{ trans('cruds.payment.fields.currency') }}</label>
                <input class="form-control {{ $errors->has('currency') ? 'is-invalid' : '' }}" type="text" name="currency" id="currency" value="{{ old('currency', $payment->currency) }}">
                @if($errors->has('currency'))
                    <div class="invalid-feedback">
                        {{ $errors->first('currency') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.currency_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.payment.fields.status') }}</label>
                @foreach(App\Models\Payment::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $payment->status) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.payment.fields.method') }}</label>
                @foreach(App\Models\Payment::METHOD_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('method') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="method_{{ $key }}" name="method" value="{{ $key }}" {{ old('method', $payment->method) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="method_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('method'))
                    <div class="invalid-feedback">
                        {{ $errors->first('method') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.method_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="paid_at">{{ trans('cruds.payment.fields.paid_at') }}</label>
                <input class="form-control datetime {{ $errors->has('paid_at') ? 'is-invalid' : '' }}" type="text" name="paid_at" id="paid_at" value="{{ old('paid_at', $payment->paid_at) }}">
                @if($errors->has('paid_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('paid_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.paid_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cancelled_at">{{ trans('cruds.payment.fields.cancelled_at') }}</label>
                <input class="form-control datetime {{ $errors->has('cancelled_at') ? 'is-invalid' : '' }}" type="text" name="cancelled_at" id="cancelled_at" value="{{ old('cancelled_at', $payment->cancelled_at) }}">
                @if($errors->has('cancelled_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cancelled_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.cancelled_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="raw_response">{{ trans('cruds.payment.fields.raw_response') }}</label>
                <textarea class="form-control {{ $errors->has('raw_response') ? 'is-invalid' : '' }}" name="raw_response" id="raw_response">{{ old('raw_response', $payment->raw_response) }}</textarea>
                @if($errors->has('raw_response'))
                    <div class="invalid-feedback">
                        {{ $errors->first('raw_response') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.raw_response_helper') }}</span>
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