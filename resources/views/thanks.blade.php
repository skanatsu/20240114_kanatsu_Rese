<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>会員登録ありがとうございます</title>
</head>

<body>
    <header class="header">
        <a href="{{ url('menu') }}">
  <img src="{{ asset('images/menu.png') }}" alt="メニュー">
</a>
        <p class="header__logo">Rese</p>

    </header>
    会員登録ありがとうございます。
<button class="login" id="loginButton">
    ログインする
</button>
</body>

<script>
    // ボタン要素を取得
    var loginButton = document.getElementById('loginButton');

    // ボタンがクリックされたときの処理
    loginButton.addEventListener('click', function() {
        // ログインページに遷移
        window.location.href = 'http://localhost/login';
    });
</script>

</html>
