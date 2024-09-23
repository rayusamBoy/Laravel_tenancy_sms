<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NonTenancyUserTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_types')->delete();
        
        \DB::table('user_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'it_guy',
                'name' => 'IT Guy',
                'level' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}