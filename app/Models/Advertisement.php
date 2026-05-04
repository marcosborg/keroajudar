<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Advertisement extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public const TYPE_GAME = 'game';
    public const TYPE_SPONSOR = 'sponsor';

    public const TYPES = [
        self::TYPE_GAME => 'Jogo / sorteio',
        self::TYPE_SPONSOR => 'Sponsor',
    ];

    public $table = 'advertisements';

    protected $appends = [
        'logo',
    ];

    protected $dates = [
        'draw_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'type',
        'title',
        'subtitle',
        'draw_date',
        'link_url',
        'sort_order',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'draw_date' => 'date',
        'sort_order' => 'integer',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 120, 80)->performOnCollections('logo');
        $this->addMediaConversion('preview')->fit('max', 420, 220)->performOnCollections('logo');
    }

    public function getLogoAttribute()
    {
        $file = $this->getMedia('logo')->last();

        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }

        return $file;
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
