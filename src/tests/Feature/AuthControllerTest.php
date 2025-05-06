<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     /** @test */
    public function it_displays_login_views()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test*/
    public function it_displays_thanks_view()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/thanks');

        $response->assertStatus(200);
        $response->assertViewIs('thanks');
    }

    /** @test */
    public function it_display_shop_view_with_data_and_flash_message()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $area = Area::factory()->create();
        $genre = Genre::factory()->create();
        $shop = Shop::factory()->create([
            'area_id' => $area->id,
            'genre_id' => $genre->id,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('shop');
        $response->assertViewHasAll([
            'areas',
            'genres',
            'shops',
        ]);
        $response->assertSee('ログインしました');
    }
}
