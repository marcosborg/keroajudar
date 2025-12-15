<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyFieldsToBeneficiariesTable extends Migration
{
    public function up()
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('vat_number')->nullable()->after('name');
            $table->string('contact_email')->nullable()->after('vat_number');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->string('website')->nullable()->after('contact_phone');
            $table->string('address')->nullable()->after('website');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->longText('about')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn([
                'vat_number',
                'contact_email',
                'contact_phone',
                'website',
                'address',
                'city',
                'country',
                'about',
            ]);
        });
    }
}
