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
        Schema::table('pins', function (Blueprint $table) {
            $table->foreign(['user_id'], 'pins_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['student_id'], 'pins_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pins', function (Blueprint $table) {
            $table->dropForeign('pins_ibfk_1');
            $table->dropForeign('pins_ibfk_2');
        });
    }
};
