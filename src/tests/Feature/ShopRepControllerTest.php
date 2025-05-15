<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Database\Factories\RoleFactory;


/**
 * @property \App\Models\Area $area
 * @property \App\Models\Genre $genre
 * @property \App\Models\Shop $shop
 */

class ShopRepControllerTest extends TestCase
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
    }

    /** @test */
    public function get_rep_index()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $response = $this->get(route('repIndex'));

        $response->assertStatus(200);
        $response->assertViewIs('shop_rep.shop');
        $response->assertViewHas('shop');
    }

    /** @test */
    public function get_shop_create_page()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $response = $this->get(route('shopCreate'));

        $response->assertStatus(200);
        $response->assertViewIs('shop_rep.shop_create');
        $response->assertViewHasAll([
            'areas',
            'genres',
        ]);
    }

    /** @test */
    public function it_redirects_to_confirm_page_with_image_upload()
    {
        // フェイクディスクの作成（保存用ディスクが作成される）
        //テスト後ファイルは削除される
        Storage::fake('public');

        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $file = UploadedFile::fake()->image('shop.jpg');

        $response = $this->post(route('shopCreateConfirm'), [
            'name' => 'Test Shop',
            'image' => $file,
            'area' => $this->area->id,
            'genre' => $this->genre->id,
            'description' => 'Test description'
        ]);

        $response->assertRedirect(route('showShopCreateConfirm'));

        $this->assertTrue(Session::has('form_input'));
        $sessionData = session('form_input');

        $this->assertEquals('Test Shop', $sessionData['name']);
        $this->assertNotNull($sessionData['img_url']);
        $this->assertEquals($this->area->id, $sessionData['area_id']);
        $this->assertEquals($this->genre->id, $sessionData['genre_id']);
        $this->assertEquals('Test description', $sessionData['description']);

        $this->assertFileExists(storage_path('app/' . $sessionData['img_url']));
    }

    /** @test */
    public function shopCreateConfirm_handles_missing_image()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $response = $this->post(route('shopCreateConfirm'), [
            'name' => 'Test Shop',
            'area' => $this->area->id,
            'genre' => $this->genre->id,
            'description' => 'Test description'
        ]);

        $response->assertRedirect(route('showShopCreateConfirm'));

        $sessionData = session('form_input');

        $this->assertNull($sessionData['img_url']);
    }

    /** @test */
    public function showShopCreateConfirm_displays_the_confirm_page_with_session_data()
    {
        $area = Area::factory()->create(['name' => 'Tokyo']);
        $genre = Genre::factory()->create(['name' => 'Sushi']);

        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        session([
            'form_input' => [
                'name' => 'Test Shop',
                'img_url' => 'public/images/test.jpg',
                'area_id' => $area->id,
                'genre_id' => $genre->id,
                'description' => 'Test description'
            ]
        ]);

        $response = $this->get(route('showShopCreateConfirm'));

        $response->assertStatus(200);
        $response->assertViewIs('shop_rep.shop_create_confirm');
        $response->assertViewHas('data', [
            'name' => 'Test Shop',
            'img_url' => 'public/images/test.jpg',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'description' => 'Test description'
        ]);

        $response->assertViewHas('area', $area);
        $response->assertViewHas('genre', $genre);
    }

    /** @test */
    public function it_can_new_shop_create()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $sessionData = [
            'name' => 'Test Shop',
            'img_url' => 'public/images/test.jpg',
            'area_id' => $this->area->id,
            'genre_id' => $this->genre->id,
            'description' => 'Test description'
        ];

        session(['form_input' => $sessionData]);

        $response = $this->post(route('newShopCreate'));

        $response->assertSessionMissing('form_input');

        $response->assertRedirect(route('repIndex'));
        $response->assertSessionHas('result', '店舗を登録しました');

        $this->assertDatabaseHas('shops', [
            'name' => 'Test Shop',
            'img_url' => 'public/images/test.jpg',
            'area_id' => $this->area->id,
            'genre_id' => $this->genre->id,
            'description' => 'Test description' 
        ]);

        $this->assertDatabaseHas('shop_user', [
            'user_id' => $shopRep->id,
            'shop_id' => Shop::first()->id,
        ]);
    }

    /** @test */
    public function cancel_it_deletes_image_and_clears_session_when_image_path_exists()
    {
        // ストレージをモック
        Storage::fake('public');

        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        // テスト用のファイルパス
        $testPath = 'public/images/test.jpg';
        // セッションデータを設定
        session(['form_input' => ['img_url' => $testPath]]);
        // テスト用ファイルを作成
        Storage::disk('public')->put($testPath, 'dummy content');
        // コントローラーメソッドの呼び出し
        $response = $this->post(route('createCancel'));
        
        // ファイルが削除されたことを確認
        Storage::disk('local')->assertMissing($testPath);

        $response->assertRedirect(route('repIndex'));
    }
    
    /** @test */
    public function it_can_shop_delete()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $shop = Shop::factory()->create();
        $shop->users()->attach($shopRep->id);

        $response = $this->delete(route('shopDestroy', ['id' => $shop->id]));

        $response->assertRedirect(route('repIndex'));
        $this->assertDatabaseMissing('shops', ['id' => $shop->id]);
    }

    /** @test */
    public function get_shop_edit_page()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $shop = Shop::factory()->create();
        $shop->users()->attach($shopRep->id);

        $response = $this->get(route('shopEdit', ['id' => $shop->id]));

        $response->assertStatus(200);
        $response->assertViewIs('shop_rep.edit');
        $response->assertViewHasAll([
            'areas',
            'genres',
            'shop',
        ]);
    }
}