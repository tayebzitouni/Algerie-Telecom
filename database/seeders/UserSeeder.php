<?php

namespace Database\Seeders;

use App\Models\Emploi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // Create 2 HR users
        User::factory()->count(2)->hr()->create();

        // Create 5 emploi users
        User::factory()->count(5)->emploi()->create()->each(function ($user) {
            // For each emploi user, create a record in emplois table
            Emploi::create([
                'user_id' => $user->id,
                'department_id' => 1, // Assign a default department or pick randomly
            ]);
        });
    }
}
