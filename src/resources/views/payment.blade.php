@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class="content">
    <form action="" class="payment">
        <input type="hidden" id="shop_id" class="payment__inner" value="{{ $shop->id }}">
        <input type="number" id="amount" class="payment__inner" placeholder="金額を入力してください">
        <div class="form__error">
            @error('amount')
            {{ $message }}
            @enderror
        </div>
        <button class="checkout-button">決済をする</button>
        <a href="{{ route('mypage') }}" class="return__btn" title="戻る">戻る</a>
    </form>

    <script>
        const stripe = Stripe('{{ env('
            STRIPE_PUBLIC_KEY ') }}');

        document.getElementById('checkout-button').addEventListener('click', async () => {
            const shopId = document.getElementById('shop_id').value;
            const amount = document.getElementById('amount').value;

            if (!amount) {
                alert('金額を入力してください');
                return;
            }
            const response = await fetch('/create-checkout-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    shop_id: shopId,
                    amount: amount
                }),
            });

            const session = await response.json();

            if (session.id) {
                stripe.redirectToCheckout({
                    sessionId: session.id
                });
            } else {
                alert('決済に失敗しました')
            }
        });
    </script>
</div>
@endsection