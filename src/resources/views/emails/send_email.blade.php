@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/emails/send_email.css') }}">
@endsection

@section('content')
<form method="POST" action="{{ route('sendEmail') }}">
    @csrf
    <label for="groupType">送信先を選択:</label>
    <select name="groupType" id="groupType" required>
        <option value="general">一般ユーザー</option>
        <option value="shop_rep">店舗代表者</option>
    </select>
    <input type="text" name="title" placeholder="件名" required>
    <textarea name="body" placeholder="本文" required></textarea>
    <button type="submit">送信</button>
</form>
@endsection