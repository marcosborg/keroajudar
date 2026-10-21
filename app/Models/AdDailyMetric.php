<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdDailyMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_campaign_id', 'ad_position_id', 'metric_date', 'category_code',
        'region_code', 'impressions', 'clicks',
    ];

    protected $casts = [
        'metric_date' => 'date',
        'impressions' => 'integer',
        'clicks' => 'integer',
    ];

    public function campaign()
    {
        return $this->belongsTo(AdCampaign::class, 'ad_campaign_id');
    }

    public function position()
    {
        return $this->belongsTo(AdPosition::class, 'ad_position_id');
    }
}
