@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Search'),
        'robots' => 'noindex, nofollow'
    ]);
@endphp

@section('content')
    <main>
        <section class="catalog catalog--search container">
            <div class="catalog__content catalog__content--search">
                <div class="catalog__header ">
                    <div class="header-page">
                        {{ Breadcrumbs::render('catalog.search') }}
                        @if(request('q'))
                            <h1 class="title title--search"><span class="title__search">Результати пошуку</span> {{ request('q') }}</h1>
                        @endif
                    </div>
                </div>
                <div class="catalog__products ">
                    <div class="catalog__products-wrapper">
                        <div class="catalog__products-select js-filter-select">

                            @include('catalog.inc.facet-filter')

                        </div>
                        <div class="catalog__products-items catalog__products-items--search">
                            <div class="catalog__products-items-sort">
                                <div class="sort">
                                    <button class="main-btn main-btn--hide-1000 main-btn--green main-btn--width-100 sort__filter-btn js-filter-btn">
                                        Фільтр
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.61455 6.43853C2.32089 5.18899 3.20544 3 5.00402 3H17.0546C18.8532 3 19.7378 5.18899 18.4441 6.43853L12.0293 12.6346V20H10.0293V12.6346L3.61455 6.43853ZM11.0293 10.8198L17.0546 5H5.00402L11.0293 10.8198Z" fill="white"/>
                                        </svg>
                                    </button>

                                    <div class="filter-dropdown">
                                        <div class="filter-dropdown__header">
                                            <!-- <button class="filter-dropdown__btn"></button> -->
                                            <button class="arrow  arrow--pagination arrow--modal filter-dropdown__btn js-filter-btn-close">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                                                    <rect x="32" y="32.5" width="32" height="32" rx="16" transform="rotate(-180 32 32.5)" fill="#E9E8E8"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.2069 24.707L9.99977 16.4999L18.2069 8.29282L19.6211 9.70704L12.8282 16.4999L19.6211 23.2928L18.2069 24.707Z" fill="#121212"/>
                                                </svg>

                                            </button>
                                            <h1 class="title filter-dropdown__title">Фільтр</h1>
                                        </div>
                                        <form action="handler.php" class="filter filter--mobile" method="GET">
                                            <div class="filter__wrapper">

                                                <div class="filter__select-menu">
                                                    <div class="filter__select-wrapper">
                                                        <div class="filter__select-header">
                                                            <h4 class="filter__title main-text main-text--semibold">Вид</h4>

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5303 15.4697L19.4696 16.5303L11.9999 9.06066L4.53027 16.5303L3.46961 15.4697L11.9999 6.93934L20.5303 15.4697Z" fill="black"/>
                                                            </svg>
                                                        </div>

                                                        <label class="filter__select-label filter__select-label--modal">
                                                            <input name="brand" class="filter__select-input " type="checkbox" autocomplete="off">
                                                            <span class="filter__select-input-img">

                                                                    <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                        <rect width="25" height="25" rx="5" fill="#2A8927"/>
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>
                                                                    </svg>
                                                                </span>
                                                            Багаторічний
                                                        </label>
                                                        <label class="filter__select-label filter__select-label--modal">
                                                            <input name="brand" class="filter__select-input " type="checkbox" autocomplete="off">
                                                            <span class="filter__select-input-img">

                                                                    <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                        <rect width="25" height="25" rx="5" fill="#2A8927"/>
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>
                                                                    </svg>
                                                                </span>
                                                            Дворічний
                                                        </label>
                                                        <label class="filter__select-label filter__select-label--modal">
                                                            <input name="brand" class="filter__select-input " type="checkbox" autocomplete="off">
                                                            <span class="filter__select-input-img">

                                                                    <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                        <rect width="25" height="25" rx="5" fill="#2A8927"/>
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>
                                                                    </svg>
                                                                </span>
                                                            Кімнатний
                                                        </label>
                                                        <label class="filter__select-label filter__select-label--modal">
                                                            <input name="brand" class="filter__select-input " type="checkbox" autocomplete="off">
                                                            <span class="filter__select-input-img">

                                                                    <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                        <rect width="25" height="25" rx="5" fill="#2A8927"/>
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>
                                                                    </svg>
                                                                </span>
                                                            Однорічний
                                                        </label>

                                                    </div>
                                                </div>

                                                <span class="filter__devider"></span>

                                                <div class="filter__select-menu">
                                                    <div class="filter__select-wrapper">
                                                        <div class="filter__select-header">
                                                            <h4 class="filter__title main-text main-text--semibold">Грами</h4>

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5303 15.4697L19.4696 16.5303L11.9999 9.06066L4.53027 16.5303L3.46961 15.4697L11.9999 6.93934L20.5303 15.4697Z" fill="black"/>
                                                            </svg>
                                                        </div>
                                                        <label class="filter__select-label filter__select-label--modal">
                                                            <input name="brand" class="filter__select-input " type="checkbox" autocomplete="off">
                                                            <span class="filter__select-input-img">

                                                                    <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                        <rect width="25" height="25" rx="5" fill="#2A8927"/>
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>
                                                                    </svg>
                                                                </span>
                                                            0.1
                                                        </label>
                                                        <label class="filter__select-label filter__select-label--modal">
                                                            <input name="brand" class="filter__select-input " type="checkbox" autocomplete="off">
                                                            <span class="filter__select-input-img">

                                                                    <svg class="filter__select-input-svg" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                        <rect width="25" height="25" rx="5" fill="#2A8927"/>
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.7072 6.70712L9.00008 19.4142L3.29297 13.7071L4.70718 12.2929L9.00008 16.5858L20.293 5.29291L21.7072 6.70712Z" fill="#F5F5F5"/>
                                                                    </svg>
                                                                </span>
                                                            0.2
                                                        </label>

                                                    </div>
                                                </div>

                                                <span class="filter__devider"></span>

                                                <div class="filter__select-menu">
                                                    <div class="filter__select-wrapper filter__select-wrapper--range">

                                                        <div class="filter__select-header">
                                                            <h4 class="filter__title main-text main-text--semibold">Ціна, грн</h4>
                                                        </div>

                                                        <div class="range">
                                                            <div class="range__range-select">
                                                                <div class="range__slider">
                                                                    <div class="range__slider-progress"></div>
                                                                </div>
                                                                <div class="range__range-input">
                                                                    <input type="range" class="range__range-input-min" min="0" max="10000" value="2500" step="100" autocomplete="off">
                                                                    <input type="range" class="range__range-input-max" min="0" max="10000" value="7500" step="100" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="range__price-input">
                                                                <div class="range__price-field">
                                                                    <input type="number" class="range__price-input-min main-input main-input--width100 main-input--gray" value="2500" autocomplete="off">
                                                                </div>
                                                                <div class="range__price-devider">-</div>
                                                                <div class="range__price-field">
                                                                    <input type="number" class="range__price-input-max main-input main-input--width100 main-input--gray" value="7500" autocomplete="off">
                                                                </div>
                                                                <button class="filter__apply main-text main-text--semibold main-btn main-btn--apply">OK</button>
                                                            </div>
                                                        </div>
                                                        <button class="main-btn main-btn--green">Застосувати • 3 товари</button>
                                                        <button type="reset" class="main-btn main-btn--gray js-btn-reset-filter">Скинути фільтри</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @include('catalog.inc.catalog-view-settings')
                                </div>
                            </div>

                            @if($variations->isEmpty())
                                <div class="search-fail">
                                    <div class="search-fail__logo">:(</div>
                                    <div class="main-text">За вашим пошуковим запитом нічого не знайдено</div>
                                </div>
                            @endif

                            <div class="catalog__products-items-grid catalog__products-items-grid--search js-perpage-source">
                                @if(!$variations->isEmpty())
                                    @include('catalog.inc.variations-list', ['variations' => $variations])
                                @endif
                            </div>

                            @include('parts.pagination-navigation', ['items' => $variations])

                        </div>
                    </div>
                </div>
            </div>

            @include('parts.reviewed_variations')

        </section>

    </main>
@endsection
