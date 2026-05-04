<script>
    Dropzone.options.logoDropzone = {
        url: '{{ route('admin.advertisements.storeMedia') }}',
        maxFilesize: 2,
        acceptedFiles: '.jpeg,.jpg,.png,.gif,.webp',
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
            $('form').find('input[name="logo"]').remove()
            $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
        },
        removedfile: function (file) {
            file.previewElement.remove()
            if (file.status !== 'error') {
                $('form').find('input[name="logo"]').remove()
                this.options.maxFiles = this.options.maxFiles + 1
            }
        },
        init: function () {
@if(isset($advertisement) && $advertisement && $advertisement->logo)
            var file = {!! json_encode($advertisement->logo) !!}
            this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
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
