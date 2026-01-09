<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raffle_games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('prize_id')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('prize_id', 'raffle_game_prize_fk')
                ->references('id')
                ->on('prizes')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raffle_games');
    }
};
