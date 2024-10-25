@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="detail-content">
        <div class="shop-name">
            <a href="{{ route('home') }}" class="return__btn" title="戻る">&lt;</a>
            <span class="shop-name__inner">{{ $shop->name }}</span>
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
    <form action="{{ route('reservation', $shop->id) }}" method="post" class="reservation-form">
        @csrf
        <div class="reservation__content">
            <div class="reservation__header">
                <h2 class="reservation__header--inner">予約</h2>
            </div>
            <div class="date">
                <input type="date" name="date" class="reservation__date" min="{{ $today }}">
            </div>
            @error('date')
            {{ $message }}
            @enderror
            <div class="time">
                <select name="time" class="reservation__time">
                    <option value="" hidden>time</option>
                    @foreach ($times as $time)
                    <option value="{{ $time }}">{{ $time }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form__error">
                @error('time')
                {{ $message }}
                @enderror
            </div>
            <div class="number">
                <select name="number" class="number__inner">
                    <option value="" hidden>number of person</option>
                    @foreach ($numbers as $number)
                    <option value="{{ $number }}">{{ $number }}人</option>
                    @endforeach
                </select>
            </div>
            <div class="form__error">
                @error('number')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="button-submit">
            <div class="reservation__button">
                <button class="reservation__btn" type="submit">予約する</button>
            </div>
        </div>
    </form>
</div>
@endsection