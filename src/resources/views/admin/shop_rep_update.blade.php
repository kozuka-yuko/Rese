@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/shop_rep_update.css') }}">
@endsection

@section('content')
<div class="update__content">
    <form action="{{ route('updateConfirm', $shopRep->id) }}" class="update__form" method="post">
        @csrf
        <label for="shop-name" class="label__inner">店舗名:</label>
        <p class="shop-name__inner">{{ optional($shopRep->shops->first())->name }}</p>
        <label for="shop_rep_name" class="label__inner">店舗代表者:</label>
        <input type="text" class="info" name="name" value="{{ old('name', $shopRep->name) }}" />
        <div class="form__error">
            @error('name')
            {{ $message }}
            @enderror
        </div>
        <label for="email" class="label__inner">メールアドレス:</label>
        <input type="email" class="info" name="email" value="{{ old('email', $shopRep->email) }}" />
        <div class="form__error">
            @error('email')
            {{ $message }}
            @enderror
        </div>
        <a href="{{ route('adIndex') }}" class="back__btn">戻る</a>
        <button class="form__confirm" type="submit">入力内容の確認</button>
    </form>
</div>
@endsection