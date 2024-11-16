<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenancyStaffRecordsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('staff_records')->delete();

        \DB::table('staff_records')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'user_id' => 1,
                    'staff_data_edit' => 0,
                    'place_of_living' => 'Zanzibar Town',
                    'role' => 'An IT person',
                    'code' => 'SUPERADMIN',
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
                    'year_graduated' => '2020',
                    'subjects_studied' => '["Information and Computer Studies","Basic Mathematics","Additional Mathematics"]',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            1 =>
                array(
                    'id' => 2,
                    'user_id' => 2,
                    'staff_data_edit' => 0,
                    'place_of_living' => 'Zanzibar Town',
                    'role' => NULL,
                    'code' => 'ADMIN',
                    'emp_date' => NULL,
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
                    'college_attended' => 'State University of Zanzibar (SUZA)',
                    'year_graduated' => '2010',
                    'subjects_studied' => '["Civics","History"]',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            2 =>
                array(
                    'id' => 3,
                    'user_id' => 3,
                    'staff_data_edit' => 0,
                    'place_of_living' => 'Zanzibar Town',
                    'role' => NULL,
                    'code' => 'TEACHER',
                    'emp_date' => NULL,
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
                    'college_attended' => 'State University of Zanzibar (SUZA)',
                    'year_graduated' => '2010',
                    'subjects_studied' => '["Civics","History"]',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            3 =>
                array(
                    'id' => 4,
                    'user_id' => 4,
                    'staff_data_edit' => 0,
                    'place_of_living' => 'Zanzibar Town',
                    'role' => NULL,
                    'code' => 'COMPANION',
                    'emp_date' => NULL,
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
                    'college_attended' => 'State University of Zanzibar (SUZA)',
                    'year_graduated' => '2010',
                    'subjects_studied' => '["Civics","History"]',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            4 =>
                array(
                    'id' => 5,
                    'user_id' => 6,
                    'staff_data_edit' => 0,
                    'place_of_living' => 'Zanzibar Town',
                    'role' => NULL,
                    'code' => 'ACCOUNTANT',
                    'emp_date' => NULL,
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
                    'college_attended' => 'State University of Zanzibar (SUZA)',
                    'year_graduated' => '2010',
                    'subjects_studied' => '["Civics","History"]',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            5 =>
                array(
                    'id' => 6,
                    'user_id' => 8,
                    'staff_data_edit' => 0,
                    'place_of_living' => 'Zanzibar Town',
                    'role' => NULL,
                    'code' => 'LIBRARIAN',
                    'emp_date' => NULL,
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
                    'college_attended' => 'State University of Zanzibar (SUZA)',
                    'year_graduated' => '2010',
                    'subjects_studied' => '["Civics","History"]',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));
    }
}