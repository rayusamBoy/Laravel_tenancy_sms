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
        Schema::table('lgas', function (Blueprint $table) {
            $table->foreign(['nationality_id'], 'lgas_ibfk_1')->references(['id'])->on('nationalities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['state_id'], 'lgas_ibfk_2')->references(['id'])->on('states')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lgas', function (Blueprint $table) {
            $table->dropForeign('lgas_ibfk_1');
            $table->dropForeign('lgas_ibfk_2');
        });
    }
};
