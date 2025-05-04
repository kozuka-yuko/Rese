<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    protected $model = Shop::class;
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
            'area_id' => Area::factory(),
            'genre_id' => Genre::factory(),
        ];
    }
}
