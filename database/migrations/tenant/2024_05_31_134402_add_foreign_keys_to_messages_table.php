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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign(['deleted_by'], 'messages_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['thread_id'], 'messages_ibfk_2')->references(['id'])->on('threads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'messages_ibfk_3')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_ibfk_1');
            $table->dropForeign('messages_ibfk_2');
            $table->dropForeign('messages_ibfk_3');
        });
    }
};
