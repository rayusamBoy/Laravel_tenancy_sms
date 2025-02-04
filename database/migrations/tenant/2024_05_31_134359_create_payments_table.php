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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->integer('amount');
            $table->string('ref_no', 100)->unique();
            $table->string('method', 100)->default('cash');
            $table->unsignedInteger('my_class_id')->nullable()->index('payments_my_class_id_foreign');
            $table->string('description')->nullable();
            $table->string('year');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
