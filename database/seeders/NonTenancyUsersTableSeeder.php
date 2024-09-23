<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NonTenancyUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'It Guy',
                'blocked' => 0,
                'religion' => 'Islam',
                'email' => 'itguy@sms.com',
                'code' => 'ITGUY',
                'username' => 'itguy',
                'user_type' => 'it_guy',
                'work' => 'NULL',
                'dob' => '01/11/1111',
                'gender' => 'Male',
                'photo' => 'global_assets/images/user.png',
                'primary_id' => NULL,
                'secondary_id' => NULL,
                'phone' => '+255 1234 567 81',
                'phone2' => NULL,
                'bg_id' => 2,
                'state_id' => 4081,
                'lga_id' => 120781,
                'nal_id' => 220,
                'address' => 'address',
                'email_verified_at' => NULL,
                'password' => Hash::make('itguy'),
                'remember_token' => NULL,
                'sidebar_minimized' => 0,
                'allow_system_sounds' => 0,
                'show_charts' => 1,
                'password_updated_at' => NULL,
                'twofa_secret_code' => NULL,
                'twofa_recovery_codes' => NULL,
                'message_media_heading_color' => '#818b6c',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            )
        ));
    }
}