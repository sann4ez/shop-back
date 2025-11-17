@extends('layouts.app')

@php
    Seo::setDefault(['title' => 'Реєстрація']);
@endphp

@section('content')
    <main>
        <section class="sign-in container">
            <form class="main-form" method="POST" action="{{ route('register') }}">
                @csrf
                @honeypot
                <h1 class="title">Реєстрація</h1>
                <div class="main-form__input @error('name') main-form__input--error @enderror">
                    <label for="user[name]" class="main-form__text modal__form-text">Ім'я</label>
                    <input
                        name="name"
                        id="user[name]"
                        type="text"
                        class="main-input main-input--gray"
                        autocomplete="off"
                    />
                    @error('name') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="main-form__input @error('email') main-form__input--error @enderror">
                    <label for="user[email]" class="main-form__text modal__form-text">Електронна пошта</label>
                    <input
                        name="email"
                        id="user[email]"
                        type="email"
                        class="main-input main-input--gray"
                        autocomplete="off"
                    />
                    @error('email') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="main-form__input @error('password') main-form__input--error @enderror">
                    <label for="user[password]" class="main-form__text modal__form-text">Пароль</label>
                    <div class="modal__form-input-wrapper">
                        <input
                            name="password"
                            id="user[password]"
                            type="password"
                            class="main-input main-input--gray main-input--width100"
                            autocomplete="off"
                        />
                        <button type="button" class="modal__form-input-icon js-eye-btn">
                            <svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg>
                        </button>
                    </div>
                    @error('password') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                </div>
                <p class="agreement">“Відправляючи форму ви погоджуєтесь на <a href="{{ route('pages.show', ['page' => 'terms']) }}" class="main-link">Умови використання</a> та <a href="{{ route('pages.show', ['page' => 'policy']) }}" class="main-link">Політику конфіденційності</a>.”</p>
                <button
                    class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold">
                    Зареєструватись
                </button>
                <a
                    href="{{ route('login') }}"
                    class="main-btn main-btn--modal main-btn--light-gray main-btn--width100 main-text main-text--semibold">
                    <span class="main-form__link">У мене є аккаунт</span>
                </a>
            </form>
        </section>

    </main>
@endsection
