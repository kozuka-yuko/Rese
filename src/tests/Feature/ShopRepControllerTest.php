<?php

namespace Tests\Feature;

use Carbon\Carbon;
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
use App\Models\Reservation;
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

     /**
      *それぞれのテストが実行される前に行う処理
      */
    public function setUp(): void
    {
        parent::setUp();

        //必要なデータの事前作成
        $this->area = Area::factory()->create();
        $this->genre = Genre::factory()->create();
    }

    protected function tearDown(): void
    {
        Storage::deleteDirectory('public/images');
        parent::tearDown();
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

        $shop = Shop::factory()->create();
        $shop->users()->attach($shopRep->id);

        // テスト用のファイルパス
        $testPath = 'public/images/test.jpg';
        // セッションデータを設定
        session(['form_input' => ['img_url' => $testPath]]);
        // テスト用ファイルを作成
        Storage::disk('public')->put($testPath, 'dummy content');
        // コントローラーメソッドの呼び出し
        $response = $this->post(route('createCancel', ['id' =>  $shop->id]));
        
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

    /** @test */
    public function shop_update_with_image_upload_redirects_to_confirm_page()
    {
        Storage::fake('public');

        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $shop = Shop::factory()->create();
        $shop->users()->attach($shopRep->id);

        $file = UploadedFile::fake()->image('shop.jpg');

        $response = $this->post(route('shopUpdateConfirm', ['id' => $shop->id]), [
            'image' => $file,
            'area' => $this->area->id,
            'genre' => $this->genre->id,
            'description' => 'Test description'
        ]);

        $response->assertRedirect(route('showShopUpdateConfirm', ['id' => $shop->id]));

        $this->assertTrue(Session::has('form_input'));
        $sessionData = session('form_input');

        $this->assertNotNull($sessionData['img_url']);
        $this->assertEquals($this->area->id, $sessionData['area_id']);
        $this->assertEquals($this->genre->id, $sessionData['genre_id']);
        $this->assertEquals('Test description', $sessionData['description']);

        $this->assertFileExists(storage_path('app/' . $sessionData['img_url']));
    }

    /** @test */
    public function shop_update_without_image_upload_redirects_to_confirm_page()
    {
        Storage::fake('public');
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $shop = Shop::factory()->create([
            'img_url' => 'public/images/old_image.jpg'
        ]);
        $shop->users()->attach($shopRep->id);

        $response = $this->post(route('shopUpdateConfirm', ['id' => $shop->id]), [
            'area' => $this->area->id,
            'genre' => $this->genre->id,
            'description' => 'Test description'
        ]);

        $response->assertRedirect(route('showShopUpdateConfirm', ['id' => $shop->id]));

        $sessionData = session('form_input');

        $this->assertEquals('public/images/old_image.jpg', $sessionData['img_url']);
        $this->assertEquals($this->area->id, $sessionData['area_id']);
        $this->assertEquals($this->genre->id, $sessionData['genre_id']);
        $this->assertEquals('Test description', $sessionData['description']);
    }

    /** @test */
    public function it_can_shop_edit()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $shop = Shop::factory()->create([
            'img_url' => 'public/images/old_image.jpg'
        ]);
        $shop->users()->attach($shopRep->id);

        $sessionData = [
            'img_url' => 'public/images/test.jpg',
            'area_id' => $this->area->id,
            'genre_id' => $this->genre->id,
            'description' => 'Test description'
        ];

        session(['form_input' => $sessionData]);

        $response = $this->patch(route('shopUpdate', ['id' => $shop->id]));

        $response->assertSessionMissing('form_input');

        $response->assertRedirect(route('repIndex'));
        $response->assertSessionHas('result', '店舗情報を変更しました');

        $this->assertDatabaseHas('shops', [
            'id' => $shop->id,
            'img_url' => 'public/images/test.jpg',
            'area_id' => $this->area->id,
            'genre_id' => $this->genre->id,
            'description' => 'Test description'
        ]);

        $this->assertDatabaseHas('shop_user', [
            'user_id' => $shopRep->id,
            'shop_id' => $shop->id,
        ]);

        $this->assertDatabaseMissing('shops', [
            'id' => $shop->id,
            'img_url' => 'public/images/old_image.jpg'
        ]);
    }

    /** @test */
    public function it_can_cancel_the_update_and_delete_the_image()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        Storage::fake('public');

        $shop = Shop::factory()->create([
            'img_url' => 'public/images/original.jpg',
        ]);
        $shop->users()->attach($shopRep->id);

        $tempImage = UploadedFile::fake()->image('temp.jpg')->store('public/images');

        $sessionData = Shop::factory()->create([
            'img_url' => $tempImage,
            'area_id' => $this->area->id,
            'genre_id' => $this->genre->id,
            'description' => 'Original description',
        ]);

        session(['form_input' => $sessionData]);

        $response = $this->post(route('cancel', ['id' => $shop->id]));

        $response->assertRedirect(route('repIndex'));

        $response->assertSessionMissing('form_input');

        // 一時的な画像の削除確認
        Storage::disk('public')->assertMissing($tempImage);

        // 元の画像は残っていることを確認
        $this->assertDatabaseHas('shops', [
            'id' => $shop->id,
            'img_url' => 'public/images/original.jpg',
        ]);
    }

    /** @test */
    public function edit_cancel_does_not_delete_any_image_if_new_image_is_not_selected()
    {
        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        Storage::fake('public');

        $shop = Shop::factory()->create([
            'img_url' => 'public/images/original.jpg',
        ]);
        $shop->users()->attach($shopRep->id);

        session(['form_input' => [
            'area_id' => $this->area->id,
            'genre_id' => $this->genre->id,
            'description' => 'Original description',
        ]]);

        $response = $this->post(route('cancel', ['id' => $shop->id]));

        $response->assertRedirect(route('repIndex'));
        $response->assertSessionMissing('form_input');

        $this->assertDatabaseHas('shops', [
            'id' => $shop->id,
            'img_url' => 'public/images/original.jpg',
        ]);
    }

    /** @test */
    public function it_displays_today_reservations_when_num_is_zero()
    {
        // テスト時の現在時刻を固定化
        $fixedDate = Carbon::parse('2025-05-16');
        Carbon::setTestNow($fixedDate);

        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $shop = Shop::factory()->create();
        $shop->users()->attach($shopRep->id);

        // 今日の予約データ作成
        $todayReservation = Reservation::factory()->create([
            'shop_id' => $shop->id,
            'date' => $fixedDate->toDateString(),
        ]);

        // 昨日の予約データ（取得されない）
        Reservation::factory()->create([
            'shop_id' => $shop->id,
            'date' => $fixedDate->copy()->subDay()->toDateString(),
        ]);

        // 明日の予約データ（取得されない）
        Reservation::factory()->create([
            'shop_id' => $shop->id,
            'date' => $fixedDate->copy()->addDays()->toDateString(),  // 再設定しないとそのまま昨日の日付になる
        ]);

        $response = $this->get(route('getReservation', ['num' => 0]));

        $response->assertStatus(200);
        $response->assertViewHas('reservations', function ($reservations) use ($todayReservation) {
            return $reservations->contains($todayReservation);
        });

        // テスト終了後にCarbonの設定をリセット
        Carbon::setTestNow();
    }

    /** @test */
    public function it_displays_tomorrow_reservations_when_num_is_one()
    {
        $fixedDate = Carbon::parse('2025-05-16');
        Carbon::setTestNow($fixedDate);

        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $shop = Shop::factory()->create();
        $shop->users()->attach($shopRep->id);

        // 明日の予約データ作成
        $tomorrowReservation = Reservation::factory()->create([
            'shop_id' => $shop->id,
            'date' => $fixedDate->copy()->addDay()->toDateString(),
        ]);

        $response = $this->get(route('getReservation', ['num' => 1]));

        $response->assertStatus(200);
        $response->assertViewHas('reservations', function ($reservations) use ($tomorrowReservation) {
            return $reservations->contains($tomorrowReservation);
        });

        Carbon::setTestNow();
    }

    /** @test */
    public function it_displays_yesterday_reservations_when_num_is_minus_one()
    {
        $fixedDate = Carbon::parse('2025-05-16');
        Carbon::setTestNow($fixedDate);

        $shopRepRole = RoleFactory::new()->create(['name' => 'shop_rep']);

        /** @var \App\Models\User $shopRep */
        $shopRep = User::factory()->create();
        $shopRep->assignRole($shopRepRole);

        $this->actingAs($shopRep);

        $shop = Shop::factory()->create();
        $shop->users()->attach($shopRep->id);

        // 昨日の予約データ作成
        $yesterdayReservation = Reservation::factory()->create([
            'shop_id' => $shop->id,
            'date' => $fixedDate->copy()->subDay()->toDateString(),
        ]);

        $response = $this->get(route('getReservation', ['num' => -1]));

        $response->assertStatus(200);
        $response->assertViewHas('reservations', function ($reservations) use ($yesterdayReservation) {
            return $reservations->contains($yesterdayReservation);
        });

        Carbon::setTestNow();
    }
}