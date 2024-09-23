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
        Schema::table('sections', function (Blueprint $table) {
            $table->foreign(['my_class_id'], 'sections_ibfk_1')->references(['id'])->on('my_classes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['teacher_id'], 'sections_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign('sections_ibfk_1');
            $table->dropForeign('sections_ibfk_2');
        });
    }
};
