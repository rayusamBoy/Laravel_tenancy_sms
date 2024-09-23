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
        Schema::create('marks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->index('marks_student_id_foreign');
            $table->unsignedInteger('subject_id')->index('marks_subject_id_foreign');
            $table->unsignedInteger('my_class_id')->index('marks_my_class_id_foreign');
            $table->unsignedInteger('section_id')->index('marks_section_id_foreign');
            $table->unsignedInteger('exam_id')->index('marks_exam_id_foreign');
            $table->integer('exm')->nullable();
            $table->integer('tex1')->nullable();
            $table->integer('tex2')->nullable();
            $table->integer('tex3')->nullable();
            $table->unsignedTinyInteger('sub_pos')->nullable();
            $table->integer('cum')->nullable();
            $table->string('cum_ave')->nullable();
            $table->unsignedInteger('grade_id')->nullable()->index('marks_grade_id_foreign');
            $table->string('year');
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'my_class_id', 'section_id', 'exam_id'], 'student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
