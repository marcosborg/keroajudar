<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    public $table = 'pedidos';

    protected $fillable = [
        'identificador',
        'status',
        'entidade',
        'referencia',
        'mp',
        'transacao',
        'valor',
        'beneficiarios',
        'data_pagamento',
        'callback_payload',
        'provider_payload',
    ];

    protected $casts = [
        'beneficiarios'    => 'array',
        'callback_payload' => 'array',
        'provider_payload' => 'array',
        'data_pagamento'   => 'datetime',
    ];
}
