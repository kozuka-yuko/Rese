<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約のご確認 Rese</title>
    @section('css')
    <link rel="stylesheet" href="{{ asset('css/reminder.css') }}">
    @endsection
</head>

<body>
    <p>{{ $reservation->user->name }}様</p>

    <p>この度は当店をご利用いただき、誠にありがとうございます。</p>

    <p>ご予約いただいたお日にちになりましたので、お知らせいたします。</p>

    <h3>予約内容</h3>
    <ul>
        <li>予約日時 : {{ $reservation->date->format('Y年m月d日 H:i') }}</li>
        <li>場所 : {{ $reservation->shop->name }}</li>
    </ul>

    <p>ご予約の５分前にはお越しくださいますようお願い申し上げます。</p>

    <p>キャンセルや変更がある場合は、事前にご連絡ください。</p>

    <p>ご不明点等がございましたら、どうぞお気軽にお問い合わせください。</p>

    <p>ご来店お待ちしております。</p>

    <p>-------------------------------------<br>
        <br>
        Rese
    </p>


</body>

</html>