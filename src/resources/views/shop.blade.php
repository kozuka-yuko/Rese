@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('search')
<form class="search-form" action="route('search')" method="get">
    <div class="search">
        <select name="area_id" class="area">
            <option value="" hidden>All area</option>
            @foreach ($areas as $area)
            <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
        <select name="genre_id" class="genre">
            <option value="" hidden>All genre</option>
            @foreach ($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach
        </select>
        <input type="text" class="search-form__input" name="name_input" placeholder="Search..." value="{{ old('name_input') }}" />
    </div>
</form>

<script>
    function handleEnterKey(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.querySelector('.search-form').submit();
        }
    }
    document.querySelector('.area').addEventListener('keydown', handleEnterKey);
    document.querySelector('.genre').addEventListener('keydown', handleEnterKey);
    document.querySelector('.search-form__input').addEventListener('keydown', handleEnterKey);
</script>
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
            <div class="detail">
                <a href="{{ url('detail?id=' . $shop->id) }}" class="detail__button">詳しく見る</a>
            </div>
            <div class="favorite">
                <form class="favorite-form" action="route('favorite')" method="post">
                    @csrf
                    <button type="submit" class="favorite__btn {{ $shop->favorites->contains('user_id', auth()->id()) ? 'btn-favorite-active' : 'btn-favorite-inactive' }}">
                        <div class="heart"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection