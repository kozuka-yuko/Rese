@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content__header">
        <h2 class="content__header--inner">
            {{ $user->name }}さん
        </h2>
    </div>
    @foreach ($reservations as $reservation)
    <div class="reservation">
        <p class="title">予約1</p>
        <form action="/delete" method="post" class="delete-form">
            @method('DELETE')
            @csrf
            <input type="hidden" name="id" value="{{ $reservation->id }}">
            <button class="delete-form__button-submit" type="submit" title="削除">&times;</button>
        </form>
        <table class="reservation__table">
            <tr class="table__row">
                <td class="reservation__data">Shop</td>
                <td class="reservation__data">{{ $reservation->shop->name }}</td>
            </tr>
            <tr class="table__row">
                <td class="reservation__data">Date</td>
                <td class="reservation__data">{{ $reservation->date }}</td>
            </tr>
            <tr class="table__row">
                <td class="reservation__data">Time</td>
                <td class="reservation__data">{{ $reservation->time }}</td>
            </tr>
            <tr class="table__row">
                <td class="reservation__data">Number</td>
                <td class="reservation__data">{{ $reservation->number }}人</td>
            </tr>
        </table>
    </div>
    @endforeach
    @foreach ($favorites as $favorite)
    <div class="favorite">
        <div class="shop">
            <div class="img">
                <img class="img_url" src="{{ asset($favorite->shop->img_url) }}" alt="お店の画像" />
            </div>
            <div class="info">
                <h3 class="shop__name">{{ $favorite->shop->name }}</h3>
                <p class="tag">#{{ $favorite->shop->area->name ?? 'エリア不明' }} #{{ $favorite->shop->genre->name ?? 'ジャンル不明' }}</p>
            </div>
            <div class="button">
                <a href="{{ url('detail?id=' . $favorite->shop->id) }}" class="detail__button">詳しく見る</a>
            </div>
            <div class="favorite">
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection