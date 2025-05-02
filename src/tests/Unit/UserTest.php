<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Shop;
use Spatie\Permission\Models\Role;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    /** @test */
    public function user_can_have_favorites()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $favorite = Favorite::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);

        $this->assertTrue($user->favorites->contains($favorite));
    }

    /** @test */
    public function user_can_belong_to_shops()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $user->shops()->attach($shop->id);

        $this->assertTrue($user->shops->contains($shop));
    }

    /** @test */
    public function it_can_search_user_by_rep_name()
    {
        $role = RoleFactory::new()->create(['name'=> 'shop_rep']);

        $user = User::factory()->create(['name' => 'Taro Tanaka']);
        $user->assignRole($role);

        $results = User::repNameSearch('Taro')->get();

        $this->assertTrue($results->contains($user));
    }

    /** @test */
    public function it_can_search_user_by_shop_name()
    {
        $shop = Shop::factory()->create(['name' => 'tama cafe']);
        $user = User::factory()->create();
        $user->shops()->attach($shop->id);

        $results = User::shopNameSearch('tama')->get();
        $this->assertTrue($results->contains($user));
    }
}