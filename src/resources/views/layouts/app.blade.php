<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
</head>
<body>
    <div class="app">
        <header class="header">
            <div class="header__heading">
                <button class="menu">
                    ここはモーダル？
                </button>
                <h1 class="header__heading-inner">Rese</h1>
            </div>
            @yield('search')
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>