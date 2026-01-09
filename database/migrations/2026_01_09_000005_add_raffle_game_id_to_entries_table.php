<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->unsignedBigInteger('raffle_game_id')->nullable()->after('beneficiary_id');
            $table->foreign('raffle_game_id', 'entry_raffle_game_fk')
                ->references('id')
                ->on('raffle_games')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropForeign('entry_raffle_game_fk');
            $table->dropColumn('raffle_game_id');
        });
    }
};
