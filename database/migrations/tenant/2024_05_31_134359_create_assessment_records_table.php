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
        Schema::create('assessment_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->index('marks_student_id_foreign');
            $table->unsignedInteger('subject_id')->index('marks_subject_id_foreign');
            $table->unsignedInteger('my_class_id')->index('marks_my_class_id_foreign');
            $table->unsignedInteger('section_id')->index('marks_section_id_foreign');
            $table->unsignedInteger('exam_id')->index('marks_exam_id_foreign');
            $table->unsignedBigInteger('mark_id')->index('mark_id');
            $table->unsignedInteger('assessment_id')->index('assesment_records_ibfk_7');
            $table->integer('cw1')->nullable();
            $table->integer('cw2')->nullable();
            $table->integer('cw3')->nullable();
            $table->integer('cw4')->nullable();
            $table->integer('cw5')->nullable();
            $table->integer('cw6')->nullable();
            $table->integer('cw7')->nullable();
            $table->integer('cw8')->nullable();
            $table->integer('cw9')->nullable();
            $table->integer('cw10')->nullable();
            $table->integer('hw1')->nullable();
            $table->integer('hw2')->nullable();
            $table->integer('hw3')->nullable();
            $table->integer('hw4')->nullable();
            $table->integer('hw5')->nullable();
            $table->integer('tt1')->nullable();
            $table->integer('tt2')->nullable();
            $table->integer('tt3')->nullable();
            $table->integer('tca')->nullable();
            $table->integer('exm')->nullable();
            $table->integer('tex1')->nullable();
            $table->integer('tex2')->nullable();
            $table->integer('tex3')->nullable();
            $table->integer('sub_pos')->nullable();
            $table->integer('pos')->nullable();
            $table->integer('total')->nullable();
            $table->decimal('ave')->nullable();
            $table->decimal('class_ave')->nullable();
            $table->unsignedInteger('grade_id')->nullable()->index('marks_grade_id_foreign');
            $table->string('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_records');
    }
};
