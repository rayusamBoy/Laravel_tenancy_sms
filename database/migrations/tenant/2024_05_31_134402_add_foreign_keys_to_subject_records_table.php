<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subject_records', function (Blueprint $table) {
            $table->foreign(['section_id'], 'subject_records_ibfk_1')->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['teacher_id'], 'subject_records_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['subject_id'], 'subject_records_ibfk_3')->references(['id'])->on('subjects')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_records', function (Blueprint $table) {
            $table->dropForeign('subject_records_ibfk_1');
            $table->dropForeign('subject_records_ibfk_2');
            $table->dropForeign('subject_records_ibfk_3');
        });
    }
};
