<?php

namespace App\Services;

use App\Models\AdCampaign;
use App\Models\AdPosition;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AdvertisingTokenService
{
    public function issue(string $type, AdCampaign $campaign, AdPosition $position, string $category, string $region): string
    {
        return Crypt::encryptString(json_encode([
            'type' => $type,
            'campaign_id' => $campaign->id,
            'position_id' => $position->id,
            'category' => $category,
            'region' => $region,
            'nonce' => Str::random(20),
            'expires_at' => now()->addHours(2)->timestamp,
        ], JSON_THROW_ON_ERROR));
    }

    public function parse(string $token, string $expectedType): ?array
    {
        try {
            $payload = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
        } catch (DecryptException|\JsonException) {
            return null;
        }

        if (($payload['type'] ?? null) !== $expectedType || ($payload['expires_at'] ?? 0) < now()->timestamp) {
            return null;
        }

        return $payload;
    }
}
