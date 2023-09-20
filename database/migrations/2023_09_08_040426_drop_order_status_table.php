<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::dropIfExists('order_status');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('order_status', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('slug', 50)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }
};