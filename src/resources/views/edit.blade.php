@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="update">
    <form action="{{ route('reservationUpdate', $reservation->id) }}" method="post" class="reservation-form">
        @method('PATCH')
        @csrf
        <a href="{{ route('mypage') }}" class="close__update" title="閉じる">&times;</a>
        <table class="reservation__table">
            <tr class="table__row">
                <td class="reservation__item">Shop</td>
                <td class="reservation__data">{{ $reservation->shop->name }}</td>
            </tr>
            <tr class="table__row">
                <td class="reservation__item">Date</td>
                <td class="reservation__data">
                    <input type="date" name="date" class="reservation__date" value="{{ old('date', $reservation->date) }}" min="{{ $today }}" required>
                </td>
            </tr>
            <tr class="table__row">
                <td class="reservation__item">Time</td>
                <td class="reservation__data">
                    <select name="time" class="reservation__time" required>
                        <option value="" hidden>time</option>
                        @foreach ($times as $time)
                        <option value="{{ $time }}">{{ $time }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr class="table__row">
                <td class="reservation__item">Number</td>
                <td class="reservation__data">
                    <select name="number" class="number__inner" required>
                        <option value="" hidden>number of person</option>
                        @foreach ($numbers as $number)
                        <option value="{{ $number }}">{{ $number }}人</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
        <div class="reservation__button">
            <button class="update__btn" type="submit">変更する</button>
        </div>
    </form>
</div>
@endsection