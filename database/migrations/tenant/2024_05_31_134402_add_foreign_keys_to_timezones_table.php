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
        Schema::table('timezones', function (Blueprint $table) {
            $table->foreign(['nationality_id'], 'timezones_ibfk_1')->references(['id'])->on('nationalities')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timezones', function (Blueprint $table) {
            $table->dropForeign('timezones_ibfk_1');
        });
    }
};
