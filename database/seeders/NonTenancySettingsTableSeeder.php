<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NonTenancySettingsTableSeeder extends Seeder
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
                'type' => 'phone',
                'description' => '0123456789',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'type' => 'system_name',
                'description' => 'SCHOOL MANANGEMENT SYSTEM',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'type' => 'address',
                'description' => 'Address',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'type' => 'system_email',
                'description' => 'info@example.domain',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'type' => 'alt_email',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'type' => 'email_host',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array(
                'id' => 7,
                'type' => 'email_pass',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array(
                'id' => 8,
                'type' => 'logo',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 =>
            array(
                'id' => 9,
                'type' => 'login_and_related_pages_bg',
                'description' => 'storage/uploads/login_and_related_pages_bg.png',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 =>
            array(
                'id' => 10,
                'type' => 'admin_email',
                'description' => 'admin@example.domain',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 =>
            array(
                'id' => 11,
                'type' => 'admin_whatsapp_link',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 =>
            array(
                'id' => 12,
                'type' => 'admin_linkedin_link',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 =>
            array(
                'id' => 13,
                'type' => 'admin_facebook_link',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 =>
            array(
                'id' => 14,
                'type' => 'admin_github_link',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 =>
            array(
                'id' => 15,
                'type' => 'analytics_enabled',
                'description' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 =>
            array(
                'id' => 16,
                'type' => 'google_analytic_tag_id',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 =>
            array(
                'id' => 17,
                'type' => 'google_analytic_property_id',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 =>
            array(
                'id' => 18,
                'type' => 'google_analytic_service_account_credential_file',
                'description' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 =>
            array(
                'id' => 19,
                'type' => 'login_and_related_pgs_txts_and_bg_colors',
                'description' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
    }
}
