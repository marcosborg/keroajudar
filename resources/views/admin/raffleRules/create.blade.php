@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.raffleRule.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.raffle-rules.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="amount">{{ trans('cruds.raffleRule.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required>
                @if($errors->has('amount'))
                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleRule.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="numbers">{{ trans('cruds.raffleRule.fields.numbers') }}</label>
                <input class="form-control {{ $errors->has('numbers') ? 'is-invalid' : '' }}" type="number" name="numbers" id="numbers" value="{{ old('numbers') }}" step="1" min="1" required>
                @if($errors->has('numbers'))
                    <span class="text-danger">{{ $errors->first('numbers') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleRule.fields.numbers_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', 1) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.raffleRule.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <span class="text-danger">{{ $errors->first('active') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleRule.fields.active_helper') }}</span>
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
