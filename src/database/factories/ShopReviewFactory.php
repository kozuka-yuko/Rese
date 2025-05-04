<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ShopReview;
use App\Models\User;
use App\Models\Shop;


class ShopReviewFactory extends Factory
{
    protected $model = ShopReview::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'shop_id' => Shop::factory(),
            'stars' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence(),
        ];
    }
}
