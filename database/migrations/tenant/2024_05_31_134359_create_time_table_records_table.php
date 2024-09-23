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
        Schema::create('time_table_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('section_id')->nullable()->index('section_id');
            $table->unsignedInteger('exam_id')->nullable()->index('time_table_records_exam_id_foreign');
            $table->string('year', 100);
            $table->timestamps();

            $table->unique(['my_class_id', 'exam_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_table_records');
    }
};
