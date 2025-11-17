<div
    class="modal fade"
    id="signIn"
    tabindex="-1"
    aria-hidden="true"
>
    <form class="modal-dialog modal-dialog-centered js-submit-ajax" action="{{ url('login') }}" method="POST" data-action="page.reload">
        @csrf
        @honeypot
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="title title--medium">Вхід</h2>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z"
                            fill="black"
                        />
                    </svg>
                </button>
            </div>
            <div class="main-form__input @error('email') main-form__input--error @enderror">
                <label for="phone" class="modal__form-text main-text"
                >Електронна пошта</label
                >
                <input
                    name="email"
                    id="email"
                    type="email"
                    class="main-input main-input--gray"
                    value="{{ old('email') }}"
                    autocomplete="off"
                >
                @error('email') <span class="main-form__error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="main-form__input @error('password') main-form__input--error @enderror">
                <label for="password" class="modal__form-text main-text">Пароль</label>
                <div class="modal__form-input-wrapper">
                    <input
                        name="password"
                        id="password"
                        type="password"
                        class="main-input main-input--gray main-input--width100"
                        autocomplete="off"
                    />
                    <button aria-label="togglePasswordAppear" type="button" class="modal__form-input-icon js-eye-btn">
                        <svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg>
                    </button>
                </div>
                @error('password') <span class="main-form__error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="modal__password-manage">
                <label
                    for="remember"
                    class="filter__select-label filter__select-label--modal filter__select-label--remember"
                >
                    <input
                        name="brand"
                        id="remember"
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
                aria-label="signIn"
                class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold"
            >
                Увійти
            </button>
            <button
                type="button"
                class="main-btn main-btn--modal main-btn--light-gray main-btn--width100 main-text main-text--semibold"
                data-bs-target="#signUp"
                data-bs-toggle="modal"
                aria-label="openSignUpModal"
            >
                Зареєструватися
            </button>
        </div>
    </form>
</div>

<div
    class="modal fade"
    id="signUp"
    tabindex="-1"
    aria-hidden="true"
>
    <form class="modal-dialog modal-dialog-centered js-submit-ajax" method="POST" action="{{ route('register') }}" data-action="page.reload">
        @csrf
        @honeypot
        <div class="modal-content main-form main-form--modal">
            <div class="modal-header">
                <h2 class="title title--medium">Реєстрація</h2>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z"
                            fill="black"
                        />
                    </svg>
                </button>
            </div>

            <div class="main-form__input">
                <label for="name" class="modal__form-text @error('name') main-form__input--error @enderror">Ім'я</label>
                <input
                    name="name"
                    id="name"
                    type="text"
                    class="main-input main-input--gray"
                    autocomplete="off"
                />
                @error('name') <span class="main-form__error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="main-form__input @error('email') main-form__input--error @enderror">
                <label for="email2" class="modal__form-text">Електронна пошта</label>
                <input
                    name="email"
                    id="email2"
                    type="email"
                    class="main-input main-input--gray"
                    autocomplete="off"
                />
                @error('email') <span class="main-form__error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="main-form__input @error('password') main-form__input--error @enderror">
                <label for="password1" class="modal__form-text">Пароль</label>
                <div class="modal__form-input-wrapper">
                    <input
                        name="password"
                        id="password1"
                        type="password"
                        class="main-input main-input--gray main-input--width100"
                        autocomplete="off"
                    />
                    <button type="button" class="modal__form-input-icon js-eye-btn">
                        <svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg>
                    </button>
                </div>
                @error('password') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                <p class="agreement">“Відправляючи форму ви погоджуєтесь на <a href="{{ route('pages.show', ['page' => 'terms']) }}" class="main-link">Умови використання</a> та <a href="{{ route('pages.show', ['page' => 'policy']) }}" class="main-link">Політику конфіденційності</a>.”</p>
            </div>
            <button
                class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold"
            >
                Зареєструватися
            </button>
            <button
                type="button"
                class="main-btn main-btn--modal main-btn--light-gray main-btn--width100 main-text main-text--semibold"
                data-bs-target="#signIn"
                data-bs-toggle="modal"
            >
                У мене є аккаунт
            </button>
        </div>
    </form>
</div>


<div
    class="modal fade"
    id="resetModal"
    tabindex="-1"
    aria-hidden="true"
