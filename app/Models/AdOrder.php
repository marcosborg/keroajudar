<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdOrder extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const STATUS_PAYMENT_PENDING = 'payment_pending';
    public const STATUS_PAYMENT_FAILED = 'payment_failed';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PAYMENT_PENDING => 'A aguardar pagamento',
        self::STATUS_PAYMENT_FAILED => 'Falha no pagamento',
        self::STATUS_PAID => 'Pago',
        self::STATUS_CANCELLED => 'Cancelado',
    ];

    protected $fillable = [
        'public_id', 'ad_position_id', 'ad_campaign_id', 'payment_identifier', 'status',
        'payment_method', 'customer_type', 'contact_name', 'company_name', 'tax_number',
        'email', 'phone', 'address', 'postal_code', 'city', 'country', 'campaign_title',
        'destination_url', 'starts_at', 'ends_at', 'targets_all_categories',
        'target_categories', 'targets_all_regions', 'target_regions', 'billable_days',
        'daily_price', 'amount', 'consent_terms', 'failure_message', 'paid_at',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'target_categories' => 'array',
        'target_regions' => 'array',
        'targets_all_categories' => 'boolean',
        'targets_all_regions' => 'boolean',
        'consent_terms' => 'boolean',
        'daily_price' => 'decimal:2',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected $appends = ['desktop_creative', 'mobile_creative'];

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function position()
    {
        return $this->belongsTo(AdPosition::class, 'ad_position_id');
    }

    public function campaign()
    {
        return $this->belongsTo(AdCampaign::class, 'ad_campaign_id');
    }

    public function payment()
    {
        return $this->hasOne(Pedido::class, 'identificador', 'payment_identifier');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('max', 320, 180)
            ->performOnCollections('desktop_creative', 'mobile_creative');
    }

    public function getDesktopCreativeAttribute()
    {
        return $this->creativeFile('desktop_creative');
    }

    public function getMobileCreativeAttribute()
    {
        return $this->creativeFile('mobile_creative');
    }

    private function creativeFile(string $collection)
    {
        $file = $this->getMedia($collection)->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }
}
