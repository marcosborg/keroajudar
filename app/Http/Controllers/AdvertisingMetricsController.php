<?php

namespace App\Http\Controllers;

use App\Models\AdCampaign;
use App\Services\AdvertisingMetricService;
use App\Services\AdvertisingTokenService;
use Illuminate\Http\Request;

class AdvertisingMetricsController extends Controller
{
    public function impressions(Request $request, AdvertisingTokenService $tokens, AdvertisingMetricService $metrics)
    {
        $validated = $request->validate([
            'tokens' => ['required', 'array', 'max:10'],
            'tokens.*' => ['required', 'string', 'max:4096'],
        ]);

        $accepted = 0;
        foreach (array_unique($validated['tokens']) as $token) {
            $payload = $tokens->parse($token, 'impression');
            if (! $payload || ! AdCampaign::whereKey($payload['campaign_id'])->exists()) {
                continue;
            }

            $sessionKey = 'advertising.impression.'.hash('sha256', $token);
            if ($request->session()->has($sessionKey)) {
                continue;
            }

            $metrics->increment($payload, 'impressions');
            $request->session()->put($sessionKey, true);
            $accepted++;
        }

        return response()->json(['accepted' => $accepted]);
    }

    public function click(string $token, AdvertisingTokenService $tokens, AdvertisingMetricService $metrics)
    {
        $payload = $tokens->parse($token, 'click');
        abort_unless($payload, 404);

        $campaign = AdCampaign::query()->findOrFail($payload['campaign_id']);
        abort_unless((int) $campaign->ad_position_id === (int) $payload['position_id'], 404);

        $metrics->increment($payload, 'clicks');

        return redirect()->away($campaign->destination_url);
    }
}
