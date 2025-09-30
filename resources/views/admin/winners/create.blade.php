@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.winner.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.winners.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="entry_id">{{ trans('cruds.winner.fields.entry') }}</label>
                <select class="form-control select2 {{ $errors->has('entry') ? 'is-invalid' : '' }}" name="entry_id" id="entry_id" required>
                    @foreach($entries as $id => $entry)
                        <option value="{{ $id }}" {{ old('entry_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('entry'))
                    <div class="invalid-feedback">
                        {{ $errors->first('entry') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.winner.fields.entry_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="prize_id">{{ trans('cruds.winner.fields.prize') }}</label>
                <select class="form-control select2 {{ $errors->has('prize') ? 'is-invalid' : '' }}" name="prize_id" id="prize_id" required>
                    @foreach($prizes as $id => $entry)
                        <option value="{{ $id }}" {{ old('prize_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('prize'))
                    <div class="invalid-feedback">
                        {{ $errors->first('prize') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.winner.fields.prize_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="draw_date">{{ trans('cruds.winner.fields.draw_date') }}</label>
                <input class="form-control datetime {{ $errors->has('draw_date') ? 'is-invalid' : '' }}" type="text" name="draw_date" id="draw_date" value="{{ old('draw_date') }}">
                @if($errors->has('draw_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('draw_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.winner.fields.draw_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.winner.fields.notes') }}</label>
                <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{{ old('notes') }}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.winner.fields.notes_helper') }}</span>
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