<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'surname' => $this->faker->lastName(),
            'apogee' => $this->faker->unique()->numberBetween(10000000, 99999999),
            'filiere' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'password' => bcrypt('password'),
        ];
    }

    public function unverified()
    {
        return $this->state([
            'email_verified_at' => null,
        ]);
    }
}

