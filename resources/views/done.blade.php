<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>予約完了</title>
</head>

<body>
    <header class="header">
        <a href="{{ url('menu') }}">
  <img src="{{ asset('images/menu.png') }}" alt="メニュー">
</a>
        <p class="header__logo">Rese</p>

    </header>
    ご予約ありがとうございます。
<button class="back" id="backButton">
    戻る
</button>
</body>

<script>
    // ボタン要素を取得
    var backButton = document.getElementById('backButton');

    // ボタンがクリックされたときの処理
    backButton.addEventListener('click', function() {
        // 指定のURLに遷移
        window.location.href = 'http://localhost/';
    });
</script>

</html>