>
    <form class="modal-dialog modal-dialog-centered js-submit-ajax" action="{{ route('password.email') }}" method="POST" data-action="modal.show" data-target-id="reset-password-modal">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header__wrapper">
                    <button
                        class="arrow arrow__left arrow--modal modal__arrow"
                        data-bs-target="#signIn"
                        data-bs-toggle="modal"
                    >
                        <svg
                            class="modal__arrow-svg"
                            xmlns="http://www.w3.org/2000/svg"
                            width="32"
                            height="32"
                            viewBox="0 0 32 32"
                            fill="none"
                        >
                            <rect
                                x="32"
                                y="32"
                                width="32"
                                height="32"
                                rx="16"
                                transform="rotate(-180 32 32)"
                                fill="#F5F5F5"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M18.203 24.2072L9.99587 16L18.203 7.79294L19.6172 9.20716L12.8243 16L19.6172 22.7929L18.203 24.2072Z"
                                fill="black"
                            />
                        </svg>

                    </button>
                    <h2 class="title title--medium">
                        Відновлення паролю
                    </h2>
                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z"
                            fill="black"
                        />
                    </svg>
                </button>
            </div>
            <div class="main-form__input @error('email') main-form__input--error @enderror">
                <label for="email3" class="modal__form-text">Електронна пошта</label>
                <input
                    name="email"
                    id="email3"
                    type="email"
                    class="main-input main-input--gray"
                    autocomplete="off"
                />
                @error('email') <span class="main-form__error-msg">{{ $message }}</span> @enderror
            </div>
            <button
                class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold"
            >
                Надіслати Код
            </button>
        </div>
    </form>
</div>

<div
    class="modal fade"
    id="reset-password-modal"
    tabindex="-1"
    aria-hidden="true"
>
    <form class="modal-dialog modal-dialog-centered js-submit-ajax" action="{{ route('password.update') }}" method="POST" data-action="page.reload" >
        @csrf
        <input type="hidden" name="token" value="{{ request()->route('token') }}" autocomplete="off">
        <input type="hidden" name="email" value="{{ old('email', request('email')) }}" autocomplete="off">
        <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header__wrapper">
                <button
                    class="arrow arrow__left arrow--modal modal__arrow"
                    data-bs-target="#resetModal"
                    data-bs-toggle="modal"
                >
                    <svg
                        class="modal__arrow-svg"
                        xmlns="http://www.w3.org/2000/svg"
                        width="32"
                        height="32"
                        viewBox="0 0 32 32"
                        fill="none"
                    >
                        <rect
                            x="32"
                            y="32"
                            width="32"
                            height="32"
                            rx="16"
                            transform="rotate(-180 32 32)"
                            fill="#F5F5F5"
                        />
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M18.203 24.2072L9.99587 16L18.203 7.79294L19.6172 9.20716L12.8243 16L19.6172 22.7929L18.203 24.2072Z"
                            fill="black"
                        />
                    </svg>

                </button>
                <h2 class="title title--medium">
                    Відновлення паролю
                </h2>
            </div>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 16 16"
                    fill="none"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z"
                        fill="black"
                    />
                </svg>
            </button>
        </div>
        <div class="main-form__input @error('code') main-form__input--error @enderror">
            <label for="code" class="modal__form-text">Код</label>
            <input
                name="code"
                id="code"
                value="{{ old('code') }}"
                type="number"
                placeholder="Код"
                class="main-input main-input--gray"
                autocomplete="off"
            />
            @error('code') <span class="main-form__error-msg">{{ $message }}</span> @enderror
        </div>
            <div class="main-form__input @error('password') main-form__input--error @enderror">
                <label for="password2" class="modal__form-text">Новий пароль</label>
                <div class="modal__form-input-wrapper">
                    <input
                        name="password"
                        id="password2"
                        type="password"
                        class="main-input main-input--gray main-input--width100"
                        placeholder="Новий пароль"
                        autocomplete="off"
                    />
                    <button type="button" class="modal__form-input-icon js-eye-btn">
                        <svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg>
                    </button>
                </div>
                @error('password') <span class="main-form__error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="main-form__input @error('password_confirmation') main-form__input--error @enderror">
                <label for="password_confirmation2" class="modal__form-text">Повторити пароль</label>
                <div class="modal__form-input-wrapper">
                    <input
                        name="password_confirmation"
                        id="password_confirmation2"
                        type="password"
                        class="main-input main-input--gray main-input--width100"
                        placeholder="Підтвердіть пароль"
                        autocomplete="off"
                    />
                    <button type="button" class="modal__form-input-icon js-eye-btn">
                        <svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg>
                    </button>
                </div>
                @error('password_confirmation') <span class="main-form__error-msg">{{ $message }}</span> @enderror
            </div>
            <button class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold">
                Відправити Код
            </button>
        </div>
    </form>
</div>

