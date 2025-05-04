<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\ShopReview;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShopReviewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    
    /** @test */
    public function shopReview_can_have_user()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $shopReview = ShopReview::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);

        $this->assertEquals($user->id, $shopReview->user->id);
    }

    /** @test */
    public function shopReview_can_have_shop()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $shopReview = ShopReview::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);

        $this->assertEquals($shop->id, $shopReview->shop->id);
    }
}
