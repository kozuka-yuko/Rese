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
    <div class="mypage__content">
        <div class="reservation__content">
            <div class="title">予約状況</div>
            <div class="reservation__status">
                @foreach ($reservations as $reservation)
                <div class="reservation">
                    <p class="title__num">予約{{ $loop->iteration }}</p>
                    <form action="{{ route('reservationDestroy') }}" method="post" class="delete-reservation">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="id" value="{{ $reservation->id }}">
                        <button class="delete-reservation__button-submit" type="submit" title="削除" onclick='return confirm("{{ $reservation->shop->name }}の予約を削除しますか？")'>&times;</button>
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
                    <div class="update__button">
                        <a href="{{ route('edit',$reservation->id) }}" class="edit">変更</a>
                        <a href="{{ route('showQrCode', $reservation->id) }}" class="qr-code">QRコードを表示する</a>
                    </div>
                    <p class="qr-code__info">ご来店時に入口で店舗スタッフにQRコードを見せてください。</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="favorite__content">
            <div class="title">お気に入り店舗</div>
            <div class="favorite__status">
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
                            <div class="detail">
                                <a href="{{ route('detail', $favorite->shop->id) }}" class="detail__button">詳しく見る</a>
                            </div>
                            <div class="isfavorite">
                                <form action="{{ route('favoriteDestroy') }}" method="post" class="delete-favorite">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $favorite->id }}">
                                    <button class="favorite__btn" type="submit" title="削除" onclick='return confirm("{{ $favorite->shop->name }}をお気に入りから削除しますか？")'>
                                        <div class="heart"></div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection