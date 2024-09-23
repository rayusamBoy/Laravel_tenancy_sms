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
        Schema::create('pins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 40)->unique();
            $table->string('used')->default('0');
            $table->string('times_used')->default('0');
            $table->unsignedBigInteger('user_id')->nullable()->index('pins_user_id_foreign');
            $table->unsignedBigInteger('student_id')->nullable()->index('pins_student_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pins');
    }
};
