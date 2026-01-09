<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raffle_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount', 15, 2);
            $table->unsignedInteger('numbers');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raffle_rules');
    }
};
