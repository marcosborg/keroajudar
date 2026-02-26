@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.raffleGame.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.raffle-games.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.raffleGame.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleGame.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="prize_id">{{ trans('cruds.raffleGame.fields.prize') }}</label>
                <select class="form-control {{ $errors->has('prize_id') ? 'is-invalid' : '' }}" name="prize_id" id="prize_id">
                    @foreach($prizes as $id => $entry)
                        <option value="{{ $id }}" {{ old('prize_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('prize_id'))
                    <span class="text-danger">{{ $errors->first('prize_id') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleGame.fields.prize_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="starts_at">{{ trans('cruds.raffleGame.fields.starts_at') }}</label>
                <input class="form-control {{ $errors->has('starts_at') ? 'is-invalid' : '' }}" type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" required>
                @if($errors->has('starts_at'))
                    <span class="text-danger">{{ $errors->first('starts_at') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleGame.fields.starts_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="ends_at">{{ trans('cruds.raffleGame.fields.ends_at') }}</label>
                <input class="form-control {{ $errors->has('ends_at') ? 'is-invalid' : '' }}" type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" required>
                @if($errors->has('ends_at'))
                    <span class="text-danger">{{ $errors->first('ends_at') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleGame.fields.ends_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.raffleGame.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleGame.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="commission_percent">{{ trans('cruds.raffleGame.fields.commission_percent') }}</label>
                <input class="form-control {{ $errors->has('commission_percent') ? 'is-invalid' : '' }}" type="number" name="commission_percent" id="commission_percent" value="{{ old('commission_percent', 0) }}" min="0" max="100" step="0.01" required>
                @if($errors->has('commission_percent'))
                    <span class="text-danger">{{ $errors->first('commission_percent') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleGame.fields.commission_percent_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', 1) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.raffleGame.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <span class="text-danger">{{ $errors->first('active') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.raffleGame.fields.active_helper') }}</span>
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
