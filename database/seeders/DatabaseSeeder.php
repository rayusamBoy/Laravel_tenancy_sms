<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {       
        // Universal seeders
        $this->call(NationalitiesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(LgasTableSeeder::class);
        $this->call(BloodGroupsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(TimezonesTableSeeder::class);
        // Tenancy specific seeders
        $this->call(TenancyExamCategoriesTableSeeder::class);
        $this->call(TenancyUserTypesTableSeeder::class);
        $this->call(TenancySkillsTableSeeder::class);
        $this->call(TenancySettingsTableSeeder::class);
        $this->call(TenancyUsersTableSeeder::class);
        $this->call(TenancyStaffRecordsTableSeeder::class);
        $this->call(TenancyClassTypesTableSeeder::class);
        $this->call(TenancyClassTypesTableSeeder::class);
        $this->call(TenancyMyClassesTableSeeder::class);
        $this->call(TenancySectionsTableSeeder::class);
        $this->call(TenancyStudentRecordsTableSeeder::class);
        $this->call(ParentRelativesTableSeeder::class);
    }
}
