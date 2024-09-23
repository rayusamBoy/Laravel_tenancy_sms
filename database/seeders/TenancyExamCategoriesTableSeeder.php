<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenancyExamCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exam_categories')->delete();
        
        \DB::table('exam_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Weekly',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Monthly',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Mid-Term',
            ),
            3 => 
            array (
                'id' => 4,
            'name' => 'Terminal (First Term Exam)',
            ),
            4 => 
            array (
                'id' => 5,
            'name' => 'Annual (Second Term Exam)',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Mock',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Pre-mock',
            ),
        ));
        
        
    }
}