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
        Schema::create('settings', function (Blueprint $table){
            $table->id();
            $table->string('title', 60)->nullable();
            $table->text('logo')->nullable();
            $table->text('favicon')->nullable();
            $table->string('email', 60)->nullable();
            $table->string('chat_id', 50)->nullable();
            $table->string('global_message', 240)->nullable();
            $table->boolean('maintenance')->default(false);
            $table->boolean('allow_new_users')->default(true);
            $table->string('disk')->default('public');
            $table->text('insert_head')->nullable();
            $table->text('insert_body')->nullable();
            $table->text('insert_footer')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
