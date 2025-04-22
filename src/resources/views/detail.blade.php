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
            <img class="img_url" src="{{ Storage::url($shop->img_url) }}" alt="お店の画像" />
        </div>
        <div class="tag">
            #{{ $shop->area->name ?? 'エリア不明' }} #{{ $shop->genre->name ?? 'ジャンル不明' }}
        </div>
        <div class="info">
            <p class="info__inner">{{ $shop->description }}</p>
        </div>
        <div class="link">
            <a href="{{ route('showReviewList', $shop->id) }}" class="review">レビューを見る</a>
        </div>
    </div>
    <form action="{{ route('reservation', $shop->id) }}" method="post" class="reservation-form">
        @csrf
        <div class="reservation__content">
            <div class="reservation__header">
                <h2 class="reservation__header--inner">予約</h2>
            </div>
            <div class="date">
                <input type="date" name="date" class="reservation__date" min="{{ $today }}" value="{{ old('date') }}">
            </div>
            <div class="form__error">
                @error('date')
                {{ $message }}
                @enderror
            </div>
            <div class="time">
                <select name="time" class="reservation__time">
                    <option value="" hidden>time</option>
                    @foreach ($times as $time)
                    <option value="{{ $time }}" {{ old('time') == $time ? 'selected' : '' }}>{{ $time }}</option>
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
                    <option value="{{ $number }}" {{ old('number') == $number ? 'selected' : '' }}>{{ $number }}人</option>
                    @endforeach
                </select>
            </div>
            <div class="form__error">
                @error('number')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="preview">
            <p id="preview__date"></p>
            <p id="preview__time"></p>
            <p id="preview__number"></p>
        </div>
        <div class="button-submit">
            <div class="reservation__button">
                <button class="reservation__btn" type="submit">予約する</button>
            </div>
        </div>
    </form>
</div>

<script>
    const dateInput = document.querySelector('input[name="date"]');
    const timeSelect = document.querySelector('select[name="time"]');
    const numberSelect = document.querySelector('select[name="number"]');

    const previewDate = document.getElementById('preview__date');
    const previewTime = document.getElementById('preview__time');
    const previewNumber = document.getElementById('preview__number');

    function updatePreview() {
        const date = dateInput.value;
        const time = timeSelect.value;
        const number = numberSelect.value;

        previewDate.textContent = date ? `日付 : ${date}` : '';
        previewTime.textContent = time ? `時間 : ${time}` : '';
        previewNumber.textContent = number ? `人数 : ${number}人` : '';
    }

        dateInput.addEventListener('input', updatePreview);
        timeSelect.addEventListener('change', updatePreview);
        numberSelect.addEventListener('change', updatePreview);

        window.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection