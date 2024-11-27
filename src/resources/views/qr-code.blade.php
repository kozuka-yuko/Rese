@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/qr-code.css') }}">
@endsection

@section('content')
<div class="content">
    @if (session('error'))
    <div class="message__error">
        {{ session('error') }}
    </div>
    @endif
    <div class="qr-code">
        {!! $qrCode !!}
    </div>
    <div class="form">
        <a href="#" onclick="history.back()" class="return__btn" title="戻る">戻る</a>
        @if ($reservation->status === 'ご来店')
        <p class="check">来店済み</p>
        @else
        <form action="{{ route('confirmVisit') }}" class="qr-code__form" method="post">
            @csrf
            <input type="hidden" name="reservation_id" class="reservation_id" value="{{ $qrData['reservation_id'] }}">
            <button class="submit" type="submit" onclick='return confirm("来店済みにしますか？")'>確認しました</button>
        </form>
        @endif
    </div>
</div>
@endsection