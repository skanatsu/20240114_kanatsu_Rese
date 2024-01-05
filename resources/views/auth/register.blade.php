<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/register.css">
    <title>会員登録</title>
</head>

<body>
    <header class="header">
        <p class="header__logo">Atte</p>
    </header>
    <div class="main">
        <div class="register">
            <h1 class="register__title">
                会員登録
            </h1>

        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <x-input-label for="name" :value="__()" />
                <x-text-input id="name" class="block mt-1 w-full nameinput" placeholder="名前" type="text"
                    name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 validation" />
            </div>


            <div class="mt-4">
                <x-input-label for="email" :value="__()" />
                <x-text-input id="email" class="block mt-1 w-full emailinput" placeholder="メールアドレス" type="email"
                    name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 validation" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__()" />

                <x-text-input id="password" class="block mt-1 w-full passwordinput" placeholder="パスワード" type="password"
                    name="password" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2 validation" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__()" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full passwordinput" placeholder="確認用パスワード"
                    type="password" name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 validation" />
            </div>

            <x-primary-button class="ms-4 register__button">

                {{ __('会員登録') }}
            </x-primary-button>

        </form>

        <div class="registered">
            <p class="registered__message">
                アカウントをお持ちの方はこちらから
            </p>
            <a href="http://localhost/login" class="registered__link">ログイン</a>
        </div>
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
