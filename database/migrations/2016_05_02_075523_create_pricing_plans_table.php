<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('pricing_plans', function (Blueprint $table) {
           $table->increments('id');
           $table->string('name',255);
           $table->integer('billing_frequency_length')->default(1);
           $table->string('billing_frequency_period',40)->default('month');
           $table->string('currency',3);
           $table->decimal('price',15,2);
           $table->string('description',255)->nullable();
           $table->integer('display_order')->nullable();
           $table->softDeletes();
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('pricing_plans');
    }
}
