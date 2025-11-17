<!-- Modal Basket -->
<div class="modal modal-basket fade" id="basketModal" aria-labelledby="basketModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="cart js-cart-modal">
                @include('cart.inc.modal')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Login -->
@guest
    <div class="modal modal-default fade" id="loginModal" aria-labelledby="loginModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body__head">
                        <div class="title">Вхід</div>
                        <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <svg class="icon-svg icon-svg-exit ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                            </svg>
                        </button>
                    </div>
                    @if(config('services.google.client_id'))
                    <div class="modal-body__social">
                        <a class="btn--extern btn--login" href="{{ route('socialite.oauth', 'facebook') }}">
                            Увійти через Facebook
                            <img src="{{ Theme::url('img/facebook.svg') }}" alt="facebook">
                        </a>
                        <a class="btn--extern btn--login" href="{{ route('socialite.oauth', 'google') }}">
                            Увійти через Google
                            <img src="{{ Theme::url('img/Google.svg') }}" alt="Google">
                        </a>
                    </div>
                    <div class="modal-body__choose">
                        <div class="dashed-line"></div>
                        <div class="modal-body__choose-text">Або</div>
                        <div class="dashed-line"></div>
                    </div>
                    @endif
                    <form class="modal-body__form js-submit-ajax" action="{{ url('login') }}" method="POST" data-action="page.reload">
                        @csrf
                        @honeypot
                        <input type="hidden" name="_destination" value="{{ \Illuminate\Support\Facades\Request::fullUrl() }}">
                        <div class="input__wrapper @error('login') error @enderror">
                            <input class="input"
                                   type="email"
                                   placeholder="Електронна пошта"
                                   name="email"
                                   value="{{ old('email') }}">
                        </div>
                        <div class="input__wrapper">
                            <input class="input input--password" type="password" placeholder="Пароль" name="password">
                            <button type="button" class="btn__show" name="password">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g>
                                        <path

                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                            fill="black"
                                        />
                                    </g>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body__reset">
                            <button type="button" class="btn__modal" data-bs-toggle="modal"
                                    data-bs-target="#resetModal">
                                Відновити пароль
                            </button>
                        </div>
                        <button class="btn--intern btn--wide">
                            Увійти
                        </button>
                        <button class="btn--extern btn--wide" type="button" data-bs-toggle="modal"
                                data-bs-target="#registerModal">
                            Я хочу зареєструватися
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endguest

@stack('modals')
<!-- Modal Feedback -->

