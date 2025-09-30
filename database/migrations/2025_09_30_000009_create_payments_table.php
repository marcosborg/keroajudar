<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provider')->nullable();
            $table->string('transaction')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->nullable();
            $table->string('method')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->longText('raw_response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
