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
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedInteger('my_class_id')->index('sections_my_class_id_foreign');
            $table->unsignedBigInteger('teacher_id')->nullable()->index('sections_teacher_id_foreign');
            $table->tinyInteger('active')->default(0);
            $table->timestamps();

            $table->unique(['name', 'my_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
