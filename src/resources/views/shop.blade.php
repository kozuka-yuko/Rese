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
    <div class="shop">
        @foreach ($shops as $shop)
        <div class="img">
            {{ $shop->img_url }}
        </div>
        <div class="info">
            <h3 class="shop__name">{{ $shop->name }}</h3>
            <p class="tag">#{{ $shops->area->name ?? 'エリア不明' }} #{{ $shops->genre->name ?? 'ジャンル不明' }}</p>
        </div>
        <div class="button">
            <a href="" class="detail__button">詳しく見る</a>
        </div>
        <div class="favorite">

        </div>
    </div>
</div>
@endsection