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
        Schema::create('manga_has_tags', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('manga_id');
            $table->unsignedBigInteger('tag_id');
            $table->primary(['manga_id', 'tag_id']);
            $table->foreign('manga_id')->references('id')->on('manga')->cascadeOnDelete();
            $table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete();
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
        Schema::dropIfExists('manga_has_tags');
    }
};
