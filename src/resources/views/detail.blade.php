@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="detail-content">
        <div class="shop-name">
            <a href="{{ url()->previous() }}" class="return__btn">&lt;</a>
            <p class="shop-name__inner">仙人</p>
        </div>
        <div class="img">
            <a href="https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg" class="shop__img"></a>
        </div>
        <div class="tag">
            <p class="tag__inner">#東京都</p>
            <p class="tag__inner">#寿司</p>
        </div>
        <div class="info">
            <p class="info__inner">料理長厳選の食材から作る寿司を用いたコースをぜひお楽しみください。食材・味・価格、お客様の満足度を徹底的に追及したお店です。特別な日のお食事、ビジネス接待まで気軽に使用することができます。
            </p>
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
            <select name="time" id="" class="reservation__time">
                <option value=""></option>
            </select>
        </div>
        <div class="number">
            <select name="number" id="" class="number__inner">
                <option value=""></option>
            </select>
        </div>
        <div class="reservation-data">
            <div class="shop-data">
                <span>Shop</span>
                <span>仙人</span>
            </div>
            <div class="date-data">
                <span>Date</span>
                <span>2021-04-01</span>
            </div>
            <div class="time-data">
                <span>Time</span>
                <span>17:00</span>
            </div>
            <div class="number-data">
                <span>Number</span>
                <span>1人</span>
            </div>
            <div class="reservation__button">
                <button class="reservation__btn" type="submit">予約する</button>
            </div>
        </div>
    </div>
</div>
@endsection