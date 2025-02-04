<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (in_array(DB::getDriverName(), ['mysql', 'pgsql'])) {
                // If the database is MySQL or PostgreSQL, use the JSON type
                $table->json('hidden_alert_ids')->nullable();
            } else {
                // For other database drivers, use TEXT as fallback
                $table->text('hidden_alert_ids')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('hidden_alert_ids');
        });
    }
};
