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
        Schema::create('manga_has_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manga_id');
            $table->unsignedBigInteger('category_id');
            $table->foreign('manga_id')->references('id')->on('manga');
            $table->foreign('category_id')->references('id')->on('categories');
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
        Schema::dropIfExists('manga_has_category');
    }
};
