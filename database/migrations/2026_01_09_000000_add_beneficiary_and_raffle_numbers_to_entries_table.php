<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->unsignedBigInteger('beneficiary_id')->nullable()->after('id');

            $table->foreign('beneficiary_id', 'entry_beneficiary_fk')
                ->references('id')
                ->on('beneficiaries')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropForeign('entry_beneficiary_fk');
            $table->dropColumn(['beneficiary_id']);
        });
    }
};
