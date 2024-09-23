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
        Schema::create('student_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('student_records_user_id_foreign');
            $table->string('ps_name');
            $table->string('ss_name')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('disability')->nullable();
            $table->string('food_taboos')->nullable();
            $table->string('chp')->nullable();
            $table->string('p_status', 155);
            $table->string('house_no')->nullable();
            $table->unsignedInteger('my_class_id')->index('student_records_my_class_id_foreign');
            $table->unsignedInteger('section_id')->index('student_records_section_id_foreign');
            $table->string('adm_no', 30)->nullable()->unique();
            $table->unsignedBigInteger('my_parent_id')->nullable()->index('student_records_my_parent_id_foreign');
            $table->unsignedInteger('dorm_id')->nullable()->index('student_records_dorm_id_foreign');
            $table->string('dorm_room_no')->nullable();
            $table->string('session');
            $table->tinyInteger('age')->nullable();
            $table->string('date_admitted')->nullable();
            $table->tinyInteger('grad')->default(0);
            $table->string('grad_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_records');
    }
};
