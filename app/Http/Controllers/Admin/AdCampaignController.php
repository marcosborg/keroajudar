<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAdCampaignRequest;
use App\Http\Requests\UpdateAdCampaignRequest;
use App\Models\AdCampaign;
use App\Models\AdPosition;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AdCampaignController extends Controller
{
    use MediaUploadingTrait {
        storeMedia as protected traitStoreMedia;
    }

    public function index()
    {
        abort_if(Gate::denies('ad_campaign_access'), Response::HTTP_FORBIDDEN);
        $campaigns = AdCampaign::with(['position', 'beneficiary', 'media'])
            ->withSum('metrics', 'impressions')->withSum('metrics', 'clicks')
            ->orderByDesc('starts_at')->get();

        return view('admin.adCampaigns.index', compact('campaigns'));
    }

    public function create()
    {
        abort_if(Gate::denies('ad_campaign_create'), Response::HTTP_FORBIDDEN);

        return view('admin.adCampaigns.create', $this->formData());
    }

    public function store(StoreAdCampaignRequest $request)
    {
        $position = AdPosition::findOrFail($request->integer('ad_position_id'));
        $this->validateCreatives($request, $position);
        $campaign = AdCampaign::create($this->normalizedData($request));
        $this->syncCreative($campaign, $request->input('desktop_creative'), 'desktop_creative', $request->boolean('remove_desktop_creative'));
        $this->syncCreative($campaign, $request->input('mobile_creative'), 'mobile_creative', $request->boolean('remove_mobile_creative'));

        return redirect()->route('admin.ad-campaigns.index')->with('message', 'Campanha criada com sucesso.');
    }

    public function edit(AdCampaign $adCampaign)
    {
        abort_if(Gate::denies('ad_campaign_edit'), Response::HTTP_FORBIDDEN);

        return view('admin.adCampaigns.edit', array_merge($this->formData(), ['campaign' => $adCampaign->load('media')]));
    }

    public function update(UpdateAdCampaignRequest $request, AdCampaign $adCampaign)
    {
        $position = AdPosition::findOrFail($request->integer('ad_position_id'));
        $this->validateCreatives($request, $position, $adCampaign);
        $adCampaign->update($this->normalizedData($request));
        $this->syncCreative($adCampaign, $request->input('desktop_creative'), 'desktop_creative', $request->boolean('remove_desktop_creative'));
        $this->syncCreative($adCampaign, $request->input('mobile_creative'), 'mobile_creative', $request->boolean('remove_mobile_creative'));

        return redirect()->route('admin.ad-campaigns.index')->with('message', 'Campanha atualizada com sucesso.');
    }

    public function destroy(AdCampaign $adCampaign)
    {
        abort_if(Gate::denies('ad_campaign_delete'), Response::HTTP_FORBIDDEN);
        $adCampaign->delete();

        return back()->with('message', 'Campanha removida.');
    }

    public function storeMedia(Request $request)
    {
        abort_if(Gate::denies('ad_campaign_create') && Gate::denies('ad_campaign_edit'), Response::HTTP_FORBIDDEN);
        $request->validate(['file' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120']]);

        return $this->traitStoreMedia($request);
    }

    private function formData(): array
    {
        return [
            'positions' => AdPosition::where('active', true)->orderBy('sort_order')->get(),
            'statuses' => AdCampaign::STATUSES,
            'categories' => config('advertising.categories'),
            'regions' => config('advertising.regions'),
            'beneficiaries' => Beneficiary::query()->where('active', true)->orderBy('name')->get(),
        ];
    }

    private function normalizedData(Request $request): array
    {
        $data = $request->only(['ad_position_id', 'beneficiary_id', 'advertiser_name', 'title', 'destination_url', 'status', 'starts_at', 'ends_at']);
        $data['targets_all_categories'] = $request->boolean('targets_all_categories');
        $data['target_categories'] = $data['targets_all_categories'] ? null : array_values($request->input('target_categories', []));
        $data['targets_all_regions'] = $request->boolean('targets_all_regions');
        $data['target_regions'] = $data['targets_all_regions'] ? null : array_values($request->input('target_regions', []));

        if (! $data['targets_all_categories'] && empty($data['target_categories'])) {
            throw ValidationException::withMessages(['target_categories' => 'Selecione pelo menos uma categoria.']);
        }
        if (! $data['targets_all_regions'] && empty($data['target_regions'])) {
            throw ValidationException::withMessages(['target_regions' => 'Selecione pelo menos uma região.']);
        }

        return $data;
    }

    private function validateCreatives(Request $request, AdPosition $position, ?AdCampaign $campaign = null): void
    {
        $requirements = [
            'desktop_creative' => [$position->desktop_width, $position->desktop_height, 'desktop_creative'],
            'mobile_creative' => [$position->mobile_width, $position->mobile_height, 'mobile_creative'],
        ];

        foreach ($requirements as $field => [$width, $height, $collection]) {
            $fileName = $request->input($field);
            $existing = $campaign?->getMedia($collection)->last();
            $samePosition = ! $campaign || (int) $campaign->ad_position_id === (int) $position->id;
            $hasExisting = $samePosition && (bool) $existing && ! $request->boolean('remove_'.$field);
            if ($request->input('status') === AdCampaign::STATUS_ACTIVE && ! $fileName && ! $hasExisting) {
                throw ValidationException::withMessages([$field => "O criativo {$width}×{$height} é obrigatório para ativar a campanha."]);
            }
            if (! $fileName) {
                continue;
            }
            if ($hasExisting && $existing->file_name === basename($fileName)) {
                continue;
            }

            $path = storage_path('tmp/uploads/'.basename($fileName));
            $image = file_exists($path) ? @getimagesize($path) : false;
            $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
            if (! $image || ! in_array($image[2], $allowedTypes, true) || $image[0] !== $width || $image[1] !== $height) {
                throw ValidationException::withMessages([$field => "A imagem deve ser JPEG, PNG ou WebP com exatamente {$width}×{$height}px."]);
            }
        }
    }

    private function syncCreative(AdCampaign $campaign, ?string $fileName, string $collection, bool $remove): void
    {
        if ($remove) {
            $campaign->clearMediaCollection($collection);
        }
        if (! $fileName) {
            return;
        }

        $current = $campaign->getMedia($collection)->last();
        if ($current && $current->file_name === $fileName) {
            return;
        }
        $campaign->clearMediaCollection($collection);
        $campaign->addMedia(storage_path('tmp/uploads/'.basename($fileName)))->toMediaCollection($collection);
    }
}
