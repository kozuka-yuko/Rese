<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Reservation;
use App\Models\ShopReview;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ShopTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    /** @test */
    public function shop_can_have_area()
    {
        $area = Area::factory()->create();
        $shop = Shop::factory()->create(['area_id' => $area->id,]);

        $this->assertEquals($area->id, $shop->area->id);        
    }

    /** @test */
    public function shop_can_have_genres()
    {
        $genre = Genre::factory()->create();
        $shop = Shop::factory()->create([
            'genre_id' => $genre->id,
        ]);

        $this->assertEquals($genre->id, $shop->genre->id);
    }

    /** @test */
    public function shop_can_have_favorites()
    {
        $shop = Shop::factory()->create( );
        $favorite = Favorite::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $this->assertTrue($shop->favorites->contains($favorite));
    }

    /** @test */
    public function shop_can_belong_to_users()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $shop->users()->attach($user->id);

        $this->assertTrue($shop->users->contains($user));
    }

    /** @test */
    public function shop_can_have_reservations()
    {
        $shop = Shop::factory()->create();
        $reservation = Reservation::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $this->assertTrue($shop->reservations->contains($reservation));
    }

    /** @test */
    public function shop_can_have_shop_reviews()
    {
        $shop = Shop::factory()->create();
        $shopReview = ShopReview::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $this->assertEquals($shop->id, $shopReview->shop->id);
    }

    /** @test */
    public function it_can_search_shop_by_area()
    {
        $area = Area::factory()->create();
        $shop = Shop::factory()->create([
            'area_id' => $area->id,
        ]);

        $results = Shop::areaSearch($area->id)->get();

        $this->assertTrue($results->contains($shop));
    }

    /** @test */
    public function it_can_search_shop_by_genre()
    {
        $genre = Genre::factory()->create();
        $shop = Shop::factory()->create([
            'genre_id' => $genre->id,
        ]);

        $results = Shop::genreSearch($genre->id)->get();

        $this->assertTrue($results->contains($shop));
    }

    /** @test */
    public function it_can_search_shop_by_shop_name()
    {
       $shop = Shop::factory()->create(['name' => 'tama cafe']);

       $results = Shop::nameSearch('tama')->get();

       $this->assertTrue($results->contains($shop));
    }
}
