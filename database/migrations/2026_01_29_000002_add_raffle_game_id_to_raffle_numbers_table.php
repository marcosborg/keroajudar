<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raffle_numbers', function (Blueprint $table) {
            $table->unsignedBigInteger('raffle_game_id')->nullable()->after('entry_id');
        });

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('UPDATE raffle_numbers rn JOIN entries e ON e.id = rn.entry_id SET rn.raffle_game_id = e.raffle_game_id');
        } elseif ($driver === 'pgsql') {
            DB::statement('UPDATE raffle_numbers rn SET raffle_game_id = e.raffle_game_id FROM entries e WHERE e.id = rn.entry_id');
        } else {
            DB::statement('UPDATE raffle_numbers SET raffle_game_id = (SELECT raffle_game_id FROM entries WHERE entries.id = raffle_numbers.entry_id)');
        }

        Schema::table('raffle_numbers', function (Blueprint $table) {
            $table->dropUnique(['number']);
            $table->unique(['raffle_game_id', 'number']);

            $table->foreign('raffle_game_id', 'raffle_number_game_fk')
                ->references('id')
                ->on('raffle_games')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('raffle_numbers', function (Blueprint $table) {
            $table->dropForeign('raffle_number_game_fk');
            $table->dropUnique(['raffle_game_id', 'number']);
            $table->unique(['number']);
            $table->dropColumn('raffle_game_id');
        });
    }
};

