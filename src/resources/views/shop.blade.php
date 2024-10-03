@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('search')
<form action="" method="">
    <div class="search">
        <select name="area" class="area">
            <option value="" hidden>All area</option>

        </select>
        <select name="genre" class="genre">
            <option value="" hidden>All genre</option>

        </select>
        <input type="text" class="search-form__input" name="name__input" placeholder="Search..." value="{{ old('name__input') }}" />
        <input type="submit" class="submit" />
    </div>
</form>
@endsection

@section('content')
<div class="shop__content">
    @foreach ($shops as $shop)
    <div class="shop">
        <div class="img">
            <img class="img_url" src="{{ asset($shop->img_url) }}" alt="お店の画像" />
        </div>
        <div class="info">
            <h3 class="shop__name">{{ $shop->name }}</h3>
            <p class="tag">#{{ $shop->area->name ?? 'エリア不明' }} #{{ $shop->genre->name ?? 'ジャンル不明' }}</p>
        </div>
        <div class="button">
            <a href="" class="detail__button">詳しく見る</a>
        </div>
        <div class="favorite">
        </div>
    </div>
    @endforeach
</div>
@endsection