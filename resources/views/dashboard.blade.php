<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>飲食店一覧ページ</title>
</head>

<body>
    <header class="header">
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
        <div class="search">
            <select id="area" class="search__select" name="area" onchange="filterShops()">
                <option value="allarea">All area</option>
                <option value="東京都">東京都</option>
                <option value="大阪府">大阪府</option>
                <option value="福岡県">福岡県</option>
            </select>
            <p class="bar">|</p>
            <select id="genre" class="search__select" name="genre" onchange="filterShops()">
                <option value="allgenre">All genre</option>
                <option value="居酒屋">居酒屋</option>
                <option value="焼肉">焼肉</option>
                <option value="寿司">寿司</option>
                <option value="ラーメン">ラーメン</option>
                <option value="イタリアン">イタリアン</option>
            </select>
            <p class="bar">|</p>
            <input type="text" id="shopname" class="search__shopname" name="shopname" placeholder="Search..."
                onkeydown="searchOnEnter(event)">
        </div>
    </header>

    <div class="shops-container">
        @foreach ($shops as $shop)
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
                        class="heart" onclick="toggleImage(this, {{ $shop->id }})"
                        id="heartImage_{{ $loop->index }}">
                @endauth
            </div>
        @endforeach
    </div>

    <script>
        function filterShops() {
            var selectedArea = document.getElementById("area").value;
            var selectedGenre = document.getElementById("genre").value;
            var searchShopname = document.getElementById("shopname").value.toLowerCase();
            var shops = document.getElementsByClassName("shop");

            for (var i = 0; i < shops.length; i++) {
                var shop = shops[i];
                var area = shop.getAttribute("data-area");
                var genre = shop.getAttribute("data-genre");
                var shopname = shop.getAttribute("data-shopname").toLowerCase();
                if ((selectedArea === "allarea" || area === selectedArea) &&
                    (selectedGenre === "allgenre" || genre === selectedGenre) &&
                    shopname.includes(searchShopname)) {
                    shop.style.display = "block";
                } else {
                    shop.style.display = "none";
                }
            }
        }

        function searchOnEnter(event) {
            if (event.key === "Enter") {
                filterShops();
            }
        }

        function toggleImage(element) {
            var currentSrc = element.src;
            if (currentSrc.includes('greyheart.png')) {
                element.src = '{{ url('/images/heart.png') }}';
            } else {
                element.src = '{{ url('/images/greyheart.png') }}';
            }
        }

        document.getElementById('heartImage').addEventListener('click', toggleImage);

        function toggleImage(element, shopId) {
            var currentSrc = element.src;
            var newSrc = currentSrc.includes('greyheart.png') ? '{{ url('/images/heart.png') }}' :
                '{{ url('/images/greyheart.png') }}';
            element.src = newSrc;

            $.ajax({
                type: 'POST',
                url: '{{ route('shop.toggle-favorite', ['shopId' => '__SHOP_ID__']) }}'.replace('__SHOP_ID__',
                    shopId),
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>

</body>

</html>
