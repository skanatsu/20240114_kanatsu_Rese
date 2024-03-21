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


<div class="vertical__line"></div>



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
                    <form id="reviewForm" action="{{ route('review.submit', ['shop_id' => $shop->id]) }}" method="POST" enctype="multipart/form-data">
                    
                        {{-- <form id="reviewForm" method="POST"> --}}
    @csrf
    <input type="hidden" name="score" id="reviewScore" value="1"> <!-- 評価スコアを保持するための隠しフィールド -->

                <h3 class="reservation">体験を評価してください</h3>

<img class="rating-star" src="{{ asset('images/bluestar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">
<img class="rating-star" src="{{ asset('images/greystar.png') }}" onmouseover="changeImages(this)" onmouseout="restoreImages(this)" onclick="saveClickedImage(this)">


{{-- @if ($review)
    <script>
        var score = {{ $review->score }};
        var imgs = document.getElementsByClassName("rating-star");
        for (var i = 0; i < score; i++) {
            imgs[i].src = "{{ asset('images/bluestar.png') }}";
        }
    </script>
@endif --}}

@if ($review)
    <script>
        var initialScore = {{ $review->score }}; // 既存の口コミがある場合はそのスコアを取得
        var imgs = document.getElementsByClassName("rating-star");
        for (var i = 0; i < initialScore; i++) {
            imgs[i].src = "{{ asset('images/bluestar.png') }}"; // スコアに基づいて評価の星の画像を設定
        }
        document.getElementById('reviewScore').value = initialScore; // フォームの隠しフィールドに評価をセット
    </script>
@endif


@php
    $score = $review ? $review->score : 1; // 既存の口コミがあればそのスコアを取得し、なければデフォルト値1を設定
@endphp



                <h3 class="review__comment">口コミを投稿</h3>
    {{-- <textarea name="comment" id="comment" rows="3" class="review__comment__input" placeholder="カジュアルな夜のお出かけにおすすめのスポット" oninput="countCharacters(this)"></textarea> --}}


<textarea name="comment" id="comment" rows="3" class="review__comment__input" placeholder="カジュアルな夜のお出かけにおすすめのスポット" oninput="toggleSubmitButton()"></textarea>

    <div id="character-count" class="character-count">0/400（最高文字数）</div>

    
    <!-- エラーメッセージを表示する要素 -->
                <div id="comment-error" class="error-message" style="color: red;"></div>

@if (isset($review))
<script>
document.getElementById('comment').value = '{{ $review->comment }}';
</script>
@endif

<div class="image__attach__div" data-review="{{ $review ? json_encode($review) : null }}">


                <h3 class="image__attach">画像の追加</h3>


<div role="button" tabindex="0" class="custom-file-upload" ondragover="handleDragOver(event)" ondrop="handleFileDrop(event)">
    <span>クリックして写真を追加<br>またはドラッグアンドドロップ</span>
</div>
<input type="file"  name="photo" id="photo" accept="image/*" onchange="previewPhoto(event)" class="image__upload__button" style="display: none;">

        <button id="clearPhotoButton" class="image__delete__button" onclick="clearPhoto(event)" style="display: none;">画像を削除</button>


        <div id="photo-preview" class="photo__preview"></div>

        {{-- <p id="review_image_name">{{ $review->review_image_url }}</p> --}}

@if (isset($review->review_image_url))
    <p id="review_image_name">{{ $review->review_image_url }}</p>
@endif

        @if (isset($review))
<script>
document.getElementById('review_image_url').value = '{{ $review->review_image_url }}';
</script>
@endif
        
        <div id="photo-error" class="error__message__image" style="color: red;"></div>


    <button type="submit" class="submit__button" id="submitReviewButton">口コミを投稿</button>

    

</form>

        </div>
    </div>

    <script>
        var initialShopName = "{{ $shop->shopname }}";
        document.addEventListener('DOMContentLoaded', function() {


            
    var commentTextarea = document.getElementById('comment');
    countCharacters(commentTextarea); // ページ読み込み時に文字数をカウントして表示
    commentTextarea.addEventListener('input', function() {
        countCharacters(this); // textareaに入力がある度に文字数をカウントして表示
    });
    
        });

        // function updateTable() {
        //     var shopNameCell = document.getElementById('shopNameCell');
        //     var reservationDate = document.getElementById('reservationDate');
        //     var reservationTime = document.getElementById('reservationTime');
        //     var reservationPeople = document.getElementById('reservationPeople');
        //     shopNameCell.innerHTML = initialShopName;
        //     reservationDate.innerHTML = document.getElementById('date').value;
        //     reservationTime.innerHTML = document.getElementById('time').value;
        //     reservationPeople.innerHTML = document.getElementById('people').value + "人";
        // }

     

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
    // var score = 1;
    var score = index + 1; // クリックされた画像の位置に応じて評価を設定
    document.getElementById('reviewScore').value = score; // 隠しフィールドに評価をセット

    
    for (var i = 0; i <= index; i++) {
        imgs[i].src = "{{ asset('images/bluestar.png') }}";
    }
}



function restoreImages(element) {
    if (element !== clickedImage) {
        var imgs = document.getElementsByClassName("rating-star");
        for (var i = 1; i < imgs.length; i++) {
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
    characterCountElement.textContent = count + "/400（最高文字数）";

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

        var photo = document.getElementById('photo');
        var clearPhotoButton = document.getElementById('clearPhotoButton');
        if (photo.files.length > 0) {
            // ファイルが選択されたらボタンを表示
            clearPhotoButton.style.display = 'block';
        } else {
            // ファイルが選択されていない場合はボタンを非表示
            clearPhotoButton.style.display = 'none';
          }





    var input = event.target;
    var file = input.files[0];
    var reader = new FileReader();

    reader.onload = function () {
        var photoPreview = document.getElementById('photo-preview');
        var img = document.createElement('img');
        img.src = reader.result;
        photoPreview.innerHTML = '';
        photoPreview.appendChild(img);
    };

    // ファイルの拡張子をチェック
    var allowedExtensions = ['jpg', 'jpeg', 'png'];
    var fileExtension = file.name.split('.').pop().toLowerCase();
    if (!allowedExtensions.includes(fileExtension)) {
        // 非対応の拡張子の場合、エラーメッセージを表示して処理を中断
        document.getElementById('photo-preview').innerHTML = '';
        document.getElementById('photo-error').innerText = '非対応のファイル形式です。jpegまたはpngのみアップロード可能です。';
        input.value = ''; // アップロードされたファイルをクリア
        return;
    } else {
        document.getElementById('photo-error').innerText = ''; // エラーメッセージをクリア
    }

    reader.readAsDataURL(file);
}


function clearPhoto(event) {
    event.preventDefault();
    // プレビュー用の要素を取得
    var photoPreview = document.getElementById('photo-preview');
    // プレビュー用の要素の中身をクリア
    photoPreview.innerHTML = '';
    // ファイルアップロード用のinput要素の値をクリア
    var fileInput = document.getElementById('photo');
    fileInput.value = '';
    // エラーメッセージをクリア
    document.getElementById('photo-error').innerText = '';
    // 画像を削除ボタンを非表示にする
    document.getElementById('clearPhotoButton').style.display = 'none';
}

//　アップロードボタン装飾
      const buttonElement = document.querySelector(".custom-file-upload");

    buttonElement.addEventListener("click", () => {
        document.querySelector("#photo").click();
    });

    // Space / Enter キーで click イベントを発火できるようにする
    buttonElement.addEventListener("keydown", (event) => {
        if (!buttonElement.isEqualNode(event.target)) {
            return;
        }

        if (event.keyCode === 32 || event.keyCode === 13) {
            event.preventDefault();
            document.querySelector("#photo").click();
        }
    });

    // ドラッグオーバー時のイベントハンドラ
    function handleDragOver(event) {
        event.preventDefault();
        event.stopPropagation();
    }

    ドロップ時のイベントハンドラ
    function handleFileDrop(event) {
        event.preventDefault();
        event.stopPropagation();

        const file = event.dataTransfer.files[0];
        if (file) {
            const inputElement = document.querySelector("#photo");
            inputElement.files = event.dataTransfer.files;
            inputElement.dispatchEvent(new Event("change"));
        }
    }






// <div role="button" tabindex="0" class="custom-file-upload" ondragover="handleDragOver(event)" ondrop="handleFileDrop(event)">
//     <span>クリックして写真を追加<br>またはドラッグアンドドロップ</span>
// </div>
// <input type="file"  name="photo" id="photo" accept="image/*" onchange="previewPhoto(event)" class="image__upload__button" style="display: none;">

//         <button id="clearPhotoButton"  class="image__delete__button" onclick="clearPhoto()">画像を削除</button>


    //  function previewPhoto(event) {
        // var photo = document.getElementById('photo');
        // var clearPhotoButton = document.getElementById('clearPhotoButton');
        // if (photo.files.length > 0) {
        //     // ファイルが選択されたらボタンを表示
        //     clearPhotoButton.style.display = 'block';
        // } else {
        //     // ファイルが選択されていない場合はボタンを非表示
        //     clearPhotoButton.style.display = 'none';
        //   }
    // }


    
    </script>

    
</body>

</html>