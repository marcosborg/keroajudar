<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raffle_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('entry_id');
            $table->unsignedInteger('number')->unique();
            $table->timestamps();

            $table->foreign('entry_id', 'raffle_number_entry_fk')
                ->references('id')
                ->on('entries')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raffle_numbers');
    }
};
