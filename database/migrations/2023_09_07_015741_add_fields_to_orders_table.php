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
            $table->dropForeign(['order_status_id']);
            $table->dropColumn('order_status_id');
            $table->text('order_id')->nullable()->after('id');
            $table->text('name')->nullable()->after('order_id');
            $table->text('email')->nullable()->after('name');
            $table->text('status')->nullable()->after('email');
            $table->text('response')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_status_id')->nullable();
            $table->foreign('order_status_id')->references('id')->on('order_status');
            $table->dropColumn('order_id');
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('status');
            $table->dropColumn('response');
        });
    }
};
