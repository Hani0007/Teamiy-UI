<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminTableSeeder::class,
            PermissionSeeder::class,
            CompanySeeder::class,
            AppSettingSeeder::class,
            GeneralSettingSeeder::class,
            LeaveTypeSeeder::class,
            FeatureSeeder::class,
            PaymentPlansSeeder::class,
        ]);
    }
}
