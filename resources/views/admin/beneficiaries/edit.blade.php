@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.beneficiary.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.beneficiaries.update", [$beneficiary->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="beneficiary_category_id">{{ trans('cruds.beneficiary.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('beneficiary_category_id') ? 'is-invalid' : '' }}" name="beneficiary_category_id" id="beneficiary_category_id" required>
                    @foreach($beneficiary_categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('beneficiary_category_id') ? old('beneficiary_category_id') : $beneficiary->beneficiary_category_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('beneficiary_category_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('beneficiary_category_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.beneficiary.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $beneficiary->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.beneficiary.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $beneficiary->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="about">{{ trans('cruds.beneficiary.fields.about') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('about') ? 'is-invalid' : '' }}" name="about" id="about">{!! old('about', $beneficiary->about) !!}</textarea>
                @if($errors->has('about'))
                    <div class="invalid-feedback">
                        {{ $errors->first('about') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.about_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="vat_number">{{ trans('cruds.beneficiary.fields.vat_number') }}</label>
                <input class="form-control {{ $errors->has('vat_number') ? 'is-invalid' : '' }}" type="text" name="vat_number" id="vat_number" value="{{ old('vat_number', $beneficiary->vat_number) }}">
                @if($errors->has('vat_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('vat_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.vat_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contact_email">{{ trans('cruds.beneficiary.fields.contact_email') }}</label>
                <input class="form-control {{ $errors->has('contact_email') ? 'is-invalid' : '' }}" type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $beneficiary->contact_email) }}">
                @if($errors->has('contact_email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contact_email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.contact_email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contact_phone">{{ trans('cruds.beneficiary.fields.contact_phone') }}</label>
                <input class="form-control {{ $errors->has('contact_phone') ? 'is-invalid' : '' }}" type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $beneficiary->contact_phone) }}">
                @if($errors->has('contact_phone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contact_phone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.contact_phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="website">{{ trans('cruds.beneficiary.fields.website') }}</label>
                <input class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}" type="text" name="website" id="website" value="{{ old('website', $beneficiary->website) }}">
                @if($errors->has('website'))
                    <div class="invalid-feedback">
                        {{ $errors->first('website') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.website_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.beneficiary.fields.address') }}</label>
                <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', $beneficiary->address) }}">
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="city">{{ trans('cruds.beneficiary.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', $beneficiary->city) }}">
                @if($errors->has('city'))
                    <div class="invalid-feedback">
                        {{ $errors->first('city') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="country">{{ trans('cruds.beneficiary.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', $beneficiary->country) }}">
                @if($errors->has('country'))
                    <div class="invalid-feedback">
                        {{ $errors->first('country') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="photo">{{ trans('cruds.beneficiary.fields.photo') }}</label>
                <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}" id="photo-dropzone">
                </div>
                @if($errors->has('photo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('photo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.photo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="logo_square">{{ trans('cruds.beneficiary.fields.logo_square') }}</label>
                <div class="needsclick dropzone {{ $errors->has('logo_square') ? 'is-invalid' : '' }}" id="logo_square-dropzone">
                </div>
                @if($errors->has('logo_square'))
                    <div class="invalid-feedback">
                        {{ $errors->first('logo_square') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.logo_square_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', $beneficiary->active) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.beneficiary.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.beneficiary.fields.active_helper') }}</span>
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
@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.beneficiaries.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $beneficiary->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>
<script>
    Dropzone.options.photoDropzone = {
    url: '{{ route('admin.beneficiaries.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 1600,
      height: 1600
    },
    success: function (file, response) {
      $('form').find('input[name="photo"]').remove()
      $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="photo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($beneficiary) && $beneficiary->photo)
      var file = {!! json_encode($beneficiary->photo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        var message = $.type(response) === 'string' ? response : (response.errors && response.errors.file ? response.errors.file : 'Upload error')
        file.previewElement.classList.add('dz-error')
        var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _ref.forEach(function (node) { node.textContent = message })
    }
}

</script>
<script>
    Dropzone.options.logoSquareDropzone = {
    url: '{{ route('admin.beneficiaries.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 300,
      height: 300
    },
    success: function (file, response) {
      $('form').find('input[name="logo_square"]').remove()
      $('form').append('<input type="hidden" name="logo_square" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="logo_square"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($beneficiary) && $beneficiary->logo_square)
      var file = {!! json_encode($beneficiary->logo_square) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="logo_square" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        var message = $.type(response) === 'string' ? response : (response.errors && response.errors.file ? response.errors.file : 'Upload error')
        file.previewElement.classList.add('dz-error')
        var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _ref.forEach(function (node) { node.textContent = message })
    }
}

</script>
@endsection
