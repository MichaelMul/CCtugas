<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('Password123!'),
            'date_of_birth' => fake()->dateTimeBetween('-30 years', '-18 years')->format('Y-m-d'),
            'status' => Registration::STATUS_PENDING,
            'email_verified_at' => null,
        ];
    }
}
