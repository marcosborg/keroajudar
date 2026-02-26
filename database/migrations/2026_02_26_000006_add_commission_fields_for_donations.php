<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->decimal('default_commission_percent', 5, 2)
                ->default(5)
                ->after('postal_code');
        });

        Schema::table('raffle_games', function (Blueprint $table) {
            $table->decimal('commission_percent', 5, 2)
                ->default(0)
                ->after('description');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('has_raffle_numbers')
                ->default(false)
                ->after('raffle_code');
            $table->decimal('commission_percent', 5, 2)
                ->default(0)
                ->after('amount');
            $table->decimal('commission_amount', 15, 2)
                ->default(0)
                ->after('commission_percent');
            $table->decimal('beneficiary_amount', 15, 2)
                ->default(0)
                ->after('commission_amount');
        });
    }

    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn([
                'has_raffle_numbers',
                'commission_percent',
                'commission_amount',
                'beneficiary_amount',
            ]);
        });

        Schema::table('raffle_games', function (Blueprint $table) {
            $table->dropColumn('commission_percent');
        });

        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn('default_commission_percent');
        });
    }
};
