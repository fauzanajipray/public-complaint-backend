<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'avatar' => $this->faker->imageUrl(),
            'nik' => $this->faker->nik,
        ];
    }
}
