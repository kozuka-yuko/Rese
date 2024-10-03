@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="detail-content">
        <div class="shop-name">
            <a href="{{ url()->previous() }}" class="return__btn">&lt;</a>
            <p class="shop-name__inner">{{ $shop->name }}</p>
        </div>
        <div class="img">
            <img class="img_url" src="{{ asset($shop->img_url) }}" alt="お店の画像" />
        </div>
        <div class="tag">
            #{{ $shop->area->name ?? 'エリア不明' }} #{{ $shop->genre->name ?? 'ジャンル不明' }}
        </div>
        <div class="info">
            <p class="info__inner">{{ $shop->info }}</p>
        </div>
    </div>
    <div class="reservation">
        <div class="reservation__header">
            <h2 class="reservation__header--inner">予約</h2>
        </div>
        <div class="date">
            <input type="date" class="reservation__date">
        </div>
        <div class="time">
            <select name="time" class="reservation__time">
                <option value="" hidden>time</option>
            </select>
        </div>
        <div class="number">
            <select name="number" class="number__inner">
                <option value="" hidden>number of person</option>
            </select>
        </div>
        <div class="reservation__button">
            <button class="reservation__btn" type="submit">予約する</button>
        </div>
    </div>
</div>
@endsection