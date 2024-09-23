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
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('category_id')->index('category_id');
            $table->unsignedInteger('class_type_id')->index('exams_ibfk_1');
            $table->unsignedInteger('editable')->default(0)->index('exam_status_id');
            $table->unsignedInteger('locked')->default(0);
            $table->unsignedInteger('published')->default(0);
            $table->tinyInteger('term');
            $table->string('year', 40);
            $table->unsignedInteger('exam_denominator')->default(100);
            $table->unsignedInteger('cw_denominator')->nullable();
            $table->unsignedInteger('hw_denominator')->nullable();
            $table->unsignedInteger('tt_denominator')->nullable();
            $table->unsignedInteger('tdt_denominator')->nullable();
            $table->string('exam_student_position_by_value', 40);
            $table->string('ca_student_position_by_value', 40)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
