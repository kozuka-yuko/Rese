@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/shop_create_confirm.css') }}">
@endsection

@section('content')
<div class="confirm-content">
    <form action="{{ route('') }}" class="confirm" method="post">
        @csrf
        <p class="shop_name">{{ $data['name'] }}</p>
        <div class="img">
            <label for="" class="edit-label">ShopImage:</label>
            <img class="image" src="{{ Storage::url($data['img_url']) }}" alt="お店の画像" />
        </div>
        <div class="tag">
            <label for="" class="edit-label">Area:</label>
            <p class="area">#{{ $data['area'] }}</p>
            <label for="" class="edit-label">Genre:</label>
            <p class="genre">#{{ $data['genre'] }}</p>
        </div>
        <div class="description">
            <label for="" class="edit-label">ShopInfo:</label>
            <p class="description__inner">{{ $data['description'] }}</p>
        </div>
        <button class="submit" type="submit">登録</button>
    </form>
    <form action="{{ route('createCancel') }}" class="cancel" method="post">
        @csrf
        <button class="cancel__button" type="submit">キャンセル</button>
    </form>
</div>
@endsection