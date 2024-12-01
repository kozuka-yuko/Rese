@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
@endsection

@section('content')
<form action="{{ route('store') }}" class="form" method="post">
    @csrf
    <h2 class="title">{{ $shop->name }}のレビュー</h2>
    <p class="stars">満足度</p>
    <div class="form-rating">
        <input type="radio" class="form-rating__input" id="star5" name="rating" value="5">
        <label for="star5" class="form-rating__label"><i class="fa-solid fa-star"></i></label>
        <input type="radio" class="form-rating__input" id="star4" name="rating" value="4">
        <label for="star4" class="form-rating__label"><i class="fa-solid fa-star"></i></label>
        <input type="radio" class="form-rating__input" id="star3" name="rating" value="3">
        <label for="star3" class="form-rating__label"><i class="fa-solid fa-star"></i></label>
        <input type="radio" class="form-rating__input" id="star2" name="rating" value="2">
        <label for="star2" class="form-rating__label"><i class="fa-solid fa-star"></i></label>
        <input type="radio" class="form-rating__input" id="star1" name="rating" value="1">
        <label for="star1" class="form-rating__label"><i class="fa-solid fa-star"></i></label>
    </div>
    <div class="form__error">
        @error('stars')
        {{ $message }}
        @enderror
    </div>
    <div class="comment">
        <textarea class="comment__inner" name="comment" id="comment" cols="50" rows="8"></textarea>
    </div>
    <div class="form__error">
        @error('comment')
        {{ $message }}
        @enderror
    </div>
    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
    <button class="submit" type="submit">送信</button>
</form>
@endsection