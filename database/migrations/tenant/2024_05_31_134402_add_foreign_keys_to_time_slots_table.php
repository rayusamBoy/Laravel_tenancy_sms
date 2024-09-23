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
        Schema::table('time_slots', function (Blueprint $table) {
            $table->foreign(['ttr_id'], 'time_slots_ibfk_1')->references(['id'])->on('time_table_records')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropForeign('time_slots_ibfk_1');
        });
    }
};
