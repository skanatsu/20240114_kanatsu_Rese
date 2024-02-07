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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reservation.store', ['shopId' => $shop->id]) }}">
                @csrf



                <input type="date" id="date" class="reservation__date" name="date"
                    value="{{ session('reservation.date') }}" onchange="updateTable()">
                <select id="time" class="reservation__time" name="time">
                    <?php
                    // 30分ごとのオプションを生成
                    for ($hour = 0; $hour < 24; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += 30) {
                            $timeValue = sprintf('%02d:%02d', $hour, $minute);
                            $selected = $timeValue === session('reservation.time') ? 'selected' : '';
                            echo "<option value=\"$timeValue\" $selected>$timeValue</option>";
                        }
                    }
                    ?>
                </select>
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

        <h3 class="review">お客様の声</h3>
        <table>
    <thead>
        <tr>
            <th>評価</th>
            <th>コメント</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reviews as $review)
        <tr>
            <td>{{ $review->score }}</td>
            <td>{{ $review->comment }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

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

        function submitForm() {
            var form = document.getElementById('reservationForm');

            // フォームのデータを取得
            var formData = new FormData(form);

            // Ajaxでフォームを送信
            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // 成功時の処理（例: リダイレクト）
                        window.location.href = data.redirect;
                    } else {
                        // 失敗時の処理（例: エラーメッセージの表示）
                        displayErrorMessages(data.errors);
                    }
                })
                .catch(error => {
                    console.error('予約時にエラーが発生しました', error);
                });
        }

        function displayErrorMessages(errors) {
            // 以前のエラーメッセージをクリア
            var errorContainer = document.getElementById('errorMessages');
            errorContainer.innerHTML = '';

            // エラーメッセージを表示するためのリスト要素を作成
            var errorList = document.createElement('ul');

            for (var field in errors) {
                if (errors.hasOwnProperty(field)) {
                    for (var i = 0; i < errors[field].length; i++) {
                        var errorMessage = errors[field][i];
                        var errorListItem = document.createElement('li');
                        errorListItem.textContent = errorMessage;
                        errorList.appendChild(errorListItem);
                    }
                }
            }
            // リストをエラーコンテナに追加
            errorContainer.appendChild(errorList);
        }
    </script>
</body>

</html>
