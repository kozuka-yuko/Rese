@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/emails/send_email.css') }}">
@endsection

@section('content')
<form class="form" method="POST" action="{{ route('sendEmail') }}">
    @csrf
    <div class="email__form">
        <label class="group-type" for="groupType">送信先を選択:</label>
        <select class="group-type" name="groupType" id="groupType" required>
            <option value="general">一般ユーザー</option>
            <option value="shop_rep">店舗代表者</option>
        </select>
    </div>
    <div class="email__form">
        <input class="title" type="text" name="title" placeholder="件名" required>
    </div>
    <div class="email__form">
        <textarea class="body" name="body" placeholder="本文" cols="100" rows="20" required></textarea>
    </div>
    <a href="#" onclick="history.back()" class="back__btn">戻る</a>
    <button class="send-email__button" type="submit">送信</button>
</form>
@endsection