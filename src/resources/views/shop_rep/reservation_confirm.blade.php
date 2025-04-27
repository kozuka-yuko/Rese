@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/reservation_confirm.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="title">
        <a href="{{ route('repIndex') }}" class="return__btn" title="戻る">&lt;</a>
        <h2 class="title__inner">予約状況</h2>
    </div>
    <div class="content__heading">
        <a href="{{ '/shop_rep/reservation_confirm/' . ($num - 1) }}" class="prev-day">&lt;</a>
        <span class="current-date">{{ $fixed_date }}</span>
        <a href="{{ '/shop_rep/reservation_confirm/' . ($num + 1) }}" class="next-day">&gt;</a>
    </div>
    <table>
        <tr class="rese__row">
            <th class="rese__inner">お名前</th>
            <th class="rese__inner">ご予約時間</th>
            <th class="rese__inner">ご予約人数</th>
        </tr>
        @foreach ($reservations as $reservation)
        <tr class="rese__row">
            <td class="rese__inner">{{ $reservation->user->name }}</td>
            <td class="rese__inner">{{ $reservation->time }}</td>
            <td class="rese__inner">{{ $reservation->number }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection