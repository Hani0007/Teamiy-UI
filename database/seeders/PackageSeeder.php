<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::statement('ALTER TABLE package_modules DROP FOREIGN KEY package_modules_plan_id_foreign');

        DB::table('packages')->truncate();

        $packages = [
            ['name' => 'Trial', 'price_per_month' => null, 'price_per_year' => null],
            ['name' => 'Basic', 'price_per_month' => '2.50', 'price_per_year' => '2.00'],
            ['name' => 'Standard', 'price_per_month' => '5.00', 'price_per_year' => '4.50'],
            ['name' => 'Premium', 'price_per_month' => '7.00', 'price_per_year' => '6.00'],
        ];

        DB::table('packages')->insert($packages);

        DB::statement('
            ALTER TABLE package_modules
            ADD CONSTRAINT package_modules_plan_id_foreign
            FOREIGN KEY (plan_id) REFERENCES packages(id)
            ON DELETE CASCADE
        ');

        Schema::enableForeignKeyConstraints();
    }
}
