<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenancySkillsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skills')->delete();
        
        \DB::table('skills')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'PUNCTUALITY',
                'skill_type' => 'AF',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'NEATNESS',
                'skill_type' => 'AF',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'HONESTY',
                'skill_type' => 'AF',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'RELIABILITY',
                'skill_type' => 'AF',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'RELATIONSHIP WITH OTHERS',
                'skill_type' => 'AF',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'POLITENESS',
                'skill_type' => 'AF',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'ALERTNESS',
                'skill_type' => 'AF',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'HANDWRITING',
                'skill_type' => 'PS',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'GAMES & SPORTS',
                'skill_type' => 'PS',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'DRAWING & ARTS',
                'skill_type' => 'PS',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'PAINTING',
                'skill_type' => 'PS',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'CONSTRUCTION',
                'skill_type' => 'PS',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'MUSICAL SKILLS',
                'skill_type' => 'PS',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'FLEXIBILITY',
                'skill_type' => 'PS',
                'class_type_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}