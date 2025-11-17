<header class="header">
    <div class="header__inner container">
        <div class="header__top">
            <nav class="nav__header">
                @if(($items = \App\Models\Menuitem::getMenu('header')) && $items->count())
                    <ul class="header__info">
                        @foreach($items as $item)
                            <li class="header__info-item">
                                <a href="{{ $item->getUrlClient() }}"
                                   target="{{ $item->target }}"
                                   class="link @if(\Illuminate\Support\Facades\Request::fullUrl() === $item->getUrlClient()) active @endif"
                                >{{ $item->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </nav>

            <div class="header__contact">
                <button class="btn__modal" type="button" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                    Зворотній зв’язок
                </button>
                @if($block = Block::init('contacts'))
                    @if($items = $block->getContentSort('phones', []))
                        <div class="dropdown">
                            @if($v = \Illuminate\Support\Arr::first($items)['number'])
                                <a class="btn--phone" href="tel:{{ preg_replace('/[^0-9]/', '', $v) }}">
                                    <svg class="icon-svg icon-svg-phone icon-white">
                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#phone"></use>
                                    </svg>
                                    {{ $v }}
                                </a>
                            @endif
                            @if(count($items) > 1)
                                <button class=" btn--dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <svg class="icon-svg icon-svg-down ">
                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#down"></use>
                                    </svg>
                                </button>
                            @endif
                            <ul class="dropdown-menu">
                                {{--                                array_slice($items, 1)--}}
                                {{--                                @dump($items)--}}
                                @foreach(array_slice($items, 1) as $item)
                                    {{--                                    @dump($item)--}}
                                    <li><a class="dropdown-item"
                                           href="tel:{{ preg_replace('/[^0-9]/', '', $item['number']) }}">{{ $item['number'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <div class="header__bottom">
            <a href="{{ route('home') }}" class="header__logo">
                <img class="header__logo-desktop" src="{{ Theme::url('img/logo.svg') }}" alt="logo">
                <img class="header__logo-mobile" src="{{ Theme::url('img/logo-mobile.svg') }}" alt="logo-mobile">
            </a>

            <button class="btn--extern header__catalog js-catalog">
                <span>Каталог</span>
                <span class="header__catalog-icon">
                    <span class="arrow-menu up"></span>
                    <span class="arrow-menu down"></span>
                </span>
            </button>

            <div class="header__search js-search-mobile">

                <button id="btn-search--close" type="button" class="btn btn-search--close js-search-hidden">
                    <svg class="icon-svg icon-svg-left down">
                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                    </svg>
                </button>

                <form class="header__search-form js-search-mobile" method="GET"
                      action="{{ route('catalog.search', request()->only(config('laravel-url-facet-filter.filter'))) }}">
                    <div class="header__search-wrapper form-group">
                        <input
                                class="input search-input input-header js-search-input"
                                type="text" placeholder="Я шукаю.."
                                name="q"
                                value="{{ request('q') }}"
                                data-url="{{ route('catalog.search', ['_format' => 'suggest', 'limit' => 5]) }}"
                                autocomplete="off"
                        >
                        <button type="button" class="header__search-btn js-search js-search-hidden">
                            <svg class="icon-svg icon-svg-search header__search-icon">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#search"></use>
                            </svg>

                            <svg class="icon-svg icon-svg-close header__search-icon--close">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#close"></use>
                            </svg>
                        </button>
                    </div>
                </form>


                <div class="header__search-empty js-search-empty hidden">
                    <span>Нічого не знайдено :(</span>
                </div>

                <div class="header__searched js-search-result hidden">
                    {{--                    <div class="header__searched-item">--}}
                    {{--                        <a href="./search.html" class="header__searched-title">--}}
                    {{--                            <span>М'яч </span>--}}
                    {{--                            гумовий дитячий пригун ріжки 45 см 350 грам CB4501--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}

                    <button class="btn btn--intern">
                        Переглянути все
                    </button>
                </div>
            </div>

            <div class="header__act">
                @guest
                    <button type="button" class="link header__account" data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                        <svg class="icon-svg icon-svg-user header__icon">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#user"></use>
                        </svg>
                        <span>Вхід</span>
                    </button>
                @else
                    <a href="{{ route('my.profile.edit') }}" class="link header__account">
                        <svg class="icon-svg icon-svg-user header__icon">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#user"></use>
                        </svg>
                        <span>Кабінет</span>
                    </a>
                @endguest
                <a href="{{ route('my.favorites.index') }}" class="link header__favorite js-modal">
                    <svg class="icon-svg icon-svg-like header__icon">
                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#like"></use>
                    </svg>
                    <span>Обрані</span>
                    @if(\Favorite::getQty() > 0)
                        <p class="header__cart-quantity">{{ \Favorite::getQty() }}</p>
                    @else
                        <p class="header__favorite-quantity">0</p>
                    @endif
                </a>

                <button class="header__cart link-cart js-cart-icon" type="button" data-bs-toggle="modal"
                        data-bs-target="#basketModal">
                    @include('cart.inc.icon')
                </button>
            </div>

            <button class="menu-mobile js-catalog">
                <span class="menu-mobile__burger"></span>
            </button>
        </div>

        <div class="menu__shadow js-cart-close {{--js-search-hidden--}}"></div>

        <nav class="menu menu--dropdown" id="nav">
            <div class="menu__wrapper">
                @include('parts.aside')

                <div class="mobile-menu">
                    <div class="mobile-menu__user">
                        <a
                                href=""
                                class="mobile-menu__user-link"
                                data-bs-target="#signIn"
                                data-bs-toggle="modal"
                        >
                            <svg class="icon-svg icon-svg-user color-red user-link">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#user"></use>
                            </svg>
                            Особисті дані
                        </a>
                        <a href="" class="mobile-menu__user-like">
                            <svg class="icon-svg icon-svg-like color-red user-like">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#like"></use>
                            </svg>
                            Вибране
                        </a>
                    </div>

                    @if(($items = \App\Models\Menuitem::getMenu('header')) && $items->count())
                        <ul class="menu">
                            @foreach($items as $item)
                                <li class="menu__item">
                                    <a class="menu__link main-text"
                                       href="{{ $item->getUrlClient() }}">{{ $item->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="mobile-menu__connection">
                        @if($block = Block::init('contacts'))
                            @if($items = $block->getContentSort('phones', []))
                                @foreach($items as $item)
                                    <a class="mobile-menu__phone main-text"
                                       href="tel:{{ preg_replace('/[^0-9]/', '', $v) }}">
                                        <svg class="icon-svg icon-svg-phone icon-white phone">
                                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#phone"></use>
                                        </svg>
                                        {{ $item['number'] }}
                                    </a>
                                @endforeach
                            @endif
                        @endif
                        <a class="mobile-menu__feedback main-text" href="#"
                           data-bs-toggle="modal" data-bs-target="#feedbackModal"
                        >Зворотній зв'язок</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
@include('parts.modals')
