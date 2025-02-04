<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreakingChangeInsertNewRecordsToSettingsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert specific data into the table
        DB::table('settings')->insert([
            [
                'id' => NULL,
                'type' => 'enable_email_notification',
                'description' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ],
            [
                'id' => NULL,
                'type' => 'enable_sms_notification',
                'description' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ],
            [
                'id' => NULL,
                'type' => 'enable_push_notification',
                'description' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ],
        ]);
    }
}
