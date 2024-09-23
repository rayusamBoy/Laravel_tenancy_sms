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
        Schema::table('assessment_records', function (Blueprint $table) {
            $table->foreign(['assessment_id'], 'assessment_records_ibfk_1')->references(['id'])->on('assessments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['exam_id'], 'assessment_records_ibfk_2')->references(['id'])->on('exams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['grade_id'], 'assessment_records_ibfk_3')->references(['id'])->on('grades')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['mark_id'], 'assessment_records_ibfk_4')->references(['id'])->on('marks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['my_class_id'], 'assessment_records_ibfk_5')->references(['id'])->on('my_classes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['section_id'], 'assessment_records_ibfk_6')->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['subject_id'], 'assessment_records_ibfk_7')->references(['id'])->on('subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['student_id'], 'assessment_records_ibfk_8')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_records', function (Blueprint $table) {
            $table->dropForeign('assessment_records_ibfk_1');
            $table->dropForeign('assessment_records_ibfk_2');
            $table->dropForeign('assessment_records_ibfk_3');
            $table->dropForeign('assessment_records_ibfk_4');
            $table->dropForeign('assessment_records_ibfk_5');
            $table->dropForeign('assessment_records_ibfk_6');
            $table->dropForeign('assessment_records_ibfk_7');
            $table->dropForeign('assessment_records_ibfk_8');
        });
    }
};
