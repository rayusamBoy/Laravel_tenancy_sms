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
        Schema::create('exam_number_formats', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('exam_id')->index('exam_id');
            $table->unsignedInteger('my_class_id')->index('exam_number_formats_ibfk_2');
            $table->string('format');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_number_formats');
    }
};
