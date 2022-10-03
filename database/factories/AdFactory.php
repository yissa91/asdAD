<?php

namespace Database\Factories;

use App\Models\category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class AdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->text(10),
            'description' => fake()->text(50),
             'price' => fake()->numberBetween(10,100),
            'available' => fake()->boolean,
          //  "category_id" => category::factory()->create()->id,

            'view_number'=>fake()->numberBetween(0,10)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
