<?php

namespace App\Services;

use App\Models\AdCampaign;
use App\Models\AdPosition;
use Illuminate\Http\Request;

class HomeAdvertisingService
{
    public function __construct(private AdvertisingTokenService $tokens) {}

    public function filters(Request $request): array
    {
        $category = array_key_exists((string) $request->query('ad_category'), config('advertising.categories'))
            ? (string) $request->query('ad_category') : 'all';
        $region = array_key_exists((string) $request->query('ad_region'), config('advertising.regions'))
            ? (string) $request->query('ad_region') : 'all';

        return compact('category', 'region');
    }

    public function slots(Request $request, string $category, string $region)
    {
        $sessionKey = $request->session()->getId() ?: 'guest';
        $rotationWindow = now()->format('Y-m-d-H');

        return AdPosition::query()->where('active', true)->orderBy('sort_order')->get()->map(function (AdPosition $position) use ($category, $region, $sessionKey, $rotationWindow) {
            $query = AdCampaign::query()->eligible()->where('ad_position_id', $position->id)
                ->whereHas('media', fn ($q) => $q->where('collection_name', 'desktop_creative'))
                ->whereHas('media', fn ($q) => $q->where('collection_name', 'mobile_creative'))
                ->with('media');

            if ($category === 'all') {
                $query->where('targets_all_categories', true);
            } else {
                $query->where(fn ($q) => $q->where('targets_all_categories', true)->orWhereJsonContains('target_categories', $category));
            }

            if ($region === 'all') {
                $query->where('targets_all_regions', true);
            } else {
                $query->where(fn ($q) => $q->where('targets_all_regions', true)->orWhereJsonContains('target_regions', $region));
            }

            $campaigns = $query->orderBy('id')->get();
            $campaign = null;
            if ($campaigns->isNotEmpty()) {
                $hash = (int) sprintf('%u', crc32("{$sessionKey}|{$rotationWindow}|{$position->code}"));
                $campaign = $campaigns[$hash % $campaigns->count()];
            }

            if ($campaign) {
                return (object) [
                    'type' => 'campaign',
                    'position' => $position,
                    'campaign' => $campaign,
                    'impression_token' => $this->tokens->issue('impression', $campaign, $position, $category, $region),
                    'click_url' => route('advertising.click', ['token' => $this->tokens->issue('click', $campaign, $position, $category, $region)]),
                ];
            }

            $adsenseSlot = config("advertising.adsense.slots.{$position->code}");
            $hasAdsense = config('advertising.adsense.enabled') && config('advertising.adsense.client') && $adsenseSlot;

            return (object) [
                'type' => $hasAdsense ? 'adsense' : 'available',
                'position' => $position,
                'adsense_slot' => $adsenseSlot,
            ];
        });
    }
}
