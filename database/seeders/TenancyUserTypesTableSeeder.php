<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenancyUserTypesTableSeeder extends Seeder
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
                'title' => 'accountant',
                'name' => 'Accountant',
                'level' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'parent',
                'name' => 'Parent',
                'level' => '4',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'teacher',
                'name' => 'Teacher',
                'level' => '3',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'admin',
                'name' => 'Admin',
                'level' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'super_admin',
                'name' => 'Super Admin',
                'level' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'student',
                'name' => 'Student',
                'level' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'companion',
                'name' => 'Companion',
                'level' => '6',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'librarian',
                'name' => 'Librarian',
                'level' => '7',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
    }
}