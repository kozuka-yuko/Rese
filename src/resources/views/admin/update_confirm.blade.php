@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/update_confirm.css') }}">
@endsection

@section('content')
<h1 class="title">入力内容の確認</h1>
<form action="{{ route('repUpdate', $shopRep->id) }}" class="confirm__form" method="post">
    @method('PATCH')
    @csrf
    <div class="form-data">
        <label for="shop_name" class="label__inner">店舗名</label>
        <span class="data">{{ $shopRep->shops->first()->name }}</span>
    </div>
    <div class="form-data">
        <label for="name" class="label__inner">店舗代表者氏名</label>
        <span class="data">{{ $data['name'] }}</span>
    </div>
    <div class="form-data">
        <label for="email" class="label__inner">メールアドレス</label>
        <span class="data">{{ $data['email'] }}</span>
    </div>
    <div class="button">
        <a href="#" onclick="history.back()" class="back__btn">訂正する</a>
        <button class="data__send--button" type="submit">登録</button>
    </div>
</form>
@endsection