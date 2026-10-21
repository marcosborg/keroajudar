@section('scripts')
@parent
<script>
function configureAdDropzone(selector, field, existing) {
    Dropzone.options[selector] = {
        url: '{{ route('admin.ad-campaigns.storeMedia') }}', maxFilesize: 5, acceptedFiles: '.jpeg,.jpg,.png,.webp', maxFiles: 1,
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        success: function(file, response) { $('form').find('input[name="'+field+'"],input[name="remove_'+field+'"]').remove(); $('form').append('<input type="hidden" name="'+field+'" value="'+response.name+'">'); },
        removedfile: function(file) { file.previewElement.remove(); if (file.status !== 'error') { $('form').find('input[name="'+field+'"]').remove(); $('form').find('input[name="remove_'+field+'"]').remove(); $('form').append('<input type="hidden" name="remove_'+field+'" value="1">'); } },
        init: function() {
            @if(isset($campaign) && $campaign)
            const data = existing;
            if (data) { const file = {name:data.file_name, size:data.size}; this.options.addedfile.call(this,file); this.options.thumbnail.call(this,file,data.thumbnail || data.url); file.previewElement.classList.add('dz-complete'); $('form').append('<input type="hidden" name="'+field+'" value="'+data.file_name+'">'); this.files.push(file); }
            @endif
        }
    };
}
configureAdDropzone('desktopCreativeDropzone', 'desktop_creative', @json(isset($campaign) && $campaign->desktop_creative ? $campaign->desktop_creative : null));
configureAdDropzone('mobileCreativeDropzone', 'mobile_creative', @json(isset($campaign) && $campaign->mobile_creative ? $campaign->mobile_creative : null));
const positionSizes = @json($positions->mapWithKeys(fn($p) => [$p->id => $p->desktopDimensions()]));
function updateSizeHelp(){ document.getElementById('desktop-size-help').textContent = '('+(positionSizes[document.getElementById('ad_position_id').value] || 'selecione a posição')+')'; }
document.getElementById('ad_position_id').addEventListener('change', updateSizeHelp); updateSizeHelp();
</script>
@endsection
