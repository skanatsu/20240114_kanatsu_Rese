<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href={{ asset('css/review.css') }}>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>{{ $shop->shopname }}の評価</title>
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

                <div class="shop-details">
                    <h2 class="shop__detail__title">今回のご利用はいかがでしたか？</h2>
                </div>
            </div>

            <div class="shop" data-area="{{ $shop->area }}" data-genre="{{ $shop->genre }}"
                data-shopname="{{ $shop->shopname }}">
                <img src="{{ asset($shop->image_url) }}" class="shop__image" alt="{{ $shop->shopname }}">
                <p class="shop__name">{{ $shop->shopname }}</p>
                <div class="shop__tag">
                    <p class="shop__area">#{{ $shop->area }}</p>
                    <p>#{{ $shop->genre }}</p>
                </div>
                <a href="{{ route('detail', ['id' => $shop->id]) }}" class="shop__detail">詳しくみる</a>
                @auth
                    <img src="{{ url('/images/' . ($shop->isFavorite ? 'heart.png' : 'greyheart.png')) }}" alt=""
                        class="heart" onclick="toggleImage(this, {{ $shop->id }})"">
                @endauth
            </div>

            @auth
            </div>
            <div class="review__form">
                <h3 class="reservation">体験を評価してください</h3>

<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
            @endauth
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


        // function changeImages(element) {
        //     var imgs = document.getElementsByClassName("rating-star");
        //     var index = Array.prototype.indexOf.call(imgs, element);
        //     for (var i = 0; i <= index; i++) {
        //         imgs[i].src = "{{ asset('images/bluestar.png') }}";
        //     }
        // }

        // function restoreImages(element) {
        //     var imgs = document.getElementsByClassName("rating-star");
        //     for (var i = 0; i < imgs.length; i++) {
        //         imgs[i].src = "{{ asset('images/greystar.png') }}";
        //     }
        // }

                var clickedImage = null;

        function changeImages(element) {
            var imgs = document.getElementsByClassName("rating-star");
            var index = Array.prototype.indexOf.call(imgs, element);
            for (var i = 0; i <= index; i++) {
                imgs[i].src = "{{ asset('images/bluestar.png') }}";
            }
        }

        function restoreImages(element) {
            if (element !== clickedImage) {
                var imgs = document.getElementsByClassName("rating-star");
                for (var i = 0; i < imgs.length; i++) {
                    imgs[i].src = "{{ asset('images/greystar.png') }}";
                }
            }
        }

        function saveClickedImage(element) {
            clickedImage = element;
        }
    </script>
</body>

</html>
