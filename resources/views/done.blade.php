<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/complete.css">
    <title>予約完了</title>
</head>

<body>
    <div class="logo">
        <div class="logo_link">
            <a href="{{ url('menu') }}">
                <img src="{{ asset('images/menu.png') }}" class="menu_image" alt="メニュー">
            </a>
        </div>
        <div class="logo_title">
            <p class="header__logo">Rese</p>
        </div>
    </div>
    <div class="complete__card">
        <p class="compete__message">
            ご予約ありがとうございます。
        </p>
        <button class="back__button" id="backButton">
            戻る
        </button>
    </div>
</body>

<script>
    var backButton = document.getElementById('backButton');
    backButton.addEventListener('click', function() {
        window.location.href = 'http://localhost/';
    });
</script>

</html>
