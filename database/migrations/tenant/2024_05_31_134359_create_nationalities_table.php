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
        Schema::create('nationalities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->tinyInteger('status')->default(1);
            $table->string('region', 100);
            $table->string('subregion', 100);
            $table->string('phone_code', 5);
            $table->string('iso2', 2);
            $table->string('iso3', 3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nationalities');
    }
};
