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
        Schema::create('time_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ttr_id');
            $table->unsignedInteger('ts_id')->index('time_tables_ts_id_foreign');
            $table->unsignedInteger('subject_id')->nullable()->index('time_tables_subject_id_foreign');
            $table->string('exam_date', 50)->nullable();
            $table->string('timestamp_from', 100);
            $table->string('timestamp_to', 100);
            $table->string('day', 50)->nullable();
            $table->tinyInteger('day_num')->nullable();
            $table->timestamps();

            $table->unique(['ttr_id', 'ts_id', 'day']);
            $table->unique(['ttr_id', 'ts_id', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_tables');
    }
};
