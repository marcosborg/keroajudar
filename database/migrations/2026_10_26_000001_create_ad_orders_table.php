<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_positions', function (Blueprint $table) {
            $table->decimal('daily_price', 10, 2)->nullable()->after('mobile_height');
        });

        Schema::create('ad_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('ad_position_id')->constrained('ad_positions')->restrictOnDelete();
            $table->foreignId('ad_campaign_id')->nullable()->constrained('ad_campaigns')->nullOnDelete();
            $table->string('payment_identifier', 64)->unique();
            $table->string('status')->default('payment_pending');
            $table->string('payment_method');
            $table->string('customer_type');
            $table->string('contact_name');
            $table->string('company_name')->nullable();
            $table->string('tax_number', 20);
            $table->string('email');
            $table->string('phone', 30);
            $table->string('address');
            $table->string('postal_code', 20);
            $table->string('city');
            $table->string('country')->default('Portugal');
            $table->string('campaign_title');
            $table->string('destination_url', 2048);
            $table->date('starts_at');
            $table->date('ends_at');
            $table->boolean('targets_all_categories')->default(true);
            $table->json('target_categories')->nullable();
            $table->boolean('targets_all_regions')->default(true);
            $table->json('target_regions')->nullable();
            $table->unsignedSmallInteger('billable_days');
            $table->decimal('daily_price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->boolean('consent_terms')->default(false);
            $table->text('failure_message')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['ad_position_id', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_orders');

        Schema::table('ad_positions', function (Blueprint $table) {
            $table->dropColumn('daily_price');
        });
    }
};
