<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaffleNumber extends Model
{
    use HasFactory;

    public $table = 'raffle_numbers';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'entry_id',
        'raffle_game_id',
        'number',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'number' => 'integer',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }

    public function raffleGame()
    {
        return $this->belongsTo(RaffleGame::class, 'raffle_game_id');
    }
}
