<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariesTable extends Migration
{
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('beneficiary_category_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('beneficiary_category_id', 'category_fk_beneficiary')
                ->references('id')
                ->on('beneficiary_categories')
                ->onDelete('cascade');
        });
    }
}
