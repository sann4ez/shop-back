@extends('layouts.app')

@php
    Seo::setDefault(['title' => 'Особистий кабінет: Мої дані']);
@endphp

@section('content')
    <main>

        <section class="personal-data container">

            <div class="header-page header-page--personal">
                <h1 class="title">Особистий кабінет</h1>
                <a href="" class="main-link" data-bs-target="#logOut" data-bs-toggle="modal" aria-label="openModal">Вийти з акаунту</a>
            </div>

            <div class="personal-data__content">
                @include('my.inc.aside')

                <div class="personal-data__wrapper">
                    <form action="{{ route('my.profile.update') }}" method="POST" class="main-form main-form--personal js-submit-ajax">
                        @csrf
                        <div class="main-form__input main-form__input--personal @error('name') main-form__input--error @enderror">
                            <label for="profile-name" class="main-form__text main-text">Ім'я</label>
                            <input name="name"
                                   id="profile-name"
                                   value="{{ old('name', $user->name) }}"
                                   type="text"
                                   class="main-input main-input--width100 main-input--white"
                                   autocomplete="off"
                            >
                            @error('name') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                        </div>
                        <div class="main-form__input main-form__input--personal @error('lastname') main-form__input--error @enderror">
                            <label for="profile-lastname" class="main-form__text main-text">Прізвище</label>
                            <input name="lastname"
                                   id="lastname"
                                   value="{{ old('lastname', $user->lastname) }}"
                                   type="text"
                                   class="main-input main-input--width100 main-input--white"
                                   autocomplete="off"
                            >
                            @error('lastname') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                        </div>
                        <div class="main-form__input main-form__input--personal @error('email') main-form__input--error @enderror">
                            <label for="profile-email" class="main-form__text main-text">Email</label>
                            <input name="email"
                                   id="profile-email"
                                   value="{{ old('email', $user->email) }}"
                                   type="text"
                                   class="main-input main-input--width100 main-input--white"
                                   autocomplete="off"
                            >
                            @error('email') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                        </div>
                        <div class="main-form__input main-form__input--personal @error('phone') main-form__input--error @enderror">
                            <label for="profile-phone" class="main-form__text main-text">Телефон</label>
                            <input name="phone"
                                   id="profile-phone"
                                   value="+{{ old('phone', $user->phone) }}"
                                   type="tel"
                                   class="main-input main-input--width100 main-input--white"
                                   placeholder="+38 (099) 999-99-99"
                                   autocomplete="off"
                            >
                            @error('phone') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                        </div>
                        <a href="" class="main-link" data-bs-target="#changePassword" data-bs-toggle="modal" >Змінити пароль</a>
                        <button class="main-btn main-btn--green main-btn--width100">Зберегти</button>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="changePassword" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('my.profile.password') }}" method="POST" class="js-submit-ajax" data-action="form.reset">
                        @csrf
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="title title--medium">Зміна паролю</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z" fill="black"/>
                                </svg>
                            </button>
                        </div>

                        <div class="main-form__input @error('password_old') main-form__input--error @enderror">
                            <label for="password_old" class="modal__form-text main-text">Старий пароль</label>
                            <div class="modal__form-input-wrapper">
                                <input name="password_old"
                                       id="password_old"
                                       type="password"
                                       class="main-input main-input--white main-input--width100 "
                                       placeholder="Введіть"
                                       autocomplete="off"
                                >
                                <button type="button" class="modal__form-input-icon js-eye-btn"><svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg></button>
                            </div>
                            @error('password_old') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                        </div>
                        <div class="main-form__input @error('password') main-form__input--error @enderror">
                            <label for="modal-password" class="modal__form-text main-text">Новий пароль</label>
                            <div class="modal__form-input-wrapper">
                                <input name="password"
                                       id="modal-password"
                                       type="password"
                                       class="main-input main-input--white main-input--width100"
                                       placeholder="Введіть"
                                       autocomplete="off"
                                >
                                <button type="button" class="modal__form-input-icon js-eye-btn"><svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg></button>
                            </div>
                            @error('password') <span class="main-form__error-msg"> {{ $message }} </span> @enderror
                        </div>
                        <div class="main-form__input @error('password_confirmation') main-form__input--error @enderror">
                            <label for="password_confirmation" class="modal__form-text main-text">Підтвердіть пароль</label>
                            <div class="modal__form-input-wrapper">
                                <input name="password_confirmation"
                                       id="password_confirmation"
                                       type="password"
                                       class="main-input main-input--white main-input--width100"
                                       placeholder="Введіть"
                                       autocomplete="off"
                                >
                                <button type="button" class="modal__form-input-icon js-eye-btn"><svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg></button>
                            </div>
                            @error('password_confirmation') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                        </div>
                        <button class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold">Змінити</button>
                    </div>
                    </form>
                </div>
            </div>

            @include('my.inc.modal-exit')
        </section>


    </main>
@endsection
