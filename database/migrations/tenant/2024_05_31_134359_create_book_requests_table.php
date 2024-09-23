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
        Schema::create('book_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedInteger('book_id')->index('book_requests_book_id_foreign');
            $table->unsignedBigInteger('user_id')->index('book_requests_user_id_foreign');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('returned')->default('0');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_requests');
    }
};
