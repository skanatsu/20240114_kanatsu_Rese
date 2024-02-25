<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href={{ asset('css/detail.css') }}>
    <title>{{ $shop->shopname }}</title>
</head>

<body>

    <div class="detail__content">
        <div class="detail__content__shop">
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
            <div class="shop__detail__shopname">
                <a href="javascript:history.back()">
                    <img src="{{ asset('images/back.png') }}" alt="戻る">
                </a>
                <div class="shop-details">
                    <h2 id="shopName" class="shop__detail__title">{{ $shop->shopname }}</h2>
                </div>
            </div>
            <img src="{{ asset($shop->image_url) }}" class="shop__image" alt="{{ $shop->shopname }}">
            <div class="shop__tag">
                <p class="shop__area">#{{ $shop->area }}</p>
                <p>#{{ $shop->genre }}</p>
            </div>
            <p class="shop__description">{{ $shop->description }}</p>
            <div class="review">
                <h3 class="review__title">お客様の声</h3>
                <table class="review__table">
                    <thead>
                        <tr>
                            <th class="review__score">評価スコア</th>
                            <th class="review__comment">評価コメント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td class="review__score">{{ $review->score }}</td>
                                <td class="review__comment">{{ $review->comment }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @auth
            </div>
            <div class="detail__content__reservation">
                <h3 class="reservation">予約</h3>
                <form method="POST" action="{{ route('reservation.store', ['shopId' => $shop->id]) }}">
                    @csrf
                    <input type="date" id="date" class="reservation__date" name="date"
                        value="{{ session('reservation.date') }}" onchange="updateTable()">
                    <select id="time" class="reservation__time" name="time">
                        <?php
                        for ($hour = 0; $hour < 24; $hour++) {
                            for ($minute = 0; $minute < 60; $minute += 30) {
                                $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                $selected = $timeValue === session('reservation.time') ? 'selected' : '';
                                echo "<option value=\"$timeValue\" $selected>$timeValue</option>";
                            }
                        }
                        ?>
                    </select>
                    <select id="people" name="people" class="reservation__number" onchange="updateTable()">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?>人</option>
                        <?php endfor; ?>
                    </select>
                    <table class="reservation__check">
                        <tr class="reservation__item">
                            <th>Shop</th>
                            <td id="shopNameCell" class="reservation__data">{{ $shop->shopname }}</td>
                        </tr>
                        <tr class="reservation__item">
                            <th>Date</th>
                            <td id="reservationDate">{{ session('reservation.date') }}</td>
                        </tr>
                        <tr class="reservation__item">
                            <th>Time</th>
                            <td id="reservationTime">{{ session('reservation.time') }}</td>
                        </tr>
                        <tr class="reservation__item">
                            <th>Number</th>
                            <td id="reservationPeople">{{ session('reservation.people') }}人</td>
                        </tr>
                    </table>
                    <button type="submit" class="reservation__button">
                        予約する
                    </button>
                </form>
            @endauth
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <script>
        var initialShopName = "{{ $shop->shopname }}";
        document.addEventListener('DOMContentLoaded', function() {
            var reservationTime = document.getElementById('time');
            reservationTime.addEventListener('change', updateTable);
            updateTable();
        });

        function updateTable() {
            var shopNameCell = document.getElementById('shopNameCell');
            var reservationDate = document.getElementById('reservationDate');
            var reservationTime = document.getElementById('reservationTime');
            var reservationPeople = document.getElementById('reservationPeople');
            shopNameCell.innerHTML = initialShopName;
            reservationDate.innerHTML = document.getElementById('date').value;
            reservationTime.innerHTML = document.getElementById('time').value;
            reservationPeople.innerHTML = document.getElementById('people').value + "人";
        }

        function submitForm() {
            var form = document.getElementById('reservationForm');
            var formData = new FormData(form);
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
                        window.location.href = data.redirect;
                    } else {
                        displayErrorMessages(data.errors);
                    }
                })
                .catch(error => {
                    console.error('予約時にエラーが発生しました', error);
                });
        }

        function displayErrorMessages(errors) {
            var errorContainer = document.getElementById('errorMessages');
            errorContainer.innerHTML = '';
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
            errorContainer.appendChild(errorList);
        }
    </script>
</body>

</html>
