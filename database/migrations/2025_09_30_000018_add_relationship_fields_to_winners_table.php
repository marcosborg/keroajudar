<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToWinnersTable extends Migration
{
    public function up()
    {
        Schema::table('winners', function (Blueprint $table) {
            $table->unsignedBigInteger('entry_id')->nullable();
            $table->foreign('entry_id', 'entry_fk_10732186')->references('id')->on('entries');
            $table->unsignedBigInteger('prize_id')->nullable();
            $table->foreign('prize_id', 'prize_fk_10732187')->references('id')->on('prizes');
        });
    }
}
