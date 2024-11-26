@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/qr-code.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="qr-code">
        {!! $qrCode !!}
    </div>
    <div class="form">
        <form action="" class="qr-code__form" method="">
            @csrf
            <input type="hidden" name="reservation_id" class="reservation_id" value="{{ $qrData['reservation_id] }}">
            @if (session('result'))
            <div class="message__inner">
                {{ session('error') }}
            </div>
            @php
            session()->forget('error');
            @endphp
            @endif
            <button class="submit" type="submit">確認しました</button>
        </form>
    </div>
</div>
@endsection