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
        Schema::create('exam_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_id')->index('exam_records_exam_id_foreign');
            $table->unsignedBigInteger('student_id')->index('exam_records_student_id_foreign');
            $table->unsignedInteger('my_class_id')->index('exam_records_my_class_id_foreign');
            $table->unsignedInteger('section_id')->index('exam_records_section_id_foreign');
            $table->integer('total')->nullable();
            $table->decimal('ave')->nullable();
            $table->decimal('class_ave')->nullable();
            $table->integer('pos')->nullable();
            $table->integer('class_pos')->nullable();
            $table->unsignedInteger('grade_id')->nullable()->index('grade_id');
            $table->unsignedInteger('points')->nullable();
            $table->decimal('gpa', 8, 1)->nullable();
            $table->unsignedInteger('division_id')->nullable()->index('exam_records_ibfk_2');
            $table->string('af')->nullable();
            $table->string('ps')->nullable();
            $table->string('p_comment')->nullable();
            $table->string('t_comment')->nullable();
            $table->string('year');
            $table->timestamps();

            $table->unique(['exam_id', 'student_id', 'my_class_id', 'section_id'], 'exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_records');
    }
};
