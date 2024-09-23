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
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 40);
            $table->unsignedInteger('class_type_id')->nullable()->index('grades_class_type_id_foreign');
            $table->tinyInteger('mark_from');
            $table->tinyInteger('mark_to');
            $table->integer('point');
            $table->decimal('credit', 8, 1);
            $table->string('remark', 40)->nullable();
            $table->timestamps();

            $table->unique(['name', 'class_type_id', 'remark']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
