<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/login.css">
    <title>ログイン</title>
</head>

<body>
    <header class="header">
        <p class="header__logo">Atte</p>
    </header>
    <div class="main">
        <div class="login">
            <h1 class="login__title">
                ログイン
            </h1>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="email">
                <x-text-input id="email" class="block mt-1 w-full emailinput" placeholder="メールアドレス" type="email"
                    name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 validation" />
            </div>
            <div class="mt-4 password">
                <x-text-input id="password" class="block mt-1 w-full passwordinput" placeholder="パスワード" type="password"
                    name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 validation" />
            </div>
            <x-primary-button class="ms-3 login__button">
                {{ __('Log in') }}
            </x-primary-button>
        </form>
        <div class="noaccount">
            <p class="noaccount__message">
                アカウントをお持ちでない方はこちらから
            </p>
            <a href="http://localhost/register" class="noaccount__link">会員登録</a>
        </div>
    </div>
    <footer class="footer">
        <small>
            <p class="footer__copyright">
                Atte,Inc.
            </p>
        </small>
    </footer>
</body>
