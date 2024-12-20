<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Shop;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function showPayment($id)
    {
        $shop = Shop::findOrFail($id);
        
        return view('/payment', compact('shop'));
    }

    public function createCheckoutSession(PaymentRequest $request)
    {
        session(['shop_id' => $request->shop_id]);

        try{
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => '店舗ID: ' . $request->shop_id,
                        ],
                        'unit_amount' => $request->amount,
                    ],
                    'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('payment.success', ['session_id' => '{CHECKOUT_SESSION_ID}']),
                    'cancel_url' => route('payment.cancel'),
                ]);
                
                return response()->json(['id' => $checkoutSession->id]);
            } catch (ApiErrorException $e) {
                \Log::error('Stripe API Error:' . $e->getMessage());
                return response()->json(['error' => '決済処理に失敗しました。後ほど再試行してください。'], 500);
            }
    }

    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $session = Session::retrieve($request->query('session_id'));

            $shop_id = session('shop_id');
            if (!$shop_id) {
                throw new \Exception('shop_id is missing.');
            }

            if ($session->payment_status === 'paid') {
                Payment::create([
                    'user_id' => auth()->id(),
                    'shop_id' => $request->query('shop_id'),
                    'amount' => $session->amount_total / 100,
                    'status' => 'success',
                    'transaction_id' => $session->payment_intent,
                    'payment_method' => 'Stripe',
                ]);

                return view('/success');
            }
            return redirect('/mypage')->with('error','支払いに失敗しました');
        } catch (\Exception $e) {
            \Log::error('Error during payment success prosessing:' . $e->getMessage());
            return redirect('/mypage')->with('error', '決済処理中にエラーが発生しました');
        }
    }

    public function paymentCancel()
    {
        return view('/cancel');
    }
}
