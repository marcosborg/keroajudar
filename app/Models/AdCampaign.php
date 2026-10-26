<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdCampaign extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_PAUSED = 'paused';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Rascunho',
        self::STATUS_ACTIVE => 'Ativa',
        self::STATUS_PAUSED => 'Pausada',
    ];

    protected $fillable = [
        'ad_position_id', 'beneficiary_id', 'advertiser_name', 'title', 'destination_url', 'status',
        'starts_at', 'ends_at', 'targets_all_categories', 'target_categories',
        'targets_all_regions', 'target_regions',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'targets_all_categories' => 'boolean',
        'targets_all_regions' => 'boolean',
        'target_categories' => 'array',
        'target_regions' => 'array',
    ];

    protected $appends = ['desktop_creative', 'mobile_creative'];

    public function position()
    {
        return $this->belongsTo(AdPosition::class, 'ad_position_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function metrics()
    {
        return $this->hasMany(AdDailyMetric::class);
    }

    public function scopeEligible($query, $at = null)
    {
        $at ??= now();

        return $query->where('status', self::STATUS_ACTIVE)
            ->where('starts_at', '<=', $at)
            ->where('ends_at', '>=', $at);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('max', 240, 160)
            ->performOnCollections('desktop_creative', 'mobile_creative');
    }

    public function getDesktopCreativeAttribute()
    {
        return $this->mediaFile('desktop_creative');
    }

    public function getMobileCreativeAttribute()
    {
        return $this->mediaFile('mobile_creative');
    }

    private function mediaFile(string $collection)
    {
        $file = $this->getMedia($collection)->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }
}
