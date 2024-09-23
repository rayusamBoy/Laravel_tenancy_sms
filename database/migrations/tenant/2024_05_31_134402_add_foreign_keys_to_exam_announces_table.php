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
        Schema::table('exam_announces', function (Blueprint $table) {
            $table->foreign(['exam_id'], 'exam_announces_ibfk_1')->references(['id'])->on('exams')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_announces', function (Blueprint $table) {
            $table->dropForeign('exam_announces_ibfk_1');
        });
    }
};
