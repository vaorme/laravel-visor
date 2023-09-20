<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_buy_days', function (Blueprint $table) {
            $table->id();
            $table->string('username', 16);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('days_without_ads')->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_buy_days');
    }
};
