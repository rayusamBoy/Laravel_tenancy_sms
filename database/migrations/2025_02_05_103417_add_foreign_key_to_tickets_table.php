<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('assigned_to', "tickets_ibfk_1")->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tenant_id', "tickets_ibfk_2")->references(['id'])->on('tenants')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign("tickets_ibfk_1");
            $table->dropForeign("tickets_ibfk_2");
        });
    }
};
