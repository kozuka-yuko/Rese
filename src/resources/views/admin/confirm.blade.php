@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<h1 class="title">入力内容の確認</h1>
<form action="" class="confirm__form" method="">
    @csrf
    <label for="shop-name" class="label__inner">店舗名</label>
    <p class="data">{{ $formInput['shop-name'] }}</p>
    <label for="name" class="label__inner">店舗代表者氏名</label>
    <p class="data">{{ $formInput['sho_rep_name'] }}</p>
    <label for="phone-number" class="label__inner">連絡先</label>
    <p class="data">{{ $formInput['phone-number'] }}</p>
    <div class="button">
        <a href="#" onclick="history.back()" class="return__btn">訂正する</a>
        <button class="data__send--button" type="submit">登録</button>
    </div>
</form>
@endsection