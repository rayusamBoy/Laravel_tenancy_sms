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
        Schema::create('subject_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedInteger('section_id')->nullable()->index('subject_records_ibfk_1');
            $table->unsignedBigInteger('teacher_id')->nullable()->index('subject_records_ibfk_3');
            $table->longText('students_ids')->nullable();
            $table->timestamps();

            $table->unique(['subject_id', 'section_id', 'teacher_id'], 'subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_records');
    }
};
