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
        Schema::table('payment_records', function (Blueprint $table) {
            $table->foreign(['payment_id'], 'payment_records_ibfk_1')->references(['id'])->on('payments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['student_id'], 'payment_records_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropForeign('payment_records_ibfk_1');
            $table->dropForeign('payment_records_ibfk_2');
        });
    }
};
