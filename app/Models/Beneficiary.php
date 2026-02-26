<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Notifications\BeneficiaryResetPassword;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Beneficiary extends Authenticatable implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory, Notifiable;

    public $table = 'beneficiaries';

    protected $appends = [
        'photo',
        'cover_url',
        'logo_square',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'beneficiary_category_id',
        'name',
        'description',
        'about',
        'vat_number',
        'commercial_certificate_code',
        'iban',
        'contact_email',
        'contact_phone',
        'email',
        'password',
        'website',
        'address',
        'postal_code',
        'default_commission_percent',
        'city',
        'country',
        'active',
        'approved_at',
        'last_login_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'last_login_at' => 'datetime',
        'active' => 'boolean',
        'default_commission_percent' => 'decimal:2',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setPasswordAttribute($value)
    {
        if (!$value) {
            return;
        }
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function sendPasswordResetNotification($token)
    {
        $url = route('beneficiaries.password.reset', ['token' => $token, 'email' => $this->email]);
        $this->notify(new BeneficiaryResetPassword($token, $url));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 480, 320);

        $this->addMediaConversion('logo_thumb')->fit('crop', 300, 300)->performOnCollections('logo');
        $this->addMediaConversion('logo_preview')->fit('crop', 300, 300)->performOnCollections('logo');
    }

    public function category()
    {
        return $this->belongsTo(BeneficiaryCategory::class, 'beneficiary_category_id');
    }

    public function getPhotoAttribute()
    {
        $file = $this->getMedia('photo')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getLogoSquareAttribute()
    {
        $file = $this->getMedia('logo')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('logo_thumb');
            $file->preview   = $file->getUrl('logo_preview');
        }

        return $file;
    }

    public function getCoverUrlAttribute()
    {
        $media = $this->getMedia('photo')->last();

        return $media ? $media->getFullUrl('preview') : asset('images/banner-ajuda.png');
    }
}
