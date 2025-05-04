<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;
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
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'number' => $this->faker->numberBetween(1, 10),
            'status' => '予約',
        ];
    }
}
