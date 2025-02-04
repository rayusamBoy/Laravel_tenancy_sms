<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedInteger('blocked')->default(0);
            $table->string('religion', 13);
            $table->string('email', 100)->nullable()->unique();
            $table->string('code', 100)->unique();
            $table->string('username', 100)->nullable()->unique();
            $table->string('user_type');
            $table->string('work')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('photo')->default('global_assets/images/user.png');
            $table->string('primary_id')->nullable();
            $table->string('secondary_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->unsignedInteger('bg_id')->nullable()->index('users_bg_id_foreign');
            $table->unsignedBigInteger('state_id')->nullable()->index('users_state_id_foreign');
            $table->unsignedBigInteger('lga_id')->nullable()->index('users_lga_id_foreign');
            $table->unsignedBigInteger('nal_id')->nullable()->index('users_nal_id_foreign');
            $table->string('address')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedTinyInteger('sidebar_minimized')->default(0);
            $table->unsignedTinyInteger('allow_system_sounds')->default(0);
            $table->text('is_notifiable')->nullable();
            $table->unsignedTinyInteger('show_charts')->default(1);
            $table->timestamp('password_updated_at')->nullable();
            $table->string('twofa_secret_code', 100)->nullable();
            $table->string('twofa_recovery_codes', 500)->nullable();
            $table->string('message_media_heading_color', 10)->default('#000000');
            $table->text('firebase_device_token')->nullable();
            if (in_array(DB::getDriverName(), ['mysql', 'pgsql'])) {
                // If the database is MySQL or PostgreSQL, use the JSON type
                $table->json('hidden_alert_ids')->nullable();
            } else {
                // For other database drivers, use TEXT as fallback
                $table->text('hidden_alert_ids')->nullable();
            }
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
