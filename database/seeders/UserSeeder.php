<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Emploi;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 2 HR users
        User::factory()->count(2)->hr()->create();

        $users = User::factory()->count(5)->emploi()->create();

        foreach ($users as $user) {
            // Ensure $user is a model instance
            if ($user instanceof User) {
                Emploi::create([
                    'user_id' => $user->id,
                    'department_id' => 1, // default or random
                ]);
            }
        }
    }
}
