<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('commercial_certificate_code')->nullable()->after('vat_number');
            $table->string('iban')->nullable()->after('commercial_certificate_code');
            $table->string('postal_code')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn([
                'commercial_certificate_code',
                'iban',
                'postal_code',
            ]);
        });
    }
};

