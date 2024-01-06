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
        Schema::table('chapters', function (Blueprint $table) {
            $table->string('type', 240)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::table('chapters', function (Blueprint $table) {
            $table->enum('type', ['manga', 'novel'])->change();
        });
    }
};
