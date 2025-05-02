<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
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
    public function shopReview_can_have_users()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $shopReview = ShopReview::factory()->create(
            [
                'user_id' => $user->id,
                'shop_id' => $shop->id,
            ]);
    }
}
