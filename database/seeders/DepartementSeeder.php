<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Department::create(['name' => 'HR']);
        Department::create(['name' => 'IT']);
        Department::create(['name' => 'Finance']);
    }
}
