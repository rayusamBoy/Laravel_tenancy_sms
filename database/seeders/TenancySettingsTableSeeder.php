<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenancySettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('settings')->delete();

        \DB::table('settings')->insert(array(
            0 =>
            array(
                'id' => 1,
                'type' => 'current_session',
                'description' => date("Y") - 1 . '-' . date('Y'),
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'type' => 'system_title',
                'description' => 'TITLE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'type' => 'system_name',
                'description' => 'YOUR SCHOOL NAME',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'type' => 'term_ends',
                'description' => '01/07' . date('Y'),
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'type' => 'term_begins',
                'description' => '01/01/' . date('Y'),
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'type' => 'phone',
                'description' => '0123456789',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array(
                'id' => 7,
                'type' => 'address',
                'description' => 'Address',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array(
                'id' => 8,
                'type' => 'system_email',
                'description' => 'example@example.domain',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 =>
            array(
                'id' => 9,
                'type' => 'alt_email',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 =>
            array(
                'id' => 10,
                'type' => 'email_host',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 =>
            array(
                'id' => 11,
                'type' => 'email_pass',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 =>
            array(
                'id' => 12,
                'type' => 'lock_exam',
                'description' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 =>
            array(
                'id' => 13,
                'type' => 'logo',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 =>
            array(
                'id' => 14,
                'type' => 'next_term_fees_j',
                'description' => '0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 =>
            array(
                'id' => 15,
                'type' => 'next_term_fees_pn',
                'description' => '0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 =>
            array(
                'id' => 16,
                'type' => 'next_term_fees_pe',
                'description' => '0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 =>
            array(
                'id' => 17,
                'type' => 'next_term_fees_n',
                'description' => '0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 =>
            array(
                'id' => 18,
                'type' => 'next_term_fees_se',
                'description' => '0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 =>
            array(
                'id' => 19,
                'type' => 'next_term_fees_ase',
                'description' => '0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 =>
            array(
                'id' => 20,
                'type' => 'login_and_related_pages_bg',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 =>
            array(
                'id' => 21,
                'type' => 'allow_marksheet_print',
                'description' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 =>
            array(
                'id' => 22,
                'type' => 'allow_assessmentsheet_print',
                'description' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 =>
            array(
                'id' => 23,
                'type' => 'admin_email',
                'description' => 'admin@example.domain',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 =>
            array(
                'id' => 24,
                'type' => 'admin_whatsapp_link',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 =>
            array(
                'id' => 25,
                'type' => 'admin_linkedin_link',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 =>
            array(
                'id' => 26,
                'type' => 'admin_facebook_link',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 =>
            array(
                'id' => 27,
                'type' => 'admin_github_link',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 =>
            array(
                'id' => 28,
                'type' => 'analytics_enabled',
                'description' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 =>
            array(
                'id' => 29,
                'type' => 'google_analytic_tag_id',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 =>
            array(
                'id' => 30,
                'type' => 'google_analytic_property_id',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 =>
            array(
                'id' => 31,
                'type' => 'google_analytic_service_account_credential_file',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 =>
            array(
                'id' => 32,
                'type' => 'login_and_related_pgs_txts_and_bg_colors',
                'description' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 =>
            array(
                'id' => 33,
                'type' => 'enable_email_notification',
                'description' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 =>
            array(
                'id' => 34,
                'type' => 'enable_sms_notification',
                'description' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 =>
            array(
                'id' => 35,
                'type' => 'enable_push_notification',
                'description' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            
        ));
    }
}

