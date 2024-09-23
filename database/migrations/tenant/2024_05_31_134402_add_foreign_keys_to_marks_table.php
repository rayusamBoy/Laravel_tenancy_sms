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
        Schema::table('marks', function (Blueprint $table) {
            $table->foreign(['exam_id'], 'marks_ibfk_1')->references(['id'])->on('exams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['grade_id'], 'marks_ibfk_2')->references(['id'])->on('grades')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['my_class_id'], 'marks_ibfk_3')->references(['id'])->on('my_classes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['section_id'], 'marks_ibfk_4')->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['subject_id'], 'marks_ibfk_5')->references(['id'])->on('subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['student_id'], 'marks_ibfk_6')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropForeign('marks_ibfk_1');
            $table->dropForeign('marks_ibfk_2');
            $table->dropForeign('marks_ibfk_3');
            $table->dropForeign('marks_ibfk_4');
            $table->dropForeign('marks_ibfk_5');
            $table->dropForeign('marks_ibfk_6');
        });
    }
};
