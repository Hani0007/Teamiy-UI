<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('package_modules')->truncate();

        $record = [
            ['plan_id' => 2, 'module_id' => 1],
            ['plan_id' => 2, 'module_id' => 2],
            ['plan_id' => 2, 'module_id' => 3],
            ['plan_id' => 2, 'module_id' => 4],
            ['plan_id' => 2, 'module_id' => 5],
            ['plan_id' => 2, 'module_id' => 6],

            ['plan_id' => 3, 'module_id' => 1],
            ['plan_id' => 3, 'module_id' => 2],
            ['plan_id' => 3, 'module_id' => 3],
            ['plan_id' => 3, 'module_id' => 4],
            ['plan_id' => 3, 'module_id' => 5],
            ['plan_id' => 3, 'module_id' => 6],
            ['plan_id' => 3, 'module_id' => 7],
            ['plan_id' => 3, 'module_id' => 8],
            ['plan_id' => 3, 'module_id' => 9],
            ['plan_id' => 3, 'module_id' => 10],

            ['plan_id' => 4, 'module_id' => 1],
            ['plan_id' => 4, 'module_id' => 2],
            ['plan_id' => 4, 'module_id' => 3],
            ['plan_id' => 4, 'module_id' => 4],
            ['plan_id' => 4, 'module_id' => 5],
            ['plan_id' => 4, 'module_id' => 6],
            ['plan_id' => 4, 'module_id' => 7],
            ['plan_id' => 4, 'module_id' => 8],
            ['plan_id' => 4, 'module_id' => 9],
            ['plan_id' => 4, 'module_id' => 10],
            ['plan_id' => 4, 'module_id' => 11],
            ['plan_id' => 4, 'module_id' => 12],
            ['plan_id' => 4, 'module_id' => 13],


        ];

        DB::table('package_modules')->insert($record);
    }
}
