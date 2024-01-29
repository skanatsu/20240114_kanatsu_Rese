<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>飲食店一覧ページ</title>
</head>

<body>
    <header class="header">
        <p class="header__logo">Atte</p>
        <nav class="header__nav">
            <ul class="header__nav-menu">
                <li class="header__nav-menu-list"><a href="http://localhost/" class="header__nav-menu-list-link">ホーム</a>
                </li>
                <li class="header__nav-menu-list"><a href="http://localhost/attendance"
                        class="header__nav-menu-list-link">日付一覧</a></li>
                <li class="header__nav-menu-list"><a href="http://localhost/userlist"
                        class="header__nav-menu-list-link">ユーザー一覧</a></li>
                <li class="header__nav-menu-list">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="#" class="header__nav-menu-list-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                    </form>
                </li>
            </ul>
        </nav>
    </header>
    店舗詳細
    <div class="shop-details">
        <h2 id="shopName">{{ $shop->shopname }}</h2>
        <p>エリア: {{ $shop->area }}</p>
        <p>ジャンル: {{ $shop->genre }}</p>
        <p>{{ $shop->description }}</p>

@auth
<form method="POST" action="{{ route('reservation.store', ['shopId' => $shop->id]) }}">
    @csrf
    <input type="date" id="date" class="reservation__date" name="date" value="{{ session('reservation.date') }}"
        onchange="updateTable()">
    <input type="time" id="time" class="reservation__time" name="time" value="{{ session('reservation.time') }}"
        onchange="updateTable()">
    <select id="people" name="people" onchange="updateTable()">
        <?php for ($i = 1; $i <= 10; $i++): ?>
        <option value="<?= $i ?>"><?= $i ?>人</option>
        <?php endfor; ?>
    </select>
<table>
            <tr>
                <th>Shop</th>
                <td id="shopNameCell">{{ $shop->shopname }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td id="reservationDate">{{ session('reservation.date') }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td id="reservationTime">{{ session('reservation.time') }}</td>
            </tr>
            <tr>
                <th>Number</th>
                <td id="reservationPeople">{{ session('reservation.people') }}人</td>
            </tr>
        </table>
    <button type="submit" class="reservation"> <!-- ボタンをsubmitに変更 -->
        予約する
    </button>
</form>
@endauth
    </div>

    <script>
        // 初期値を保持
        var initialShopName = "{{ $shop->shopname }}";

        function updateTable() {
            // 入力された値を取得
            var shopNameCell = document.getElementById('shopNameCell');
            var reservationDate = document.getElementById('reservationDate');
            var reservationTime = document.getElementById('reservationTime');
            var reservationPeople = document.getElementById('reservationPeople');

            // 表示を更新 (初期のshopnameは変更しない)
            shopNameCell.innerHTML = initialShopName;
            reservationDate.innerHTML = document.getElementById('date').value;
            reservationTime.innerHTML = document.getElementById('time').value;
            reservationPeople.innerHTML = document.getElementById('people').value + "人";
        }
    </script>
</body>
</html>
