<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->text(100),
            'img_url' => $this->faker->imageUrl(640, 480, 'food', true),
            'area_id' => \App\Models\Area::factory(),
            'genre_id' => \App\Models\Genre::factory(),
        ];
    }
}
