<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BloodGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('blood_groups')->delete();
        
        \DB::table('blood_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'O-',
                'created_at' => '2022-07-19 01:52:38',
                'updated_at' => '2022-07-19 01:52:38',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'O+',
                'created_at' => '2022-07-19 01:52:38',
                'updated_at' => '2022-07-19 01:52:38',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'A+',
                'created_at' => '2022-07-19 01:52:38',
                'updated_at' => '2022-07-19 01:52:38',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'A-',
                'created_at' => '2022-07-19 01:52:38',
                'updated_at' => '2022-07-19 01:52:38',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'B+',
                'created_at' => '2022-07-19 01:52:38',
                'updated_at' => '2022-07-19 01:52:38',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'B-',
                'created_at' => '2022-07-19 01:52:38',
                'updated_at' => '2022-07-19 01:52:38',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'AB+',
                'created_at' => '2022-07-19 01:52:38',
                'updated_at' => '2022-07-19 01:52:38',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'AB-',
                'created_at' => '2022-07-19 01:52:38',
                'updated_at' => '2022-07-19 01:52:38',
            ),
        ));
        
        
    }
}