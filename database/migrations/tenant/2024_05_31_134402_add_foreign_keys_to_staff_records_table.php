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
        Schema::table('staff_records', function (Blueprint $table) {
            $table->foreign(['user_id'], 'staff_records_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_records', function (Blueprint $table) {
            $table->dropForeign('staff_records_ibfk_1');
        });
    }
};
