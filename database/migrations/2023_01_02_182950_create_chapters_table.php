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
        Schema::create('chapters', function (Blueprint $table){
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->bigInteger('order');
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->text('images')->nullable();
            $table->text('price')->nullable();
            $table->text('content')->nullable();
            $table->unsignedBigInteger('manga_id');
            $table->enum('type', ['manga', 'novel']);
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
        Schema::dropIfExists('chapters');
    }
};
