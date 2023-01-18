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
        Schema::create('profiles', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('name', 60)->nullable();
            $table->string('avatar', 240)->nullable();
            $table->string('cover', 240)->nullable();
            $table->text('message')->nullable();
            $table->bigInteger('coins')->nullable();
            $table->string('link_site', 140)->nullable();
            $table->string('link_twitter', 100)->nullable();
            $table->string('link_facebook', 100)->nullable();
            $table->string('link_youtube', 100)->nullable();
            $table->string('link_custom', 140)->nullable();
            $table->string('country', 2)->nullable();
            $table->tinyInteger('public_profile')->default(1);
            $table->date('date_of_birth')->nullable();
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('profile');
    }
};
