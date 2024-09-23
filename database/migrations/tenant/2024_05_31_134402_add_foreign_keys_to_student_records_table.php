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
        Schema::table('student_records', function (Blueprint $table) {
            $table->foreign(['dorm_id'], 'student_records_ibfk_1')->references(['id'])->on('dorms')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['my_class_id'], 'student_records_ibfk_2')->references(['id'])->on('my_classes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['my_parent_id'], 'student_records_ibfk_3')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['section_id'], 'student_records_ibfk_4')->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['user_id'], 'student_records_ibfk_5')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_records', function (Blueprint $table) {
            $table->dropForeign('student_records_ibfk_1');
            $table->dropForeign('student_records_ibfk_2');
            $table->dropForeign('student_records_ibfk_3');
            $table->dropForeign('student_records_ibfk_4');
            $table->dropForeign('student_records_ibfk_5');
        });
    }
};
