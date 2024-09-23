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
        Schema::table('exam_number_formats', function (Blueprint $table) {
            $table->foreign(['exam_id'], 'exam_number_formats_ibfk_1')->references(['id'])->on('exams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['my_class_id'], 'exam_number_formats_ibfk_2')->references(['id'])->on('my_classes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_number_formats', function (Blueprint $table) {
            $table->dropForeign('exam_number_formats_ibfk_1');
            $table->dropForeign('exam_number_formats_ibfk_2');
        });
    }
};
