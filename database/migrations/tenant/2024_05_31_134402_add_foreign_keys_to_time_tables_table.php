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
        Schema::table('time_tables', function (Blueprint $table) {
            $table->foreign(['subject_id'], 'time_tables_ibfk_1')->references(['id'])->on('subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ts_id'], 'time_tables_ibfk_2')->references(['id'])->on('time_slots')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ttr_id'], 'time_tables_ibfk_3')->references(['id'])->on('time_table_records')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_tables', function (Blueprint $table) {
            $table->dropForeign('time_tables_ibfk_1');
            $table->dropForeign('time_tables_ibfk_2');
            $table->dropForeign('time_tables_ibfk_3');
        });
    }
};
