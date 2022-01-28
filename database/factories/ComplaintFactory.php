<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1,5),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph(1),
            'image' => $this->faker->imageUrl(),
            'recipient_id' => $this->faker->numberBetween(1,5),
            'is_anonymous' => $this->faker->boolean,
            'is_private' => $this->faker->boolean,
        ];
    }
}
