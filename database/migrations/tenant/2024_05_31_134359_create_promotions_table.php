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
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->index('promotions_student_id_foreign');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('from_class')->index('promotions_from_class_foreign');
            $table->unsignedInteger('from_section')->index('promotions_from_section_foreign');
            $table->unsignedInteger('to_class')->index('promotions_to_class_foreign');
            $table->unsignedInteger('to_section')->index('promotions_to_section_foreign');
            $table->tinyInteger('grad');
            $table->string('from_session');
            $table->string('to_session');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
