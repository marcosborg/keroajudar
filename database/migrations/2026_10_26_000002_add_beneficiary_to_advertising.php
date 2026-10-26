<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->foreignId('beneficiary_id')
                ->nullable()
                ->after('ad_position_id')
                ->constrained('beneficiaries')
                ->nullOnDelete();
        });

        Schema::table('ad_orders', function (Blueprint $table) {
            $table->foreignId('beneficiary_id')
                ->nullable()
                ->after('ad_position_id')
                ->constrained('beneficiaries')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ad_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('beneficiary_id');
        });

        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->dropConstrainedForeignId('beneficiary_id');
        });
    }
};
