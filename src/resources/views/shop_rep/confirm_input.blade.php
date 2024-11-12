@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/confirm.css') }}">
@endsection

@section('content')
<div class="confirm-content">
    <form action="{{ route('shopUpdate') }}" class="confirm" method="post">
        @method('PATCH')
        @csrf
        <p class="shop_name">{{ $shop->name }}</p>
        <div class="img">
            <label for="" class="edit-label">ShopImage</label>
            <img class="image" src="{{ Storage::url($data['image']) }}" alt="お店の画像" />
        </div>
        <div class="tag">
            <label for="" class="edit-label">Area</label>
            <p class="area">#{{ $data['area'] }}</p>
            <label for="" class="edit-label">Genre</label>
            <p class="genre">#{{ $data['genre'] }}</p>
        </div>
        <div class="description">
            <label for="" class="edit-label">ShopInfo</label>
            <p class="description__inner">{{ $data['description'] }}</p>
        </div>
        <button class="submit" type="submit">登録</button>
    </form>
    <form action="" class="cancel" method="post">
        <button class="cancel__button" type="submit">キャンセル</button>
    </form>
</div>
@endsection