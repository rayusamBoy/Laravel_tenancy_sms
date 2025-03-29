<?php

namespace Coderflex\LaravelTicket\Database\Factories;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreignId('user_id');
            $table->string('subject');
            $table->string('department')->nullable();
            $table->string('priority')->default('low');
            $table->string('status')->default('open');
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->foreignId('category_id');
            $table->string('labels_ids')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
