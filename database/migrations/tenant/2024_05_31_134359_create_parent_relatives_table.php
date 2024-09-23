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
        Schema::create('parent_relatives', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index('parent_relatives_ibfk_1');
            $table->string('name');
            $table->string('phone3');
            $table->string('phone4')->nullable();
            $table->string('relation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_relatives');
    }
};
