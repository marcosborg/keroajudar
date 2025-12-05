<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'payments';

    protected $dates = [
        'paid_at',
        'cancelled_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const METHOD_RADIO = [
        'cartao'        => 'cartão',
        'MBWay'         => 'MBWay',
        'transferencia' => 'transferência',
    ];

    public const STATUS_RADIO = [
        'pending'   => 'pending',
        'confirmed' => 'confirmed',
        'cancelled' => 'cancelled',
        'failed'    => 'failed',
        'refunded'  => 'refunded',
    ];

    protected $fillable = [
        'entry_id',
        'provider',
        'transaction',
        'amount',
        'currency',
        'status',
        'method',
        'paid_at',
        'cancelled_at',
        'raw_response',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }

    public function getPaidAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setPaidAtAttribute($value)
    {
        $this->attributes['paid_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getCancelledAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setCancelledAtAttribute($value)
    {
        $this->attributes['cancelled_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}
