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
        Schema::create('lgas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('nationality_id')->index('nationality_id');
            $table->unsignedBigInteger('state_id')->index('state_id');
            $table->string('name', 100);
            $table->string('country_code', 3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgas');
    }
};
