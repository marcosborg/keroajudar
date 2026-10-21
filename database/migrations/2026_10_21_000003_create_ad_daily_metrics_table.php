<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_daily_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_campaign_id')->constrained('ad_campaigns')->cascadeOnDelete();
            $table->foreignId('ad_position_id')->constrained('ad_positions')->restrictOnDelete();
            $table->date('metric_date');
            $table->string('category_code', 40)->default('all');
            $table->string('region_code', 40)->default('all');
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);
            $table->timestamps();
            $table->unique(
                ['ad_campaign_id', 'ad_position_id', 'metric_date', 'category_code', 'region_code'],
                'ad_daily_metric_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_daily_metrics');
    }
};
