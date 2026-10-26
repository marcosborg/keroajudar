<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiary_referral_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries')->cascadeOnDelete();
            $table->date('metric_date');
            $table->unsignedBigInteger('visits')->default(0);
            $table->timestamps();
            $table->unique(['beneficiary_id', 'metric_date'], 'beneficiary_referral_daily_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiary_referral_metrics');
    }
};
