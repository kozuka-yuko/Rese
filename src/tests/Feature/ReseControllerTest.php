<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
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
    public function detail_page_display_correctly()
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

    /** @test */
    public function reservation_can_be_deleted()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $reservation = Reservation::factory()->create();

        $response = $this->delete('/reservation/delete', [
            'id' => $reservation->id,
        ]);

        $this->assertDatabaseMissing('reservations', [
            'id' => $reservation->id,
        ]);

        $response->assertRedirect('mypage');
        $response->assertSessionHas('result', '予約を削除しました');
    }

    /** @test */
    public function displays_edit_view_with_data()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $reservation = Reservation::factory()->create();

        $response = $this->get("/edit/{$reservation->id}");

        $response->assertStatus(200);
        $response->assertViewIs('edit');
        $response->assertViewHasAll([
            'reservation',
            'today',
            'times',
            'numbers'
        ]);
    }

    /** @test */
    public function edit_with_invalid_returns_404()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get("/edit/999");
        $response->assertStatus(404);
    }

    /** @test */
    public function reservation_can_be_updated()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'number' => 2,
        ]);

        $updateData = [
            'number' => 4,
            'date' => now()->addDays(2)->format('Y-m-d'),
            'time' => '14:00:00',
        ];

        $response = $this->patch("/reservation/{$reservation->id}", $updateData);

        $response->assertRedirect(route('mypage'));
        $response->assertSessionHas('result', '予約を変更しました');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'user_id' => $user->id,
            'shop_id' => $reservation->shop_id,
            'date' => $updateData['date'],
            'time' => $updateData['time'],
            'number' => $updateData['number'],
            'status' => $reservation->status,
        ]);
    }

    /** @test */
    public function reservation_update_fails_when_data_is_missing()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->patch("/reservation/{$reservation->id}", [
            'number' => '',
            'date' => '',
            'time' => '',
        ]);

        $response->assertSessionHasErrors(['number', 'date', 'time']);
    }

    /** @test */
    public function favorite_can_be_deleted()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $favorite = Favorite::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->delete('/favorite/delete', ['id' => $favorite->id,
    ]);

    $this->assertDatabaseMissing('favorites', ['id' => $favorite->id,
]);

    $response->assertRedirect('mypage');
    $response->assertSessionHas('result', 'お気に入りを削除しました');
    }

    /** @test */
    public function show_qr_code_generates_qr_code_for_valid_reservation()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $shop = Shop::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' =>$shop->id,
            'date' => '2025-05-10',
            'time' => '14:00:00',
            'number' => 4,
        ]);

        $response = $this->get(route('showQrCode', $reservation->id));

        $response->assertStatus(200);
        $response->assertViewIs('qr-code');
        $response->assertViewHas('qrData');

        $qrData = $response->viewData('qrData');
        
        $expectedData = [
            '店舗名' => $shop->name,
            '予約者名' => $user->name,
            '来店日' => '2025-05-10',
            '来店時間' => '14:00',
            '人数' => 4,
        ];

        $decryptedReservationId = Crypt::decryptString($qrData['reservation_id']);
        $this->assertEquals($reservation->id, $decryptedReservationId);

        unset($qrData['reservation_id']);
        $this->assertEquals($expectedData, $qrData);
    }

    public function show_qr_code_returns_404_if_reservation_not_found()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/qrcode/9999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_confirm_visit()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $encryptedId = Crypt::encryptString($reservation->id);

        $response = $this->post('/qr-code/visit', ['reservation_id' => $encryptedId]);
        
        $response->assertStatus(302);
        $response->assertSessionHas('result', '来店確認が完了しました！');
    }

    /** @test */
    public function it_fails_to_confirm_visit_with_invalid_reservation_id()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $invalidId = Crypt::encryptString(99999);

        $response = $this->post('/qr-code/visit', ['reservation_id' => $invalidId]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', '来店確認が完了していません。');
    }

    /** @test */
    public function it_show_the_create_reviewpage()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $shop = Shop::factory()->create();

        $response = $this->get("/review/{$shop->id}");

        $response->assertStatus(200);
        $response->assertViewIs('review');
        $response->assertViewHas('shop');
    }

    /** @test */
    public function it_can_store_shop_review()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $shop = Shop::factory()->create();

        $reviewData = [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'stars' => 5,
            'comment' => '素晴らしいお店でした！',
        ];

        $response = $this->post('/review/store', $reviewData);
        $response->assertRedirect(route('showReviewList', $shop->id));
        $response->assertSessionHas('result', 'レビューを投稿しました');
    }

    /** @test */
    public function it_valid_store_shop_review()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $shop = Shop::factory()->create();

        $response = $this->post('/review/store', [
            'shop_id' => $shop->id,
            'stars' => '',
            'comment' => '',
        ]);
        
        $response->assertSessionHasErrors(['stars', 'comment']);
    }

    /** @test */
    public function it_show_the_review_list()
    {
        $shop = Shop::factory()->create();

        $response = $this->get("/review-list/{$shop->id}");

        $response->assertStatus(200);
        $response->assertViewIs('review-list');
        $response->assertViewHasAll(['reviews', 'shop']);
    }
}