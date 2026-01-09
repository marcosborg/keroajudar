<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raffle_rules', function (Blueprint $table) {
            $table->unsignedBigInteger('raffle_game_id')->nullable()->after('id');
            $table->foreign('raffle_game_id', 'raffle_rule_game_fk')
                ->references('id')
                ->on('raffle_games')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('raffle_rules', function (Blueprint $table) {
            $table->dropForeign('raffle_rule_game_fk');
            $table->dropColumn('raffle_game_id');
        });
    }
};
