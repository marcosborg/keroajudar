<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RaffleRule extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'raffle_rules';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'amount',
        'numbers',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'numbers' => 'integer',
        'active' => 'boolean',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
