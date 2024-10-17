<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ParentRelativesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('parent_relatives')->delete();
        
        \DB::table('parent_relatives')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 5,
                'name' => 'Relative Name',
                'phone3' => '+255 1111 222 333',
                'phone4' => NULL,
                'relation' => 'Brother',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}