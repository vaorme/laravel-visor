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
        Schema::create('manga', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->bigInteger('order')->unique()->default(1);
            $table->string('name', 60);
            $table->string('alternative_name', 60)->nullable();
            $table->text('featured_image')->nullable();
            $table->string('slug', 60);
            $table->text('description')->nullable();
            $table->enum('status', ['published', 'draft', 'private'])->default('published');
            $table->date('release_date')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('book_status_id')->nullable();
            $table->unsignedBigInteger('demography_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('manga_type');
            $table->foreign('book_status_id')->references('id')->on('manga_book_status');
            $table->foreign('demography_id')->references('id')->on('manga_demography');
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
