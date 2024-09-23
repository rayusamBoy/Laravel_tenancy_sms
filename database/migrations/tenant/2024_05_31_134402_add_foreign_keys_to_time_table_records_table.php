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
        Schema::table('time_table_records', function (Blueprint $table) {
            $table->foreign(['my_class_id'], 'time_table_records_ibfk_1')->references(['id'])->on('my_classes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['section_id'], 'time_table_records_ibfk_2')->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['exam_id'], 'time_table_records_ibfk_3')->references(['id'])->on('exams')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_table_records', function (Blueprint $table) {
            $table->dropForeign('time_table_records_ibfk_1');
            $table->dropForeign('time_table_records_ibfk_2');
            $table->dropForeign('time_table_records_ibfk_3');
        });
    }
};
