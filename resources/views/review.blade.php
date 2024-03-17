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


            </div>
            <div class="review__form">
                    <form id="reviewForm" action="{{ route('review.submit', ['shop_id' => $shop->id]) }}" method="POST">
                        {{-- <form id="reviewForm" method="POST"> --}}
    @csrf
    <input type="hidden" name="score" id="reviewScore" value="0"> <!-- 評価スコアを保持するための隠しフィールド -->

                <h3 class="reservation">体験を評価してください</h3>

<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">


{{-- 既存の口コミがある場合は、そのスコアに基づいて画像のマウスオーバー状態を設定する --}}
@if ($review)
    <script>
        var score = {{ $review->score }};
        var imgs = document.getElementsByClassName("rating-star");
        for (var i = 0; i < score; i++) {
            imgs[i].src = "{{ asset('images/bluestar.png') }}";
        }
    </script>
@endif



                <h3 class="reservation">口コミを投稿</h3>
    <textarea name="comment" id="comment" rows="3" class="review__comment" placeholder="カジュアルな夜のお出かけにおすすめのスポット" oninput="countCharacters(this)"></textarea>
    <div id="character-count">0/400（最高文字数）</div>

    <!-- エラーメッセージを表示する要素 -->
                <div id="comment-error" class="error-message" style="color: red;"></div>

@if (isset($review))
<script>
document.getElementById('comment').value = '{{ $review->comment }}';
</script>
@endif

<div class="review__form" data-review="{{ $review ? json_encode($review) : null }}">


                <h3 class="reservation">画像の追加</h3>



<input type="file" name="photo" id="photo" accept="image/*"  onchange="previewPhoto(event)" class="image__upload__button">



<label for="photo" class="custom-file-upload">
    <span>クリックして写真を選択またはドラッグ＆ドロップ</span>
</label>
        <div id="photo-preview"></div>

    <button type="submit" id="submitReviewButton">口コミを投稿</button>

</form>

        </div>
    </div>

    <script>
        var initialShopName = "{{ $shop->shopname }}";
        document.addEventListener('DOMContentLoaded', function() {
            var reservationTime = document.getElementById('time');
            reservationTime.addEventListener('change', updateTable);
            updateTable();

    var commentTextarea = document.getElementById('comment');
    countCharacters(commentTextarea); // ページ読み込み時に文字数をカウントして表示
    commentTextarea.addEventListener('input', function() {
        countCharacters(this); // textareaに入力がある度に文字数をカウントして表示
    });
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


                var clickedImage = null;

function changeImages(element) {
    var imgs = document.getElementsByClassName("rating-star");
    var index = Array.prototype.indexOf.call(imgs, element);
    var score = index + 1; // クリックされた画像の位置に応じて評価を設定
    document.getElementById('reviewScore').value = score; // 隠しフィールドに評価をセット
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

    var initialComment = document.getElementById('comment').value; // ページ読み込み時のtextareaの値を取得
    countCharacters(document.getElementById('comment')); // ページ読み込み時に文字数をカウントして表示

    // textareaに入力がある度に文字数をカウントして表示
    document.getElementById('comment').addEventListener('input', function() {
        countCharacters(this);
    });

                function countCharacters(element) {
     var text = element.value;
    var count = text.length;
    var characterCountElement = document.getElementById("character-count");
    characterCountElement.textContent = count + "/400";

                        // エラーメッセージの表示とボタンの無効化
            var errorMessageElement = document.getElementById("comment-error");
            var submitButton = document.getElementById("submitReviewButton");
            if (count > 400) {
                errorMessageElement.textContent = "400文字以内で入力してください";
                submitButton.disabled = true;
                submitButton.style.opacity = "0.5"; // ボタンを薄くする
            } else {
                errorMessageElement.textContent = "";
                submitButton.disabled = false;
                submitButton.style.opacity = "1"; // ボタンの透明度を元に戻す
            }
        }


function previewPhoto(event) {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function () {
        var photoPreview = document.getElementById('photo-preview');
        var img = document.createElement('img');
        img.src = reader.result;
        photoPreview.innerHTML = '';
        photoPreview.appendChild(img);
    };

    
    reader.readAsDataURL(input.files[0]);
}



document.getElementById('submitReviewButton').addEventListener('click', function (event) {
    // event.preventDefault(); 
    if (!isSubmitting) { // 送信中でない場合のみ処理を実行
        postReview(); // 口コミを投稿する関数を呼び出す
    }
});


        function postReview() {

                // 送信中フラグを立てる
    // isSubmitting = true;

    var comment = document.getElementById('comment').value; // コメントを取得
    var score = document.getElementById('reviewScore').value; // 評価スコアを取得

    // フォームデータを作成
    var formData = new FormData();
    formData.append('score', score); // 評価スコアを追加
    formData.append('comment', comment); // コメントを追加
    formData.append('shop_id', {{ $shop->id }});

    // フォームデータを送信するAjaxリクエストを作成
    fetch("{{ route('review.submit') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
    })
    .then(response => response.json())
 .then(data => {
    // 成功時の処理
    if (data.success) {
        // リダイレクト先の詳細ページ URL を構築してリダイレクト
        var shopId = "{{ $shop->id }}"; // Blade テンプレートからショップの ID を取得
        var redirectUrl = "{{ url('/detail/') }}" + '/' + shopId;
        window.location.href = redirectUrl;
    } else {
        // エラーメッセージを表示するなどの処理
        console.error('口コミの投稿に失敗しました:', data.message);
    }
})
    .catch(error => {
    console.error('口コミの投稿時にエラーが発生しました', error);
})

}

    </script>

    
</body>

</html>