<div class="modal modal-default fade" id="feedbackModal" aria-labelledby="feebackModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-body__head">
                    <div class="title">Зворотній зв’язок</div>
                    <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="icon-svg icon-svg-exit ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                        </svg>
                    </button>
                </div>

                <div class="modal-body__description text text-gray">
                    Введіть ваш номер телефону і ми зв’яжемось з вами протягом 5хв ❤️
                </div>

                <form class="modal-body__form js-submit-ajax" action="{{ route('lead') }}" method="POST" data-action="modal.hide">
                    @csrf
                    @honeypot
                    <div class="input__wrapper @error('phone') error @enderror">
                        <input type="hidden" name="form" value="feedback">
                        <input
                            class="input phone input--password"
                            placeholder="+38 ___ ___ __ __"
                            data-inputmask="'mask': '+38 (099)-999-99-99'"
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                        >
                        @error('phone') <p>{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn--intern btn--wide">
                        Зв’язатися
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Logout -->
@auth
    <div class="modal modal-default modal-logout fade" id="logoutModal" aria-labelledby="logoutModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="text">Ви впевнені, що хочете вийти?</p>
                    <div class="modal-body__actions">
                        <button class="btn--intern js-click-submit" data-url="{{ route('logout') }}">
                            Вийти
                            <svg class="icon-svg icon-svg-exit_door ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit_door"></use>
                            </svg>
                        </button>
                        <button class="btn--extern" data-bs-dismiss="modal" aria-label="Close">
                            Скасувати
                            <svg class="icon-svg icon-svg-exit ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth

<!-- Modal Register -->
@guest
    <div class="modal modal-default fade" id="registerModal" aria-labelledby="registerModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body__head">
                        <div class="title">Реєстрація</div>
                        <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <svg class="icon-svg icon-svg-exit ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                            </svg>
                        </button>
                    </div>
                    @if(config('services.google.client_id'))
                    <div class="modal-body__social">
                        <a class="btn--extern btn--login" href="{{ route('socialite.oauth', 'facebook') }}">
                            Увійти через Facebook
                            <img src="{{ Theme::url('img/facebook.svg') }}" alt="facebook">
                        </a>
                        <a class="btn--extern btn--login" href="{{ route('socialite.oauth', 'google') }}">
                            Увійти через Google
                            <img src="{{ Theme::url('img/Google.svg') }}" alt="Google">
                        </a>
                    </div>
                    <div class="modal-body__choose">
                        <div class="dashed-line"></div>
                        <div class="modal-body__choose-text">Або</div>
                        <div class="dashed-line"></div>
                    </div>
                    @endif
                    <form class="modal-body__form js-submit-ajax" action="{{ route('register') }}" method="POST" data-action="page.reload">
                        @csrf
                        @honeypot
                        <div class="input__wrapper @error('email') error @enderror">
                            <input class="input"
                                   value="{{ old('email') }}" type="email"
                                   placeholder="Електронна пошта" name="email">
                            @error('email') <p>{{ $message }}</p> @enderror
                        </div>
                        <div class="input__wrapper @error('password') error @enderror">
                            <input class="input input--password" type="password" placeholder="Пароль"
                                   name="password">
                            <button type="button" class="btn__show" name="password">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g>
                                        <path

                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                            fill="black"
                                        />
                                    </g>
                                </svg>
                            </button>
                            @error('password') <p>{{ $message }}</p> @enderror
                        </div>
                        <div class="input__wrapper">
                            <input class="input input--password" type="password" placeholder="Повторіть пароль"
                                   name="password_confirmation">
                            <button type="button" class="btn__show" name="password_confirmation">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g>
                                        <path

                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                            fill="black"
                                        />
                                    </g>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body__reset">
                            <p class="text text-gray">Натискаючи «Зареєструватися», я погоджуюсь з </p>
                            <a href="{{ route('pages.show', ['page' => 'terms']) }}" class="btn__modal">Умовами використання</a>
                            <p class="text text-gray">та</p>
                            <a href="{{ route('pages.show', ['page' => 'policy']) }}" class="btn__modal">Політикою конфіденційності</a>
                        </div>
{{--                        <div class="modal-body__reset">--}}
{{--                            <p class="text text-gray">Натискаючи «Зареєструватися», ви приймаєте</p>--}}
{{--                            <a type="button" class="btn__modal" href="/terms" target="_blank">--}}
{{--                                Правила користування сайтом--}}
{{--                            </a>--}}
{{--                        </div>--}}
                        <button class="btn--intern btn--wide">
                            Зареєструватися
                        </button>
                        <button type="button" class="btn--extern btn--wide" data-bs-toggle="modal"
                                data-bs-target="#loginModal">
                            Я маю особистий профіль
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endguest
<!-- Modal reset first step-->

<div class="modal modal-default fade" id="resetModal" aria-labelledby="resetModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-body__head">
                    <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <svg class="icon-svg icon-svg-left ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                        </svg>
                    </button>
                    <div class="title">Відновлення пароля</div>
                    <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="icon-svg icon-svg-exit ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                        </svg>
                    </button>
                </div>

                <form class="modal-body__form js-submit-ajax" action="{{ route('password.email') }}" method="POST" data-action="modal.hide" data-target-id="reset-password-modal">
                    @csrf
                    @honeypot
                    <input type="hidden" name="_modal" value="#resetModal">
                    <div class="input__wrapper @error('email') error @enderror ">
                        <input class="input" type="text" value="{{ old('email') }}"
                               placeholder="Електронна пошта" name="email">
                        @error('email') <p>{{ $message }}</p> @enderror
                    </div>
                    <button class="btn--intern btn--wide" type="submit">
                        Відправити код
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal reset second step-->

<div class="modal modal-default fade" id="reset-password-modal" aria-labelledby="confirmModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-body__head">
                    <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#resetModal">
                        <svg class="icon-svg icon-svg-left ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                        </svg>
                    </button>
                    <div class="title">Відновлення пароля</div>
                    <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="icon-svg icon-svg-exit ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                        </svg>
                    </button>
                </div>

                <div class="modal-body__email">
                    <p class="text text-gray">
                        Ми відправили код на пошту:
                        <span>
                            {{ old('email', request('email')) }}
                        </span>
                    </p>
                </div>

                <form class="modal-body__form js-submit-ajax" action="{{ route('password.update') }}" method="POST" data-action="modal.hide" data-target-id="loginModal">
                    @csrf
                    <input type="hidden" name="_modal" value="#reset-password-modal">
                    <input type="hidden" name="token" id="hidden-token" value="{{ request()->route('token') }}">
                    <input type="hidden" name="email" value="{{ old('email', request('email')) }}">
                    <div class="input__wrapper @error('code') error @enderror">
                        <input class="input"
                               value="{{ old('code') }}"
                               type="text"
                               placeholder="Код"
                               name="code"
                               required
                        >
                        @error('code') <p>{{ $message }}</p> @enderror
                    </div>
                    <div class="input__wrapper @error('password') error @enderror">
                        <input
                            class="input input--password"
                            type="password"
                            placeholder="Новий пароль"
                            name="password"
                        >
                        <button type="button" class="btn__show" name="password">
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g>
                                    <path

                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                        fill="black"
                                    />
                                </g>
                            </svg>
                        </button>
                        @error('code') <p>{{ $message }}</p> @enderror
                    </div>
                    <div class="input__wrapper @error('password') password-input @enderror">
                        <input
                            class="input input--password"
                            type="password"
                            placeholder="Підтвердити пароль"
                            name="password_confirmation"
                        >
                        <button type="button" class="btn__show" name="password_confirmation">
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g>
                                    <path

                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                        fill="black"
                                    />
                                </g>
                            </svg>
                        </button>
                    </div>
                    <button class="btn--intern btn--wide" type="submit">Увійти</button>
                </form>
                <div class="modal-body__reset">
                    <button type="button" class="btn__modal" data-bs-toggle="modal" data-bs-target="#resetModal">
                        Надіслати код повторно
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Restore -->

<div class="modal modal-default fade" id="restoreModal" aria-labelledby="restoreModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-body__head">
                    <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#confirmModal">
                        <svg class="icon-svg icon-svg-left ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                        </svg>
                    </button>
                    <div class="title">Відновлення пароля</div>
                    <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="icon-svg icon-svg-exit ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                        </svg>
                    </button>
                </div>

                <form class="modal-body__form js-submit-ajax" action="{{ route('password.update') }}" method="POST" data-action="page.reload">
                    @csrf
                    <input type="hidden" name="_modal" value="#restoreModal">
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">
                    <input type="hidden" name="email" value="{{ old('email', request('email')) }}">
                    <div class="input__wrapper @error('password') error @enderror">
                        <input
                            class="input input--password"
                            type="password"
                            placeholder="Новий пароль"
                            name="password"
                        >
                        <button type="button" class="btn__show" name="password">
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g>
                                    <path

                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                        fill="black"
                                    />
                                </g>
                            </svg>
                        </button>
                        @error('code') <p>{{ $message }}</p> @enderror
                    </div>
                    <div class="input__wrapper @error('password') password-input @enderror">
                        <input
                            class="input input--password"
                            type="password"
                            placeholder="Підтвердити пароль"
                            name="password_confirmation"
                        >
                        <button type="button" class="btn__show" name="password_confirmation">
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <g>
                                    <path

                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                        fill="black"
                                    />
                                </g>
                            </svg>
                        </button>
                    </div>
                    <button class="btn--intern btn--wide" type="submit">Увійти</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Success -->
<div class="modal modal-default fade" id="exampleModal" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img class="modal-body__img" src="{{ Theme::url('img/success-notification.svg') }}"
                     alt="success-notification">
                <div class="modal-body__text">
                    <div class="title">
                        Дякуємо!
                    </div>
                    <p class="text text-gray">Очікуйте на дзвінок</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Profile -->

<div class="modal modal-default modal-small  fade" id="profilePage" aria-labelledby="profilePageLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-body__head">
                    <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="icon-svg icon-svg-exit "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use></svg>
                    </button>
                </div>
                <ul class="profile__list">
                    <li class="profile__item @if(Route::is('my.orders.index')) active @endif">
                        <a href="{{ route('my.orders.index') }}" class="profile__link">
                            <svg class="icon-svg icon-svg-save "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#save"></use></svg>
                            Мої замовлення
                        </a>
                    </li>
                    <li class="profile__item @if(Route::is('my.profile.edit')) active @endif">
                        <a href="{{ route('my.profile.edit') }}" class="profile__link"><svg class="icon-svg icon-svg-user "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#user"></use></svg>
                            Мої дані
                        </a>
                    </li>
                    <li class="profile__item @if(Route::is('my.favorites.index')) active @endif">
                        <a href="{{ route('my.favorites.index') }}" class="profile__link"><svg class="icon-svg icon-svg-like "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#like"></use></svg>
                            Обрані
                        </a>
                    </li>
                    <li class="profile__item">
                        <button class="profile__link profile__link--logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <svg class="icon-svg icon-svg-logout "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#logout"></use></svg>Вийти
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@if($modal = old('_modal') ?: request('_modal') ?: session()->get('_modal'))
    @push('scripts')
        <script>
            var myModal = new bootstrap.Modal(document.querySelector('{{$modal}}'));
            myModal.show()
        </script>
    @endpush
@else
    @push('scripts')
        <script>
            var modalElement = document.getElementById('filterModal');
            if (modalElement && window.innerWidth < 1280) {
                $btnOkayFilter = $('.btn--extern')
                var myModal = new bootstrap.Modal(modalElement);

                // Отримуємо з localStorage інформацію про стан модалки
                var isModalOpen = localStorage.getItem('isModalOpen') === 'true';

                // Перевіряємо, чи модалка відкрита
                // if (isModalOpen) {
                //     myModal.show();
                // }

                if ($btnOkayFilter) {
                    $btnOkayFilter.on('click', function () {
                        localStorage.setItem('isModalOpen', false);
                    });
                }
                // Додаємо обробник події для зміни фільтрів
                // Переконайтеся, що ваші функції чи обробники подій викликають цю подію
                modalElement.addEventListener('hidden.bs.modal', function () {
                    // Зберігаємо інформацію, що модалка була закрита
                    localStorage.setItem('isModalOpen', false);
                });

                modalElement.addEventListener('shown.bs.modal', function () {
                    // Зберігаємо інформацію, що модалка була відкрита
                    localStorage.setItem('isModalOpen', true);
                });

                // document.addEventListener('filtersChanged', function () {
                //     // Зберігаємо інформацію, що вибрані фільтри
                //     localStorage.setItem('hasSelectedFilters', true);
                // });
                //
                // // Перевіряємо, чи вже вибрані фільтри
                // var hasSelectedFilters = localStorage.getItem('hasSelectedFilters') === 'true';
                //
                // // Якщо вибрані фільтри, то ви можете викликати подію filtersChanged
                // if (hasSelectedFilters) {
                //     var filtersChangedEvent = new Event('filtersChanged');
                //     document.dispatchEvent(filtersChangedEvent);
                // }
            } else {
                localStorage.setItem('isModalOpen', false);
            }
        </script>
    @endpush
@endif
