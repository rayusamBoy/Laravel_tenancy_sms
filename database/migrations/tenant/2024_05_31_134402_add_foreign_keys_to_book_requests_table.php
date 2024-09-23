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
        Schema::table('book_requests', function (Blueprint $table) {
            $table->foreign(['book_id'], 'book_requests_ibfk_1')->references(['id'])->on('books')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'book_requests_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_requests', function (Blueprint $table) {
            $table->dropForeign('book_requests_ibfk_1');
            $table->dropForeign('book_requests_ibfk_2');
        });
    }
};
