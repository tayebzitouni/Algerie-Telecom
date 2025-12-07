<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Constructor - initialize Faker manually for Laravel 8.
     */
    public function __construct()
    {
        parent::__construct();
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // default password
            'role' => $this->faker->randomElement(['hr', 'emploi']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * HR role state.
     */
    public function hr(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'hr',
        ]);
    }

    /**
     * Emploi role state.
     */
    public function emploi(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'emploi',
        ]);
    }
}
