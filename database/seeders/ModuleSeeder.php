<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::statement('ALTER TABLE package_modules DROP FOREIGN KEY package_modules_module_id_foreign');

        DB::table('modules')->truncate();

        $modules = [
            ['name' => 'Employee Management'],
            ['name' => 'Chat & Document Sharing'],
            ['name' => 'Attendance Section'],
            ['name' => 'Event Calender'],
            ['name' => 'Leave Management'],
            ['name' => 'Shift Management'],
            ['name' => 'Payroll Management'],
            ['name' => 'Team Meeting'],
            ['name' => 'Notice Board'],
            ['name' => 'HR Admin Setup'],
            ['name' => 'Project Management'],
            ['name' => 'Training Management'],
            ['name' => 'Asset Management'],
        ];

        DB::table('modules')->insert($modules);

        DB::statement('
            ALTER TABLE package_modules
            ADD CONSTRAINT package_modules_module_id_foreign
            FOREIGN KEY (module_id) REFERENCES modules(id)
            ON DELETE CASCADE
        ');

        Schema::enableForeignKeyConstraints();
    }
}
