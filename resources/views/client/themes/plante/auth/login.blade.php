@extends('layouts.app')

@php
    Seo::setDefault(['title' => 'Вхід']);
@endphp

@section('content')
    <main>

        <section class="sign-in container">
            <form  action="{{ url('login') }}" method="POST" class="main-form">
                @csrf
                @honeypot
                <h1 class="title">Вхід</h1>
                <div class="main-form__input @error('email') main-form__input--error @enderror">
                    <label for="user[email]" class="main-form__text modal__form-text">Електронна пошта</label>
                    <input
                        name="email"
                        id="user[email]"
                        type="text"
                        class="main-input main-input--gray"
                        autocomplete="off"
                    />
                    @error('email') <span class="main-form__error-msg"> {{ $message }} </span> @enderror
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
                    @error('password') <span class="main-form__error-msg"> {{ $message }} </span> @enderror
                </div>
                <div class="modal__password-manage">
                    <label
                        for="user[remember]"
                        class="filter__select-label filter__select-label--modal filter__select-label--remember"
                    >
                        <input
                            name="brand"
                            id="user[remember]"
                            class="filter__select-input"
                            type="checkbox"
                            autocomplete="off"
                        />
                        <span class="filter__select-input-img">
                      <svg
                          class="filter__select-input-svg"
                          xmlns="http://www.w3.org/2000/svg"
                          width="25"
                          height="25"
                          viewBox="0 0 25 25"
                          fill="none"
                      >
                        <rect width="25" height="25" rx="5" fill="#2A8927" />
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z"
                            fill="#F5F5F5"
                        />
                      </svg>
                    </span>
                        Запам'ятати мене
                    </label>
                    <a
                        href=""
                        class="main-link main-text--semibold"
                        data-bs-target="#resetModal"
                        data-bs-toggle="modal"
                    >Забули пароль?</a
                    >
                </div>
                <button
                    class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold">
                    Увійти
                </button>
                <a
                    href="{{ route('register') }}"
                    class="main-btn main-btn--modal main-btn--light-gray main-btn--width100 main-text main-text--semibold">

                    <span class="main-form__link">Зареєструватися</span>
                </a>
            </form>
        </section>

    </main>
@endsection
