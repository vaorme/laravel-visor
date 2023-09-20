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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('coins')->nullable()->change();
            $table->text('image')->nullable()->after('name');
            $table->integer('days_without_ads')->nullable()->after('coins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('coins')->nullable()->change();
            $table->dropColumn('image');
            $table->dropColumn('days_without_ads');
        });
    }
};
