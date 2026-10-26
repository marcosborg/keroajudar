@php
    $allCategories = old('targets_all_categories', $campaign->targets_all_categories ?? true);
    $allRegions = old('targets_all_regions', $campaign->targets_all_regions ?? true);
    $selectedCategories = old('target_categories', $campaign->target_categories ?? []);
    $selectedRegions = old('target_regions', $campaign->target_regions ?? []);
@endphp
<div class="row">
<div class="form-group col-md-6"><label class="required" for="advertiser_name">Anunciante</label><input class="form-control" name="advertiser_name" id="advertiser_name" required value="{{ old('advertiser_name', $campaign->advertiser_name ?? '') }}"></div>
<div class="form-group col-md-6"><label class="required" for="title">Título interno</label><input class="form-control" name="title" id="title" required value="{{ old('title', $campaign->title ?? '') }}"></div>
</div>
<div class="form-group"><label class="required" for="destination_url">URL de destino</label><input type="url" class="form-control" name="destination_url" id="destination_url" required value="{{ old('destination_url', $campaign->destination_url ?? '') }}"></div>
<div class="row">
<div class="form-group col-md-6"><label class="required" for="ad_position_id">Posição</label><select class="form-control" name="ad_position_id" id="ad_position_id" required><option value="">Selecione</option>@foreach($positions as $position)<option value="{{ $position->id }}" {{ (string)old('ad_position_id', $campaign->ad_position_id ?? '') === (string)$position->id ? 'selected' : '' }}>{{ $position->name }} — {{ $position->desktopDimensions() }} / {{ $position->mobileDimensions() }}</option>@endforeach</select></div>
<div class="form-group col-md-6"><label for="beneficiary_id">Associação apoiada</label><select class="form-control select2" name="beneficiary_id" id="beneficiary_id"><option value="">Sem associação</option>@foreach($beneficiaries as $beneficiary)<option value="{{ $beneficiary->id }}" {{ (string)old('beneficiary_id', $campaign->beneficiary_id ?? '') === (string)$beneficiary->id ? 'selected' : '' }}>{{ $beneficiary->name }}</option>@endforeach</select></div>
</div>
<div class="row">
<div class="form-group col-md-6"><label class="required" for="status">Estado</label><select class="form-control" name="status" id="status" required>@foreach($statuses as $value => $label)<option value="{{ $value }}" {{ old('status', $campaign->status ?? 'draft') === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
</div>
<div class="row">
<div class="form-group col-md-6"><label class="required" for="starts_at">Início</label><input class="form-control datetime" name="starts_at" id="starts_at" required value="{{ old('starts_at', isset($campaign) ? $campaign->starts_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s')) }}"></div>
<div class="form-group col-md-6"><label class="required" for="ends_at">Fim</label><input class="form-control datetime" name="ends_at" id="ends_at" required value="{{ old('ends_at', isset($campaign) ? $campaign->ends_at->format('Y-m-d H:i:s') : now()->addMonth()->format('Y-m-d H:i:s')) }}"></div>
</div>
<div class="row">
<div class="form-group col-md-6">
<input type="hidden" name="targets_all_categories" value="0"><label><input type="checkbox" name="targets_all_categories" value="1" {{ $allCategories ? 'checked' : '' }}> Todas as categorias</label>
<select class="form-control select2" name="target_categories[]" multiple>@foreach($categories as $code => $label)<option value="{{ $code }}" {{ in_array($code, $selectedCategories, true) ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select>
</div>
<div class="form-group col-md-6">
<input type="hidden" name="targets_all_regions" value="0"><label><input type="checkbox" name="targets_all_regions" value="1" {{ $allRegions ? 'checked' : '' }}> Todo o país</label>
<select class="form-control select2" name="target_regions[]" multiple>@foreach($regions as $code => $label)<option value="{{ $code }}" {{ in_array($code, $selectedRegions, true) ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select>
</div>
</div>
<div class="row">
<div class="form-group col-md-6"><label>Criativo desktop <small id="desktop-size-help"></small></label><div class="needsclick dropzone" id="desktop-creative-dropzone"></div><small class="text-muted">JPEG, PNG ou WebP; dimensão exata da posição.</small></div>
<div class="form-group col-md-6"><label>Criativo mobile <small>(320×100)</small></label><div class="needsclick dropzone" id="mobile-creative-dropzone"></div><small class="text-muted">JPEG, PNG ou WebP; exatamente 320×100px.</small></div>
</div>
<button class="btn btn-danger" type="submit">Guardar</button> <a class="btn btn-default" href="{{ route('admin.ad-campaigns.index') }}">Cancelar</a>
