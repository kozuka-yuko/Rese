<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

     /** @test */
     public function it_formats_time_attribute_as_H_i()
     {
        $reservation = Reservation::factory()->create([
            'time' => '2025-05-04 14:20:00',
        ]);

        $this->assertEquals('14:20', $reservation->time);
     }

    /** @test */
    public function reservation_can_have_user()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $reservation->user->id);
    }

    /** @test */
    public function reservation_can_have_shop()
    {
        $shop = Shop::factory()->create();
        $reservation = Reservation::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $this->assertEquals($shop->id, $reservation->shop->id);
    }
}
