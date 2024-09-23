<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenancyEventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('events')->delete();
        
        \DB::table('events')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'required',
                'description' => 'this event is required',
                'status' => 'new',
                'year' => 1990,
                'month' => 1,
                'day' => 1,
                'user_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}