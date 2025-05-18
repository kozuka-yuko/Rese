<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Stripe\Exception\InvalidRequestException;
use App\Models\User;
use App\Models\Shop;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     /** @test */
    public function show_payment_display_the_view()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);
        
        $shop = Shop::factory()->create();
        $response = $this->get("/payment/{$shop->id}");
        
        $response->assertStatus(200)->assertViewIs('payment')->assertViewHas('shop', $shop);
    }
    
    /** @test */
    public function show_payment_returns_404_if_shop_not_found()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
    
        $this->actingAs($user);

        $response = $this->get('/payment/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function create_checkout_session_returns_session_id_on_success()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $mockSession = Mockery::mock(Session::class);
        $mockSession->shouldReceive('create')
        ->once()
        ->andReturn((object)['id' => 'test_session_id']);

        $this->mock(Session::class, function ($mock) {
            $mock->shouldReceive('create')->andReturn((object)['id' => 'test_session_id']);
        });

        $shop = Shop::factory()->create();

        $response = $this->postJson('/checkout', [
            'shop_id' => $shop->id,
            'amount' => 1000,
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure(['id']);
    }

    /** @test */
    public function create_checkout_session_returns_error_on_failure()
    {
        $this->withoutExceptionHandling();
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $shop = Shop::factory()->create();

        $stripeMock = \Mockery::mock('overload:' . \Stripe\Checkout\Session::class);
        $stripeMock->shouldReceive('create')
        ->andThrow(new \Stripe\Exception\ApiErrorException('Stripe error'));

        Log::shouldReceive('error')->once();

        $response = $this->postJson('/checkout', [
            'shop_id' => $shop->id,
            'amount' => 1000,
        ]);

        $response->assertStatus(500)
        ->assertJson(['error' => '決済処理に失敗しました。後ほど再試行してください。']);
    }

    /** @test */
    public function payment_success_displays_success_view()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('paymentSuccess'));
        $response->assertStatus(200)
        ->assertViewIs('success');
    }

    /** @test */
    public function payment_cancel_displays_cancel_view()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('paymentCancel'));
        $response->assertStatus(200)
        ->assertViewIs('cancel');
    }
}
