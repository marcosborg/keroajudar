<div class="form-group">
    <label class="required" for="type">{{ trans('cruds.advertisement.fields.type') }}</label>
    <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
        @foreach($types as $value => $label)
            <option value="{{ $value }}" {{ old('type', $advertisement->type ?? 'game') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @if($errors->has('type'))
        <div class="invalid-feedback">{{ $errors->first('type') }}</div>
    @endif
</div>

<div class="form-group">
    <label class="required" for="title">{{ trans('cruds.advertisement.fields.title') }}</label>
    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $advertisement->title ?? '') }}" required>
    @if($errors->has('title'))
        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
    @endif
</div>

<div class="form-group">
    <label for="subtitle">{{ trans('cruds.advertisement.fields.subtitle') }}</label>
    <input class="form-control {{ $errors->has('subtitle') ? 'is-invalid' : '' }}" type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $advertisement->subtitle ?? '') }}">
    @if($errors->has('subtitle'))
        <div class="invalid-feedback">{{ $errors->first('subtitle') }}</div>
    @endif
</div>

<div class="form-group">
    <label for="draw_date">{{ trans('cruds.advertisement.fields.draw_date') }}</label>
    <input class="form-control date {{ $errors->has('draw_date') ? 'is-invalid' : '' }}" type="text" name="draw_date" id="draw_date" value="{{ old('draw_date', optional($advertisement?->draw_date ?? null)->format(config('panel.date_format', 'Y-m-d'))) }}">
    @if($errors->has('draw_date'))
        <div class="invalid-feedback">{{ $errors->first('draw_date') }}</div>
    @endif
    <span class="help-block">{{ trans('cruds.advertisement.fields.draw_date_helper') }}</span>
</div>

<div class="form-group">
    <label for="link_url">{{ trans('cruds.advertisement.fields.link_url') }}</label>
    <input class="form-control {{ $errors->has('link_url') ? 'is-invalid' : '' }}" type="url" name="link_url" id="link_url" value="{{ old('link_url', $advertisement->link_url ?? '') }}">
    @if($errors->has('link_url'))
        <div class="invalid-feedback">{{ $errors->first('link_url') }}</div>
    @endif
</div>

<div class="form-group">
    <label for="sort_order">{{ trans('cruds.advertisement.fields.sort_order') }}</label>
    <input class="form-control {{ $errors->has('sort_order') ? 'is-invalid' : '' }}" type="number" min="0" step="1" name="sort_order" id="sort_order" value="{{ old('sort_order', $advertisement->sort_order ?? 0) }}">
    @if($errors->has('sort_order'))
        <div class="invalid-feedback">{{ $errors->first('sort_order') }}</div>
    @endif
</div>

<div class="form-group">
    <label for="logo">{{ trans('cruds.advertisement.fields.logo') }}</label>
    <div class="needsclick dropzone {{ $errors->has('logo') ? 'is-invalid' : '' }}" id="logo-dropzone"></div>
    @if($errors->has('logo'))
        <div class="invalid-feedback">{{ $errors->first('logo') }}</div>
    @endif
    <span class="help-block">{{ trans('cruds.advertisement.fields.logo_helper') }}</span>
</div>

<div class="form-group">
    <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
        <input type="hidden" name="active" value="0">
        <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', $advertisement->active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="active">{{ trans('cruds.advertisement.fields.active') }}</label>
    </div>
    @if($errors->has('active'))
        <div class="invalid-feedback">{{ $errors->first('active') }}</div>
    @endif
</div>

<div class="form-group">
    <button class="btn btn-danger" type="submit">
        {{ trans('global.save') }}
    </button>
</div>
