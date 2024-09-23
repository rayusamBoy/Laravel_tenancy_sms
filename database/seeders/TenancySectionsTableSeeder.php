<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenancySectionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sections')->delete();
        
        \DB::table('sections')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'A',
                'my_class_id' => 1,
                'teacher_id' => NULL,
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}