<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

     /** @test */
    public function favorite_can_have_user()
    {
        $user = User::factory()->create();
        $favorite = Favorite::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $favorite->user->id);
    }

    /** @test */
    public function favorite_can_have_shop()
    {
        $shop = Shop::factory()->create();
        $favorite = Favorite::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $this->assertEquals($shop->id, $favorite->shop->id);
    }
}
