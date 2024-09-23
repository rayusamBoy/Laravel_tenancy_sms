<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NonTenancyStaffRecordsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('staff_records')->delete();
        
        \DB::table('staff_records')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'staff_data_edit' => 0,
                'place_of_living' => 'Zanzibar Town',
                'role' => 'An IT person',
                'code' => 'ITGUY',
                'emp_date' => '01/01/2020',
                'emp_no' => NULL,
                'confirmation_date' => NULL,
                'licence_number' => NULL,
                'file_number' => NULL,
                'bank_acc_no' => NULL,
                'bank_name' => NULL,
                'tin_number' => NULL,
                'ss_number' => NULL,
                'no_of_periods' => NULL,
                'education_level' => 'Degree',
                'college_attended' => 'Mzumbe University',
                'year_graduated' => '2018',
                'subjects_studied' => '["Information and Computer Studies","Basic Mathematics","Additional Mathematics"]',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}