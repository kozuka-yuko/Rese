<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use Database\Factories\RoleFactory;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;

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

    /** @test */
    public function it_redirect_to_confirm_with_valid_input()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $postData = [
            'name' => 'Test Rep',
            'email'=> 'rep@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/admin/confirm', $postData);
        $response->assertRedirect(route('showRepConfirm'));
        
        $response->assertStatus(302);
        $this->assertEquals(session('form_input'), $postData);
    }

    /** @test */
    public function it_fails_validation_and_redirects_back()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $response = $this->post('/admin/confirm', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['name','email']);
        $response->assertStatus(302);
    }

    /** @test */
    public function it_show_the_confirm_page_with_session_data()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $formInput = [
            'name' => 'Test Rep',
            'email' => 'rep@example.com',
            'password' => 'password',
        ];

        session(['form_input' => $formInput]);
        $response = $this->get('/admin/confirm');

        $response->assertStatus(200);
        $response->assertViewIs('admin.confirm');
        $response->assertViewHas('data', $formInput);
    }

    /** @test */
    public function it_create_a_new_shop_rep_from_session_data()
    {
        Notification::fake();

        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $sessionData = [
            'name' => 'Shop Rep',
            'email' => 'newrep@example.com',
            'password' => 'password',
        ];

        session(['form_input' => $sessionData]);

        $response = $this->post('/admin/confirm/create');

        $response->assertRedirect(route('shopRepList'));
        $response->assertSessionMissing('form_input');
        $this->assertDatabaseHas('users', ['email' => 'newrep@example.com']);

        $shopRep = User::where('email', 'newrep@example.com')->first();
        $this->assertTrue(Hash::check('password', $shopRep->password));
        $this->assertTrue($shopRep->hasRole('shop_rep'));

        Notification::assertSentTo($shopRep, VerifyEmail::class);
    }

    /** @test */
    public function it_delete_a_shop_rep_and_redirect()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $response = $this->delete('/admin/shop_rep_list/delete', ['id' => $shopRep->id]);

        $response->assertRedirect(route('shopRepList'));
        $response->assertSessionHas('result', '削除しました');
        $this->assertDatabaseMissing('users', ['id' => $shopRep->id]);
    }

    /** @test */
    public function it_shows_update_edit_page()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $response = $this->get("/admin/shop_rep_update/{$shopRep->id}");
        $response->assertStatus(200);
        $response->assertViewIs('admin.shop_rep_update');
        $response->assertViewHas('shopRep', $shopRep);
    }

    /** @test */
    public function it_puts_update_data_in_session_and_redirects_to_confirm()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $data = [
            'name' => 'Update Name',
            'email' => 'update@example.com',
        ];

        $response = $this->post("/admin/update_confirm/{$shopRep->id}", $data);

        $response->assertRedirect(route('showUpdateConfirm', ['id' => $shopRep->id]));
        $this->assertEquals(session('form_input'), $data);
    }

    /** @test */
    public function it_displays_update_confirm_view_with_data()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);
        
        $data = ['name' => 'Update Name', 'email' => 'update@example.com'];
        session(['form_input' => $data]);

        $response = $this->get("/admin/update_confirm/{$shopRep->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.update_confirm');
        $response->assertViewHasAll(['data', 'shopRep']);
    }

    /** @test */
    public function it_updates_the_shop_rep_and_clears_session()
    {
        $this->withoutExceptionHandling();
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $shopRep = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $shopRep->assignRole($shopRepRole);

        $data = ['name' =>'New Name', 'email' => 'new@example.com'];
        session(['form_input' => $data]);

        $response = $this->patch("/admin/update_confirm/store/{$shopRep->id}");

        $response->assertRedirect(route('shopRepList'));
        $response->assertSessionMissing('form_input');

        $this->assertDatabaseHas('users', [
            'id' => $shopRep->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }
}