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
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $response = $this->actingAs($admin)->get('/admin/management');
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
    public function it_can_search_shop_rep_by_rep_name()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        $role = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $user = User::factory()->create(['name' => 'Taro Tanaka']);
        $user->assignRole($role);
        $shop = Shop::factory()->create(['name' => 'tama cafe']);
        $user->shops()->attach($shop->id);

        $this->actingAs($admin);

        $response = $this->get('/admin/shop_rep_list/search?rep_name=Taro&shop_name=');

        $response->assertStatus(200);
        $response->assertViewIs('admin.shop_rep_list');
        $response->assertViewHas('shopReps', function ($shopReps) use ($user) {
            return $shopReps->contains($user);
        });
    }

    /** @test */
    public function it_can_search_shop_rep_by_shop_name()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        $role = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $user = User::factory()->create(['name' => 'Taro Tanaka']);
        $user->assignRole($role);
        $shop = Shop::factory()->create(['name' => 'tama cafe']);
        $user->shops()->attach($shop->id);

        $this->actingAs($admin);

        $response = $this->get('/admin/shop_rep_list/search?rep_name=&shop_name=tama');

        $response->assertStatus(200);
        $response->assertViewIs('admin.shop_rep_list');
        $response->assertViewHas('shopReps', function ($shopReps) use ($user) {
            return $shopReps->contains($user);
        });
    }

    /** @test */
    public function it_can_search_shop_rep_by_rep_name_and_shop_name()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        $role = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $user = User::factory()->create(['name' => 'Taro Tanaka']);
        $user->assignRole($role);
        $shop = Shop::factory()->create(['name' => 'tama cafe']);
        $user->shops()->attach($shop->id);

        $this->actingAs($admin);

        $response = $this->get('/admin/shop_rep_list/search?rep_name=Taro&shop_name=tama');

        $response->assertStatus(200);
        $response->assertViewIs('admin.shop_rep_list');
        $response->assertViewHas('shopReps', function ($shopReps) use ($user) {
            return $shopReps->contains($user);
        });
    }

    /** @test */
    public function get_new_rep_create_form()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $response = $this->get('/admin/new_rep_create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.new_rep_create');

        $response = $this->get('/no_route');
        $response->assertStatus(404);
    }
}
