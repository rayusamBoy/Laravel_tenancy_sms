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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ttr_id')->index('time_slots_ttr_id_foreign');
            $table->tinyInteger('hour_from');
            $table->string('min_from', 2);
            $table->string('meridian_from', 2);
            $table->tinyInteger('hour_to');
            $table->string('min_to', 2);
            $table->string('meridian_to', 2);
            $table->string('time_from', 100);
            $table->string('time_to', 100);
            $table->string('timestamp_from', 50);
            $table->string('timestamp_to', 50);
            $table->string('full', 100);
            $table->timestamps();

            $table->unique(['timestamp_from', 'timestamp_to', 'ttr_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
