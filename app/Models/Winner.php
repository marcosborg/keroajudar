<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Winner extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'winners';

    protected $dates = [
        'draw_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'entry_id',
        'prize_id',
        'draw_date',
        'notes',
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

    public function prize()
    {
        return $this->belongsTo(Prize::class, 'prize_id');
    }

    public function getDrawDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDrawDateAttribute($value)
    {
        $this->attributes['draw_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}
