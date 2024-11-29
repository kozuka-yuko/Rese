@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/shop.css') }}">
@endsection

@section('content')
<div class="detail-content">
    @if
    <a href="{{ route('shopCreate') }}" class="shop_create">新規店舗作成</a>
    @else
    <div class="shop__description">
        <div class="shop-name">
            <span class="shop-name__inner">{{ $shop->name }}</span>
        </div>
        <div class="img">
            <img class="img_url" src="{{ Storage::url($shop->img_url) }}" alt="Shop Image" />
        </div>
        <div class="tag">
            #{{ $shop->area->name ?? 'エリア不明' }} #{{ $shop->genre->name ?? 'ジャンル不明' }}
        </div>
        <div class="description">
            <p class="description__inner">{{ $shop->description }}</p>
        </div>
        <div class="edit">
            <a href="{{ route('shopEdit', $shop->id) }}" class="edit__button">変更</a>
            <form action="{{ route('shopDestroy', $shop->id) }}" method="post" class="delete__shop-rep">
                @method('DELETE')
                @csrf
                <button class="delete__shop--button" type="submit" title="削除" onclick='return confirm("{{ optional($shop)->name }}を削除しますか？")'>削除</button>
            </form>
        </div>
    </div>
    <div class="reservation__confirm">
        <a href="{{ route('getReservation') }}" class="reservation">予約確認</a>
    </div>
    @endif
</div>
@endsection