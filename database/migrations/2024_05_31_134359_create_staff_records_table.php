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
        Schema::create('staff_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('staff_records_user_id_foreign');
            $table->unsignedInteger('staff_data_edit')->default(0);
            $table->string('place_of_living', 100)->nullable()->index('lga_id');
            $table->string('role')->nullable();
            $table->string('code', 100)->nullable()->unique();
            $table->string('emp_date')->nullable();
            $table->integer('emp_no')->nullable()->unique('emp_no');
            $table->string('confirmation_date')->nullable();
            $table->string('licence_number')->nullable()->unique('licence_no');
            $table->string('file_number')->nullable();
            $table->string('bank_acc_no')->nullable()->unique('bank_acc_no');
            $table->string('bank_name')->nullable();
            $table->string('tin_number')->nullable()->unique('tin_no');
            $table->string('ss_number')->nullable()->unique('ss_number');
            $table->integer('no_of_periods')->nullable();
            $table->string('education_level')->nullable();
            $table->string('college_attended')->nullable();
            $table->string('year_graduated')->nullable();
            if (in_array(DB::getDriverName(), ['mysql', 'pgsql'])) {
                // If the database is MySQL or PostgreSQL, use the JSON type
                $table->json('subjects_studied')->nullable();
            } else {
                // For other database drivers, use TEXT as fallback
                $table->text('subjects_studied')->nullable();
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_records');
    }
};
