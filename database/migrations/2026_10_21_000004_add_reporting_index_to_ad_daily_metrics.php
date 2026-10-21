<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_daily_metrics', function (Blueprint $table) {
            $table->index(['metric_date', 'ad_campaign_id', 'ad_position_id'], 'ad_daily_metric_reporting_idx');
        });
    }

    public function down(): void
    {
        Schema::table('ad_daily_metrics', function (Blueprint $table) {
            $table->dropIndex('ad_daily_metric_reporting_idx');
        });
    }
};
