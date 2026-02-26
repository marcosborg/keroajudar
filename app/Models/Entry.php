<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'entries';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const CONTACT_VIA_RADIO = [
        'correio' => 'correio',
        'email'   => 'email',
        'sms'     => 'sms',
    ];

    protected $fillable = [
        'beneficiary_id',
        'raffle_game_id',
        'raffle_code',
        'has_raffle_numbers',
        'email',
        'first_name',
        'last_name',
        'phone',
        'amount',
        'commission_percent',
        'commission_amount',
        'beneficiary_amount',
        'is_company',
        'nif',
        'nipc',
        'address',
        'postal_code',
        'city',
        'country_id',
        'consent_privacy',
        'contact_via',
        'source_page',
        'client_ip',
        'user_agent',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'beneficiary_amount' => 'decimal:2',
        'has_raffle_numbers' => 'boolean',
        'is_company' => 'boolean',
        'consent_privacy' => 'boolean',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }

    public function raffleGame()
    {
        return $this->belongsTo(RaffleGame::class, 'raffle_game_id');
    }

    public function raffleNumbers()
    {
        return $this->hasMany(RaffleNumber::class, 'entry_id')->orderBy('number');
    }
}
