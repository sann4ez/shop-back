@extends('layouts.app', ['bodyClass' => 'profile__page'])

@php
    Seo::setDefault(['title' => 'Особистий кабінет: Дані']);
@endphp

@section('content')
    <main class="default-page profile">
        <div class="profile__wrapper container">
            @include('client.themes.home-mama.my.inc.aside')
            <button class="profile__menu-btn" type="button" data-bs-toggle="modal" data-bs-target="#profilePage">
                <svg class="icon-svg icon-svg-user "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#user"></use></svg>
                    Мої дані
                <svg class="icon-svg icon-svg-down "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#down"></use></svg>
            </button>
            <div class="profile__content">
                <h1 class="profile__title">Мої дані</h1>
                <div
                    class="accordion accordion--collapse"
                    id="accordionPanelsStayOpenPersonal"
                >
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button
                                class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseOne"
                                aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseOne"
                            >
                                <span class="accordion-text">Особисті дані</span>
                                <svg class="icon-svg icon-svg-top ">
                                    <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                </svg>
                            </button>
                        </h2>
                        <div
                            id="panelsStayOpen-collapseOne"
                            class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingOne"
                        >
                            <div class="accordion-body">
                                <form class="form js-submit-ajax" action="{{ route('my.profile.update') }}" method="POST" data-action="page.reload">
                                    @csrf
                                    <div class="form__wrapper">
                                        <div class="input__wrapper @error('name') error @enderror">
                                            <label class="label">Ім’я</label>
                                            <input
                                                class="input"
                                                type="text"
                                                name="name"
                                                value="{{ old('name', $user->name) }}"
                                                placeholder="Введіть ім’я"
                                            />
                                            @error('name') <p>{{ $message }}</p> @enderror
                                        </div>
                                        <div class="input__wrapper  @error('lastname') error @enderror">
                                            <label class="label">Прізвище</label>
                                            <input
                                                class="input"
                                                type="text"
                                                name="lastname"
                                                value="{{ old('lastname', $user->lastname) }}"
                                                placeholder="Введіть прізвище"
                                            />
                                            @error('lastname') <p>{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="form__wrapper">
                                        <div class="input__wrapper @error('email') error @enderror">
                                            <label class="label">E-mail</label>
                                            <input
                                                class="input"
                                                type="text"
                                                name="email"
                                                value="{{ old('email', $user->email) }}"
                                                placeholder="Введіть e-mail"
                                            />
                                            @error('email') <p>{{ $message }}</p> @enderror
                                        </div>
                                        <div class="input__wrapper @error('phone') error @enderror">
                                            <label class="label">Телефон</label>
                                            <input
                                                class="input"
                                                type="text"
                                                name="phone"
                                                value="+{{ old('phone', $user->phone) }}"
                                                placeholder="+38 ___ ___ __ __"
                                                data-inputmask="'mask': '+38 (099)-999-99-99'"
                                            />
                                            @error('phone') <p>{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <button class="btn--intern">Зберегти</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item" style="display: none">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button
                                class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseTwo"
                                aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseTwo"
                            >
                                <span class="accordion-text">Дані доставки</span>
                                <svg class="icon-svg icon-svg-top ">
                                    <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                </svg>
                            </button>
                        </h2>
                        <div
                            id="panelsStayOpen-collapseTwo"
                            class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingTwo"
                        >
                            <div class="accordion-body">
                                <form class="form" action="{{ route('my.shipping.update') }}" method="POST">
                                    @csrf
                                    <div class="checkout__select select @error('shipping.city') error @enderror">
                                        <label class="label">Місто</label>
                                        <select class="js-select js-suggestSettlements"
                                                name="shipping[city]"
                                                data-ajax-url="{{ route('suggest.novaposhtaSettlements') }}"
                                                data-placeholder="Виберіть місто"
                                        >
                                            @if($old = old_request('shipping.city', $user->getAdded('shipping.city')))
                                                <option value="{{ $old }}">{{ $user->getAdded('shipping.city') }}</option>
                                            @else
                                                <option value="">&nbsp;</option>
                                            @endif
                                        </select>
                                        @error('shipping.city') <p>{{ $message }}</p> @enderror
                                    </div>
                                    <div class="input__wrapper @error('shipping.street') error @enderror">
                                        <label class="label">Вулиця</label>
                                        <input
                                            class="input"
                                            type="text"
                                            name="shipping[street]"
                                            value="{{ old('shipping[street]', $user->getAdded('shipping.street')) }}"
                                            placeholder="Введіть вулицю"
                                        />
                                        @error('shipping.street') <p>{{ $message }}</p> @enderror
                                    </div>
                                    <div class="form__wrapper">
                                        <div class="input__wrapper @error('shipping.house') error @enderror">
                                            <label class="label">Номер будинку</label>
                                            <input
                                                class="input"
                                                type="text"
                                                name="shipping[house]"
                                                value="{{ old('shipping[house]', $user->getAdded('shipping.house')) }}"
                                                placeholder="Введіть номер"
                                            />
                                            @error('shipping.house') <p>{{ $message }}</p> @enderror
                                        </div>
                                        <div class="input__wrapper @error('shipping.apartment') error @enderror">
                                            <label class="label">Номер квартири</label>
                                            <input
                                                class="input"
                                                type="text"
                                                name="shipping[apartment]"
                                                value="{{ old('shipping[apartment]', $user->getAdded('shipping.apartment')) }}"
                                                placeholder="Введіть номер"
                                            />
                                            @error('shipping.apartment') <p>{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="input__wrapper @error('shipping.zipcode') error @enderror">
                                        <label class="label">Поштовий індекс</label>
                                        <input class="input"
                                               type="text"
                                               name="shipping[zipcode]"
                                               value="{{ old_request('shipping.zipcode', Auth::user()?->getAdded('shipping.zipcode')) }}"
                                               placeholder="Введіть поштовий індекс">
                                        @error('shipping.zipcode') <p>{{ $message }}</p> @enderror
                                    </div>
                                    <button class="btn--intern">Зберегти</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                            <button
                                class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseThree"
                                aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseThree"
                            >
                                <span class="accordion-text">Змінити пароль</span>
                                <svg class="icon-svg icon-svg-top ">
                                    <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                </svg>
                            </button>
                        </h2>
                        <div
                            id="panelsStayOpen-collapseThree"
                            class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingThree"
                        >
                            <div class="accordion-body">
                                <form class="form js-submit-ajax" action="{{ route('my.profile.password') }}" method="POST" data-action="page.reload">
                                    @csrf
                                    <div class="input__wrapper @error('password_old') error @enderror">
                                        <label class="label">Старий пароль</label>
                                        <div class="input__button-wrapper">
                                            <input
                                                class="input input--password"
                                                type="password"
                                                placeholder="Введіть пароль"
                                                name="password_old"
                                            />

                                            <button
                                                type="button"
                                                class="btn__show"
                                                name="password_old"
                                            >
                                                <svg
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <g id="eye_show">
                                                        <path
                                                            id="Union"
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                                            fill="black"
                                                        />
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password_old') <p>{{ $message }}</p> @enderror
                                    </div>
                                    <div class="input__wrapper @error('password') error @enderror">
                                        <label class="label">Новий пароль</label>
                                        <div class="input__button-wrapper">
                                            <input
                                                class="input input--password"
                                                type="password"
                                                placeholder="Введіть пароль"
                                                name="password"
                                            />

                                            <button
                                                type="button"
                                                class="btn__show"
                                                name="password"
                                            >
                                                <svg
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <g id="eye_show">
                                                        <path
                                                            id="Union"
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                                            fill="black"
                                                        />
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password') <p>{{ $message }}</p> @enderror
                                    </div>
                                    <div class="input__wrapper @error('password_confirmation') error @enderror">
                                        <label class="label">Підтвердити пароль</label>
                                        <div class="input__button-wrapper">
                                            <input
                                                class="input input--password"
                                                type="password"
                                                placeholder="Введіть пароль"
                                                name="password_confirmation"
                                            />

                                            <button
                                                type="button"
                                                class="btn__show"
                                                name="password_confirmation"
                                            >
                                                <svg
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <g id="eye_show">
                                                        <path
                                                            id="Union"
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                                            fill="black"
                                                        />
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password_confirmation') <p>{{ $message }}</p> @enderror
                                    </div>
                                    <button class="btn--intern">Зберегти</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
