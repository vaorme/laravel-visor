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
        Schema::table('orders', function (Blueprint $table){
            $table->text('order_id')->nullable()->after('id');
            $table->text('name')->nullable()->after('order_id');
            $table->text('email')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('name');
            $table->dropColumn('email');
        });
    }
};
