<?php

namespace App\Http\Requests;

use App\Models\AdCampaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('ad_campaign_create') ?? false;
    }

    public function rules(): array
    {
        return $this->campaignRules();
    }

    protected function campaignRules(): array
    {
        return [
            'ad_position_id' => ['required', 'exists:ad_positions,id'],
            'beneficiary_id' => ['nullable', 'exists:beneficiaries,id'],
            'advertiser_name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'destination_url' => ['required', 'url', 'max:2048'],
            'status' => ['required', Rule::in(array_keys(AdCampaign::STATUSES))],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'targets_all_categories' => ['nullable', 'boolean'],
            'target_categories' => ['nullable', 'array'],
            'target_categories.*' => ['string', Rule::in(array_keys(config('advertising.categories')))],
            'targets_all_regions' => ['nullable', 'boolean'],
            'target_regions' => ['nullable', 'array'],
            'target_regions.*' => ['string', Rule::in(array_keys(config('advertising.regions')))],
            'desktop_creative' => ['nullable', 'string'],
            'mobile_creative' => ['nullable', 'string'],
            'remove_desktop_creative' => ['nullable', 'boolean'],
            'remove_mobile_creative' => ['nullable', 'boolean'],
        ];
    }
}
