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
        Schema::table('exam_records', function (Blueprint $table) {
            $table->foreign(['division_id'], 'exam_records_ibfk_1')->references(['id'])->on('divisions')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['exam_id'], 'exam_records_ibfk_2')->references(['id'])->on('exams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['grade_id'], 'exam_records_ibfk_3')->references(['id'])->on('grades')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['my_class_id'], 'exam_records_ibfk_4')->references(['id'])->on('my_classes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['section_id'], 'exam_records_ibfk_5')->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['student_id'], 'exam_records_ibfk_6')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_records', function (Blueprint $table) {
            $table->dropForeign('exam_records_ibfk_1');
            $table->dropForeign('exam_records_ibfk_2');
            $table->dropForeign('exam_records_ibfk_3');
            $table->dropForeign('exam_records_ibfk_4');
            $table->dropForeign('exam_records_ibfk_5');
            $table->dropForeign('exam_records_ibfk_6');
        });
    }
};
