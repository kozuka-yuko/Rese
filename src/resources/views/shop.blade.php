@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('search')
<form class="search-form" action="/search" method="get">
    <div class="search">
        <select name="area_id" class="area">
            <option value="" hidden>All area</option>
            @foreach ($areas as $area)
            <option value="{{ $area['id'] }}">{{ $area['name'] }}</option>
            @endforeach
        </select>
        <select name="genre_id" class="genre">
            <option value="" hidden>All genre</option>
            @foreach ($genres as $genre)
            <option value="{{ $genre['id'] }}">{{ $genre['name'] }}</option>
            @endforeach
        </select>
        <input type="text" class="search-form__input" name="name_input" placeholder="Search..." value="{{ old('name_input') }}" />
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
            <a href="{{ url('detail?id=' . $shop->id) }}" class="detail__button">詳しく見る</a>
        </div>
        <div class="favorite">
        </div>
    </div>
    @endforeach
</div>
@endsection