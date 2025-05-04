<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use Database\Factories\RoleFactory;


class AdminControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function get_ad_index()
    {
        $role = RoleFactory::new()->create(['name' => 'admin']);
        
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get('/admin/management');
        $response->assertStatus(200);
        
        $response->assertViewIs('admin.management');

        $response = $this->get('/no_route');
        $response->assertStatus(404);
    }

    /** @test */
    public function get_shop_rep_list()
    {
        $role = RoleFactory::new()->create(['name' => 'admin']);

        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($role);

        $shopRep = User::factory()->create();
        $role = RoleFactory::new()->create(['name' => 'shop_rep']);
        $shopRep->assignRole($role);

        $response = $this->actingAs($user)->get('/admin/shop_rep_list');
        $response->assertStatus(200);
        
        $response->assertViewIs('admin.shop_rep_list');
        $response->assertViewHas('shopReps');

        $response = $this->get('/no_route');
        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_search_shop_rep_by_keyword()
    {
        $role = RoleFactory::new()->create(['name' => 'shop_rep']);

        $user = User::factory()->create(['name' => 'Taro Tanaka']);
        $user->assignRole($role);
        $shop = Shop::factory()->create(['name' => 'tama cafe']);
        $user->shops()->attach($shop->id);

        $results = User::repNameSearch('Taro')->get();

        $this->assertTrue($results->contains($user));

        $results = User::shopNameSearch('tama')->get();

        $this->assertTrue($results->contains($user));
    }
}