{{-- TODO ?? --}}
<div
    class="modal fade"
    id="newPassword"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header__wrapper">
                    <button
                        class="arrow arrow__left arrow--modal modal__arrow"
                        data-bs-target="#reset-password-modal"
                        data-bs-toggle="modal"
                    >
                        <svg
                            class="modal__arrow-svg"
                            xmlns="http://www.w3.org/2000/svg"
                            width="32"
                            height="32"
                            viewBox="0 0 32 32"
                            fill="none"
                        >
                            <rect
                                x="32"
                                y="32"
                                width="32"
                                height="32"
                                rx="16"
                                transform="rotate(-180 32 32)"
                                fill="#F5F5F5"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M18.203 24.2072L9.99587 16L18.203 7.79294L19.6172 9.20716L12.8243 16L19.6172 22.7929L18.203 24.2072Z"
                                fill="black"
                            />
                        </svg>

                    </button>
                    <h2 class="title title--medium">
                        Відновлення паролю
                    </h2>
                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z"
                            fill="black"
                        />
                    </svg>
                </button>
            </div>
            <div class="main-form__input">
                <label for="password3" class="modal__form-text">Новий пароль</label>
                <div class="modal__form-input-wrapper">
                    <input
                        name="phone"
                        id="password3"
                        type="password"
                        class="main-input main-input--gray main-input--width100"
                        autocomplete="off"
                    />
                    <button class="modal__form-input-icon js-eye-btn">
                        <svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg>
                    </button>
                </div>
            </div>
            <div class="main-form__input">
                <label for="password_confirmation3" class="modal__form-text">Повторити пароль</label>
                <div class="modal__form-input-wrapper">
                    <input
                        name="password_confirmation"
                        id="password_confirmation3"
                        type="password"
                        class="main-input main-input--gray main-input--width100"
                        autocomplete="off"
                    />
                    <button class="modal__form-input-icon js-eye-btn">
                        <svg class="icon-svg icon-svg-eye-show eye-show"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg>
                    </button>
                </div>
            </div>
            <button
                class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold"
            >
                Змінити
            </button>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="feedback"
    tabindex="-1"
    aria-hidden="true"
>
    <form class="modal-dialog modal-dialog-centered js-submit-ajax" action="{{ route('lead') }}" method="POST" data-action="modal.hide">
        @csrf
        @honeypot
        <input type="hidden" name="form" value="feedback" autocomplete="off">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title__wrapper">
                    <h2 class="title title--medium">Зворотній зв’язок</h2>
                    <span class="main-text main-text--color-s-gray">Ми зв’яжемся з вами протягом 5хв</span>
                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z"
                            fill="black"
                        />
                    </svg>
                </button>
            </div>

            <div class="main-form__input @error('phone') main-form__input--error @enderror">
                <label for="phone" class="modal__form-text">Телефон <span class="required">*</span></label>
                <input
                    name="phone"
                    id="phone"
                    type="tel"
                    class="main-input main-input--gray"
                    value="{{ old('phone') }}"
                    autocomplete="off"
                />
                @error('phone') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
            </div>
            <div class="main-form__input @error('name') main-form__input--error @enderror">
                <label for="name2" class="modal__form-text">Ім'я</label>
                <input
                    name="name"
                    id="name2"
                    type="text"
                    class="main-input main-input--gray"
                    value="{{ old('name') }}"
                    autocomplete="off"
                />
                @error('name') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
            </div>
            <button
                class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold"
            >
                Отримати консультацію
            </button>
        </div>
    </form>

</div>


<div
    class="modal fade"
    id="cart"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog--cart modal-dialog-centered">
        <div class="modal-content modal-content--cart js-cart-modal">
            @include('cart.inc.modal')
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
            $(document).ready(function () {
                var $filterButton = $('.js-filter-btn');
                    $filterSelect = $('.js-filter-select');

                // Кнопки які закривають бекдроп
                    $filterButtonClose = $('.js-filter-btn-close');
                    $btnResetFilter = $('.js-btn-reset-filter');
                    $btnOkayFilter = $('.js-btn-okay-filter');
                    $backgroundClose = $('.bg-gray');

                // Функція яка дозволяє закривати бекдроп
                function removeActiveClass() {
                    localStorage.setItem('filterButtonClicked', false);
                    $filterSelect.removeClass('catalog__products-select--active');
                }


                // Виклики функцій які закривають бекдроп
                if (window.innerWidth > 1023) {
                    removeActiveClass();
                }
                if ($filterButtonClose && window.innerWidth < 1023) {
                    $filterButtonClose.on('click', removeActiveClass);
                }
                if ($btnResetFilter && window.innerWidth < 1023) {
                    $btnResetFilter.on('click', removeActiveClass);
                }
                if ($btnOkayFilter && window.innerWidth < 1023) {
                    $btnOkayFilter.on('click', removeActiveClass);
                }
                if ($backgroundClose && window.innerWidth < 1023) {
                    $backgroundClose.on('click', removeActiveClass);
                }

                // Перевірка чи filterButtonClicked є true що береться з localStorage
                var $isFilterButtonClicked = localStorage.getItem('filterButtonClicked') === 'true';

                // Якщо натиснута кнопка filterButton, то в localStorage записується true
                if ($filterButton) {
                    $filterButton.on('click', function () {
                        localStorage.setItem('filterButtonClicked', true);
                    });
                }

                // Якщо кнопка "Фільтр" була натиснута, то ви можете викликати подію filtersChanged
                if ($isFilterButtonClicked) {
                    var $filtersChangedEvent = new Event('filtersChanged');
                    document.dispatchEvent($filtersChangedEvent);

                    $('.bg-gray').addClass('bg-gray--active');

                    if ($filterSelect.length) {
                        $filterSelect.addClass('catalog__products-select--active');
                    }
                }
            });
        </script>
    @endpush
@endif
