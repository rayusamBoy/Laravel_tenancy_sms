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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['bg_id'], 'users_ibfk_1')->references(['id'])->on('blood_groups')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['nal_id'], 'users_ibfk_2')->references(['id'])->on('nationalities')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['state_id'], 'users_ibfk_3')->references(['id'])->on('states')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['lga_id'], 'users_ibfk_4')->references(['id'])->on('lgas')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_ibfk_1');
            $table->dropForeign('users_ibfk_2');
            $table->dropForeign('users_ibfk_3');
            $table->dropForeign('users_ibfk_4');
        });
    }
};
