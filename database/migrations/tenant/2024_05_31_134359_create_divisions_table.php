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
        Schema::create('divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 40);
            $table->tinyInteger('points_from');
            $table->tinyInteger('points_to');
            $table->string('remark', 40)->nullable();
            $table->unsignedInteger('class_type_id')->nullable()->index('divisions_ibfk_1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
