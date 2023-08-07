<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_ads', function (Blueprint $table){
            $table->id();
            $table->text('ads_1')->nullable();
            $table->text('ads_2')->nullable();
            $table->text('ads_3')->nullable();
            $table->text('ads_4')->nullable();
            $table->text('ads_5')->nullable();
            $table->text('ads_6')->nullable();
            $table->text('ads_7')->nullable();
            $table->text('ads_8')->nullable();
            $table->text('ads_9')->nullable();
            $table->text('ads_10')->nullable();
            $table->text('ads_11')->nullable();
            $table->text('ads_12')->nullable();
            $table->text('ads_13')->nullable();
            $table->text('ads_14')->nullable();
            $table->text('ads_15')->nullable();
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
        Schema::dropIfExists('settings_ads');
    }
};
