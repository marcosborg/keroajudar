<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->after('contact_email');
            $table->string('password')->nullable()->after('email');
            $table->rememberToken();
            $table->timestamp('approved_at')->nullable()->after('active');
            $table->timestamp('last_login_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn(['email', 'password', 'remember_token', 'approved_at', 'last_login_at']);
        });
    }
};
