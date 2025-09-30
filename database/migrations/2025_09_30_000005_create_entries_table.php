<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('raffle_code');
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->decimal('amount', 15, 2);
            $table->boolean('is_company')->default(0)->nullable();
            $table->string('nif')->nullable();
            $table->string('nipc')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city');
            $table->boolean('consent_privacy')->default(0);
            $table->string('contact_via')->nullable();
            $table->string('source_page')->nullable();
            $table->string('client_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
