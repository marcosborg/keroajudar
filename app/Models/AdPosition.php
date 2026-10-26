<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdPosition extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'row', 'sort_order', 'desktop_width', 'desktop_height', 'mobile_width', 'mobile_height', 'daily_price', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'row' => 'integer',
        'sort_order' => 'integer',
        'desktop_width' => 'integer',
        'desktop_height' => 'integer',
        'mobile_width' => 'integer',
        'mobile_height' => 'integer',
        'daily_price' => 'decimal:2',
    ];

    public function campaigns()
    {
        return $this->hasMany(AdCampaign::class);
    }

    public function orders()
    {
        return $this->hasMany(AdOrder::class);
    }

    public function desktopDimensions(): string
    {
        return "{$this->desktop_width}×{$this->desktop_height}";
    }

    public function mobileDimensions(): string
    {
        return "{$this->mobile_width}×{$this->mobile_height}";
    }
}
