@extends('layouts.app', ['bodyClass' => 'profile__page'])

@php
    Seo::setDefault(['title' => 'Особистий кабінет: Коментарі']);
@endphp

@section('content')
    <main class="default-page profile">
        <div class="profile__wrapper container">
            @include('client.themes.home-mama.my.inc.aside')
            <div class="profile__content">
                <div class="profile__top">
                    <h1 class="profile__title">Мої відгуки</h1>
                </div>
                <!--      <div class="profile__empty">
                       <svg
                         width="201"
                         height="201"
                         viewBox="0 0 201 201"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg"
                       >
                         <rect
                           x="0.5"
                           y="0.5"
                           width="200"
                           height="200"
                           rx="100"
                           fill="#F1F1F1"
                         />
                         <path
                           fill-rule="evenodd"
                           clip-rule="evenodd"
                           d="M142.937 86.2777L138.265 81.6782L141.773 78.1152L148.254 84.4962C148.731 84.9661 149 85.6079 149 86.2777C149 86.9475 148.731 87.5892 148.254 88.0591L123.225 112.701C122.241 113.67 120.659 113.657 119.69 112.674C118.721 111.69 118.734 110.107 119.717 109.138L142.937 86.2777ZM52 145.833C52 144.453 53.1193 143.333 54.5 143.333H108.509C109.89 143.333 111.009 144.453 111.009 145.833C111.009 147.214 109.89 148.333 108.509 148.333H54.5C53.1193 148.333 52 147.214 52 145.833ZM119.235 145.833C119.235 144.453 120.354 143.333 121.735 143.333H144.919C146.3 143.333 147.419 144.453 147.419 145.833C147.419 147.214 146.3 148.333 144.919 148.333H121.735C120.354 148.333 119.235 147.214 119.235 145.833Z"
                           fill="#C6C6C6"
                         />
                         <path
                           d="M59.453 122.696L54.8211 144.333C54.6699 145.04 55.2948 145.669 56.0022 145.522L78.0006 140.957L59.453 122.696Z"
                           fill="#575555"
                         />
                         <path
                           d="M125.277 57.8815L59.4453 122.696L77.9928 140.957L140.011 79.8968L143.056 76.8987C144.25 75.7235 144.25 73.7984 143.056 72.6232L128.083 57.8815C127.305 57.1151 126.055 57.1151 125.277 57.8815Z"
                           fill="#777777"
                         />
                       </svg>

                       <p class="profile__empty-text">Ви не залишали відгуків</p>
                     </div> -->
                <ul class="profile__reviews-list">
                    <li class="profile__reviews-item">
                        <div class="profile__reviews-top">
                            <a href="./product.html">
                                <img
                                    src="{{ Theme::url('img/product.png') }}"
                                    alt="Product"
                                    class="profile__reviews-img"
                                /></a>

                            <a href="./product.html" class="profile__reviews-name">
                                Плаття для вагітних H&M Mama
                            </a>
                        </div>
                        <div class="profile__reviews-info">
                            <h6 class="profile__reviews-name">Олена</h6>
                            <span>20 вересня 2023</span>
                        </div>

                        <div class="profile-rating rating" data-rating="3"></div>
                        <p class="profile__reviews-text">
                            Плаття дуже комфортне завдяки м'якій і розтяжній тканині. Як
                            жінці, яка цінує зовнішній вигляд, мені важливо виглядати чудово,
                            навіть коли вагітна. Це плаття дозволяє мені поєднувати комфорт з
                            модою, і я отримую безліч компліментів від друзів та колег.
                            Рекомендую його кожній майбутній мамі!Плаття дуже комфортне
                            завдяки м'якій і розтяжній тканині. Як жінці, яка цінує зовнішній
                            вигляд, мені важливо виглядати чудово, навіть коли вагітна.
                        </p>
                        <button class="btn--extern">
                            Видалити <svg class="icon-svg icon-svg-delete "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use></svg>
                        </button>
                    </li>
                    <li class="profile__reviews-item">
                        <div class="profile__reviews-top">
                            <a href="./product.html">
                                <img
                                    src="{{ Theme::url('img/product.png') }}"
                                    alt="Product"
                                    class="profile__reviews-img"
                                /></a>

                            <a href="./product.html" class="profile__reviews-name">
                                Плаття для вагітних H&M Mama
                            </a>
                        </div>
                        <div class="profile__reviews-info">
                            <h6 class="profile__reviews-name">Олена</h6>
                            <span>20 вересня 2023</span>
                        </div>

                        <div class="profile-rating rating" data-rating="3"></div>
                        <p class="profile__reviews-text">
                            Плаття дуже комфортне завдяки м'якій і розтяжній тканині. Як
                            жінці, яка цінує зовнішній вигляд, мені важливо виглядати чудово,
                            навіть коли вагітна. Це плаття дозволяє мені поєднувати комфорт з
                            модою, і я отримую безліч компліментів від друзів та колег.
                            Рекомендую його кожній майбутній мамі!Плаття дуже комфортне
                            завдяки м'якій і розтяжній тканині. Як жінці, яка цінує зовнішній
                            вигляд, мені важливо виглядати чудово, навіть коли вагітна.
                        </p>
                        <button class="btn--extern">
                            Видалити <svg class="icon-svg icon-svg-delete "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use></svg>
                        </button>
                    </li>
                    <li class="profile__reviews-item">
                        <div class="profile__reviews-top">
                            <a href="./product.html">
                                <img
                                    src="{{ Theme::url('img/product.png') }}"
                                    alt="Product"
                                    class="profile__reviews-img"
                                /></a>

                            <a href="./product.html" class="profile__reviews-name">
                                Плаття для вагітних H&M Mama
                            </a>
                        </div>
                        <div class="profile__reviews-info">
                            <h6 class="profile__reviews-name">Олена</h6>
                            <span>20 вересня 2023</span>
                        </div>

                        <div class="profile-rating rating" data-rating="3"></div>
                        <p class="profile__reviews-text">
                            Плаття дуже комфортне завдяки м'якій і розтяжній тканині. Як
                            жінці, яка цінує зовнішній вигляд, мені важливо виглядати чудово,
                            навіть коли вагітна. Це плаття дозволяє мені поєднувати комфорт з
                            модою, і я отримую безліч компліментів від друзів та колег.
                            Рекомендую його кожній майбутній мамі!Плаття дуже комфортне
                            завдяки м'якій і розтяжній тканині. Як жінці, яка цінує зовнішній
                            вигляд, мені важливо виглядати чудово, навіть коли вагітна.
                        </p>
                        <button class="btn--extern">
                            Видалити <svg class="icon-svg icon-svg-delete "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use></svg>
                        </button>
                    </li>
                    <li class="profile__reviews-item">
                        <div class="profile__reviews-top">
                            <a href="./product.html">
                                <img
                                    src="{{ Theme::url('img/product.png') }}"
                                    alt="Product"
                                    class="profile__reviews-img"
                                /></a>

                            <a href="./product.html" class="profile__reviews-name">
                                Плаття для вагітних H&M Mama
                            </a>
                        </div>
                        <div class="profile__reviews-info">
                            <h6 class="profile__reviews-name">Олена</h6>
                            <span>20 вересня 2023</span>
                        </div>

                        <div class="profile-rating rating" data-rating="3"></div>
                        <p class="profile__reviews-text">
                            Плаття дуже комфортне завдяки м'якій і розтяжній тканині. Як
                            жінці, яка цінує зовнішній вигляд, мені важливо виглядати чудово,
                            навіть коли вагітна. Це плаття дозволяє мені поєднувати комфорт з
                            модою, і я отримую безліч компліментів від друзів та колег.
                            Рекомендую його кожній майбутній мамі!Плаття дуже комфортне
                            завдяки м'якій і розтяжній тканині. Як жінці, яка цінує зовнішній
                            вигляд, мені важливо виглядати чудово, навіть коли вагітна.
                        </p>
                        <button class="btn--extern">
                            Видалити <svg class="icon-svg icon-svg-delete "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use></svg>
                        </button>
                    </li>
                    <li class="profile__reviews-item">
                        <div class="profile__reviews-top">
                            <a href="./product.html">
                                <img
                                    src="{{ Theme::url('img/product.png') }}"
                                    alt="Product"
                                    class="profile__reviews-img"
                                /></a>

                            <a href="./product.html" class="profile__reviews-name">
                                Плаття для вагітних H&M Mama
                            </a>
                        </div>
                        <div class="profile__reviews-info">
                            <h6 class="profile__reviews-name">Олена</h6>
                            <span>20 вересня 2023</span>
                        </div>

                        <div class="profile-rating rating" data-rating="3"></div>
                        <p class="profile__reviews-text">
                            Плаття дуже комфортне завдяки м'якій і розтяжній тканині. Як
                            жінці, яка цінує зовнішній вигляд, мені важливо виглядати чудово,
                            навіть коли вагітна. Це плаття дозволяє мені поєднувати комфорт з
                            модою, і я отримую безліч компліментів від друзів та колег.
                            Рекомендую його кожній майбутній мамі!Плаття дуже комфортне
                            завдяки м'якій і розтяжній тканині. Як жінці, яка цінує зовнішній
                            вигляд, мені важливо виглядати чудово, навіть коли вагітна.
                        </p>
                        <button class="btn--extern">
                            Видалити <svg class="icon-svg icon-svg-delete "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use></svg>
                        </button>
                    </li>
                    <li class="profile__reviews-item">
                        <div class="profile__reviews-top">
                            <a href="./product.html">
                                <img
                                    src="{{ Theme::url('img/product.png') }}"
                                    alt="Product"
                                    class="profile__reviews-img"
                                /></a>

                            <a href="./product.html" class="profile__reviews-name">
                                Плаття для вагітних H&M Mama
                            </a>
                        </div>
                        <div class="profile__reviews-info">
                            <h6 class="profile__reviews-name">Олена</h6>
                            <span>20 вересня 2023</span>
                        </div>

                        <div class="profile-rating rating" data-rating="3"></div>
                        <p class="profile__reviews-text">
                            Плаття дуже комфортне завдяки м'якій і розтяжній тканині. Як
                            жінці, яка цінує зовнішній вигляд, мені важливо виглядати чудово,
                            навіть коли вагітна. Це плаття дозволяє мені поєднувати комфорт з
                            модою, і я отримую безліч компліментів від друзів та колег.
                            Рекомендую його кожній майбутній мамі!Плаття дуже комфортне
                            завдяки м'якій і розтяжній тканині. Як жінці, яка цінує зовнішній
                            вигляд, мені важливо виглядати чудово, навіть коли вагітна.
                        </p>
                        <button class="btn--extern">
                            Видалити <svg class="icon-svg icon-svg-delete "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use></svg>
                        </button>
                    </li>
                </ul>
                <button class="btn--intern catalog__btn--more">
                    Показати ще
                    <svg
                        width="25"
                        height="24"
                        viewBox="0 0 25 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M19.2344 14.6966C18.5978 16.1911 17.4521 17.4231 15.9926 18.1825C14.533 18.942 12.8499 19.182 11.23 18.8617C9.61012 18.5414 8.15369 17.6805 7.10886 16.4258C6.06403 15.1711 5.49546 13.6001 5.50003 11.9807C5.50459 10.3612 6.08201 8.79336 7.13389 7.54433C8.18577 6.29529 9.64704 5.44233 11.2687 5.13077C12.8904 4.81921 14.5721 5.06834 16.0274 5.8357C17.4826 6.60306 18.6213 7.84117 19.2495 9.33909M19.2495 9.33909L20.5 5.13077M19.2495 9.33909L15.1429 8.49999"
                            stroke="white"
                            stroke-width="1.4"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </button>
                <nav aria-label="page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link active" href="#">1</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item">
                            <a class="page-link dots" href="#">...</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">6</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </main>
@endsection
