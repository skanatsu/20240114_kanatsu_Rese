<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/register.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>ログインページ</title>
</head>

<body>
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
        <div class="register__form">
            <div class="form__title">Login</div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="register__item">
                        <img src="{{ asset('images/email.png') }}" class="item__image">
                        <div class="col-md-6">
                            <input id="email" type="email" class="item__form @error('email') is-invalid @enderror"
                                name="email" placeholder="Email" value="{{ old('email') }}" required
                                autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="register__item">
                        <img src="{{ asset('images/password.png') }}" class="item__image">
                        <div class="col-md-6">
                            <input id="password" type="password"
                                class="item__form @error('password') is-invalid @enderror" name="password"
                                placeholder="Password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="login__button">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
