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
        Schema::create('manga_rating', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('rating');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('manga_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('manga_id')->references('id')->on('manga');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manga');
    }
};
