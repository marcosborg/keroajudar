<?php

namespace App\Http\Requests;

class UpdateAdCampaignRequest extends StoreAdCampaignRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('ad_campaign_edit') ?? false;
    }
}
