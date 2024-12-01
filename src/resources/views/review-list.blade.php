@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review-list.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
@endsection

@section('content')
<div class="content">
    <h2 class="title">{{ $shop->name }}のレビュー</h2>
    @if ($reviews->isEmpty())
    <p>まだレビューがありません</p>
    @else
    @foreach ($reviews as $review)
    <div class="review">
        <div class="stars">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <=$review->stars)
                <i class="fa-solid fa-star"></i>
                @else
                <i class="fa-regular fa-star"></i>
                @endif
                @endfor
        </div>
        <div class="comment">
            <p class="comment__inner">{{ $review->comment }}</p>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection