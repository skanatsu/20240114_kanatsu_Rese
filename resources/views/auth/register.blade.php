{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/register.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>会員登録ページ</title>
</head>

<body>
    {{-- <header class="header"> --}}
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

<div class="container">
    {{-- <div class="row justify-content-center"> --}}
        {{-- <div class="col-md-8"> --}}
            {{-- <div class="card"> --}}
                <div class="register__form">

                <div class="form__title">Registration</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label> --}}
                            <div class="register__item">

                            <img src="{{ asset('images/username.png') }}" class="item__image">

                            <div class="col-md-6">
                                <input id="name" type="text" class="item__form @error('name') is-invalid @enderror" name="name" placeholder="Username" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            {{-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label> --}}
                            <div class="register__item">
                            
                            <img src="{{ asset('images/email.png') }}" class="item__image">

                            <div class="col-md-6">
                                <input id="email" type="email" class="item__form @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            {{-- <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label> --}}
                            <div class="register__item">

                            <img src="{{ asset('images/password.png') }}" class="item__image">

                            <div class="col-md-6">
                                <input id="password" type="password" class="item__form @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="item__form" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div> --}}

<div class="row mb-3">
    {{-- <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label> --}}

    <div class="col-md-6">
        <input id="password-confirm" type="password" class="item__form" name="password_confirmation" required autocomplete="new-password">
    </div>
</div>

</div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="register__button">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>

</div>

<style>
    /* 追加 */
    #password-confirm {
        display: none;
    }
</style>

<script>
    // パスワードフィールドに入力があるたびに、パスワード確認用のフィールドにも同じ値を入れる
    document.getElementById("password").addEventListener("input", function() {
        document.getElementById("password-confirm").value = this.value;
    });
</script>
</body>

</html>
{{-- @endsection --}}
