<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Shop;
use App\Models\User;

/**
 * @property \App\Models\Area $area
 * @property \App\Models\Genre $genre
 * @property \App\Models\Shop $shop
 */
class ReseControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     public function setUp(): void
     {
        parent::setUp();

        //必要なデータの事前作成
        $this->area = Area::factory()->create();
        $this->genre = Genre::factory()->create();
        $this->shop = Shop::factory()->create([
            'area_id' => $this->area->id,
            'genre_id' => $this->genre->id,
        ]);
     }

     /** @test */
    public function it_displays_the_shop_page()
    {
        $response = $this->get('/shop');

        $response->assertStatus(200);
        $response->assertViewIs('shop');
        $response->assertViewHasAll([
            'areas',
            'genres',
            'shops',
        ]);
    }

    /** @test */
    public function test_detail_page_display_correctly()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/detail/' . $this->shop->id);

        $response->assertStatus(200);
        $response->assertViewIs('detail');
        $response->assertViewHasAll([
            'today',
            'shop',
            'times',
            'numbers',
        ]);
    }

    /** @test */
    public function it_can_search_shop_by_area_genre_and_name()
    {
        $area = Area::factory()->create(['name' => '東京']);
        $genre = Genre::factory()->create(['name' => 'イタリアン']);
        $shop = Shop::factory()->create([
            'name' => 'テストレストラン',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
        ]);

        $response = $this->get('/search?area_id=' . $area->id . '&genre_id=' . $genre->id . '&name_input=テスト');

        $response->assertStatus(200);
        $response->assertViewIs('shop');
        $response->assertSee('テストレストラン');
    }

    /** @test */
    public function user_can_make_a_reservation()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $shop =Shop::factory()->create();

        $response = $this->post('/reservation/' . $shop->id, [
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '12:00',
            'number' => 2,
        ]);

        $response->assertViewIs('done');
    }

    /** @test */
    public function mypage_displays_user_reservations_and_favorites()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $shop = Shop::factory()->create();
        Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'date' =>now()->addDays(1)->format('Y-m-d'),
            'status' => '予約',
        ]);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        $response->assertViewIs('mypage');
        $response->assertSee($shop->name);
    }
}
