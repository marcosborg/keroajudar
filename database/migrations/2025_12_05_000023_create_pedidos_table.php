<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identificador')->unique();
            $table->string('status')->default('pendente');
            $table->string('entidade')->nullable();
            $table->string('referencia')->nullable();
            $table->string('mp')->nullable();
            $table->string('transacao')->nullable();
            $table->decimal('valor', 10, 2)->default(0);
            $table->json('beneficiarios')->nullable();
            $table->timestamp('data_pagamento')->nullable();
            $table->json('callback_payload')->nullable();
            $table->json('provider_payload')->nullable();
            $table->timestamps();
        });
    }
}
