<?php

namespace App\Services;

use App\Models\AdDailyMetric;
use Illuminate\Database\QueryException;

class AdvertisingMetricService
{
    public function increment(array $payload, string $column): void
    {
        if (! in_array($column, ['impressions', 'clicks'], true)) {
            throw new \InvalidArgumentException('Invalid advertising metric.');
        }

        $attributes = [
            'ad_campaign_id' => $payload['campaign_id'],
            'ad_position_id' => $payload['position_id'],
            'metric_date' => today()->toDateString(),
            'category_code' => $payload['category'] ?? 'all',
            'region_code' => $payload['region'] ?? 'all',
        ];

        try {
            $metric = AdDailyMetric::firstOrCreate($attributes);
        } catch (QueryException) {
            $metric = AdDailyMetric::where($attributes)->firstOrFail();
        }

        $metric->increment($column);
    }
}
