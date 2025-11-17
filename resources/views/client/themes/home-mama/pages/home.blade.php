@extends('layouts.app')

@php
    Seo::setModel($page);
@endphp

{{--@dd(\App\Models\Block::initBlock('home_brands'))--}}

@section('content')
    <main class="default-page">

        <div class="container home__swiper">
            <div class="menu__wrapper menu__wrapper--home">
                @include('parts.aside')
            </div>

            {{-- SLIDER --}}
            {{--@if(($items = \App\Models\MenuItem::getMenu('slider')) && $items->count())--}}
            @if($block = Block::init('home_slider'))
            @if($items = $block->getContentSort('items'))
                <div class="swiper__main">
                    <div class="swiper-wrapper swiper__main-wrapper">
                        @foreach($items as $item)
                            <div class="swiper-slide swiper__main-slide">
                                <a class="swiper__main-link" target="_blank"
                                   href="{{ $item['url'] ?: '#' }}">
                                    <img
                                        src="{{ $item['img'] }}"
                                        alt="{{ $item['title'] }}"
                                        class="swiper__main-img"
                                        @if($loop->index > 0) loading="lazy" @endif
                                    >
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination swiper__main-pagination"></div>
                    <div class="swiper-button-prev swiper__main-button-prev">
                        <svg class="icon-svg icon-svg-left ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                        </svg>
                    </div>
                    <div class="swiper-button-next swiper__main-button-next">
                        <svg class="icon-svg icon-svg-right ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use>
                        </svg>
                    </div>
                </div>
            @endif
            @endif
        </div>

        {{-- CATEGORIES --}}
        @if($block = Block::init('popular_categories'))
            @if($block->getData('categories')->count())
            <section>
                <div class="swiper__loop">
                    <div class="swiper-top container">
                        <div class="title">{{ $block->getContent('title') }}</div>
                        <div class="swiper-top__btn">
                            <div class="swiper-button-prev--block swiper__loop-button-prev">
                                <svg class="icon-svg icon-svg-left "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use></svg>
                            </div>
                            <div class="swiper-button-next--block swiper__loop-button-next">
                                <svg class="icon-svg icon-svg-right "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use></svg>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-wrapper swiperf__loop-wrapper">
                        @foreach($block->getData('categories') as $item)
                        <a href="{{ $item->getUrlClient() }}" class="swiper-slide swiper__loop-slide">
                            <span class="swiper__loop-category">
                                <img src="{{ $item->getMyFirstMediaUrl('image') ?: Theme::url('img/category.png') }}" width="400" height="450" onerror="this.onerror=null;this.src='img/nophoto.webp';"  alt="Image" class="swiper__loop-img">
                            </span>
                            <p class="swiper__loop-text">
                                <span>{{ $item->name }} </span><span>{{ pluralize_ukrainian($item->variations_count ?: 0, ['товар', 'товари', 'товарів']) }}</span>
                            </p>
                        </a>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
        @endif

        {{-- BRANDS --}}
        @if($block = Block::init('home_brands'))
        @if($block->getData('brands')->count())
        <section class="home__brand">
            <div class="swiper__loop">
                <div class="swiper-top container">
                    <div class="swiper-top__wrapper">
                        <div class="title">{{ $block->getContent('title') }}</div>
                        <a href="{{ route('catalog.brands.index') }}" class="btn--more">
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <circle cx="12" cy="12" r="10" fill="#E25566"/>
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M11.3 11.3V8H12.7V11.3H16V12.7H12.7V16H11.3V12.7H8V11.3H11.3Z"
                                    fill="white"
                                />
                            </svg>

                            Показати все
                        </a>
                    </div>

                    <div class="swiper-top__btn">
                        <div class="swiper-button-prev--block swiper__loop-button-prev">
                            <svg class="icon-svg icon-svg-left ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                            </svg>
                        </div>
                        <div class="swiper-button-next--block swiper__loop-button-next">
                            <svg class="icon-svg icon-svg-right ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="swiper-wrapper swiper__loop-wrapper">
                    @foreach($block->getData('brands') as $item)
                        <a href="{{ $item->getUrlClient() }}" class="swiper-slide brand">
                            <img
                                src="{{ $item->getMyFirstMediaUrl('logo', 'preview') ?: Theme::url('img/nophoto.webp') }}"
                                alt="Image" class="brand-img">
                        </a>
                    @endforeach
                </div>
            </div>

        </section>
        @endif
        @endif

        {{-- VARIATIONS --}}
        @if($block = Block::init('recommend_variations'))
        @if($block->getData('variations')->count())
        <section class="home__section home__section--blue">
            <div class="home__section-bg"></div>
            <div class="swiper__product">
                <div class="swiper-top container">
                    <div class="swiper-top__wrapper">
                        <div class="title">{{ $block->getContent('title') }}</div>
                        {{--
                        <button class="btn--more">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#E25566"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M11.3 11.3V8H12.7V11.3H16V12.7H12.7V16H11.3V12.7H8V11.3H11.3Z" fill="white"/>
                            </svg>

                            Показати все
                        </button>
                        --}}
                    </div>

                    <div class="swiper-top__btn">
                        <div class="swiper-button-prev--block swiper__loop-button-prev">
                            <svg class="icon-svg icon-svg-left ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                            </svg>
                        </div>
                        <div class="swiper-button-next--block swiper__loop-button-next">
                            <svg class="icon-svg icon-svg-right ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="swiper-wrapper swiper__loop-wrapper">
                    @foreach($block->getData('variations') as $item)
                    <div class="swiper-slide swiper__product-slide">
                        @include('catalog.inc.variation-frame', ['variation' => $item])
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        @endif


        @if($block = Block::init('promotion_variations'))

        @if($block->getData('variations')->count())
        <section class="home__section home__section--orange">
            <div class="home__section-bg"></div>
            <div class="swiper__product">
                <div class="swiper-top container">
                    <div class="swiper-top__wrapper">
                        <div class="title">{{ $block->getContent('title') }}</div>
                        <a class="btn--more" href="/promotions">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#E25566"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M11.3 11.3V8H12.7V11.3H16V12.7H12.7V16H11.3V12.7H8V11.3H11.3Z" fill="white"/>
                            </svg>

                            Показати все
                        </a>
                    </div>

                    <div class="swiper-top__btn">
                        <div class="swiper-button-prev--block swiper__loop-button-prev">
                            <svg class="icon-svg icon-svg-left ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                            </svg>
                        </div>
                        <div class="swiper-button-next--block swiper__loop-button-next">
                            <svg class="icon-svg icon-svg-right ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="swiper-wrapper swiper__loop-wrapper">
                    @foreach($block->getData('variations') as $item)
                        <div class="swiper-slide swiper__product-slide">
                            @include('catalog.inc.variation-frame', ['variation' => $item])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        @endif


        @if($block = Block::init('hit_variations'))
        @if($block->getData('variations')->count())
        <section class="home__section home__section--green">
            <div class="home__section-bg"></div>
            <div class="swiper__product">
                <div class="swiper-top container">
                    <div class="swiper-top__wrapper">
                        <div class="title">{{ $block->getContent('title') }}</div>
                        {{--
                        <button class="btn--more">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#E25566"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M11.3 11.3V8H12.7V11.3H16V12.7H12.7V16H11.3V12.7H8V11.3H11.3Z" fill="white"/>
                            </svg>

                            Показати все
                        </button>
                        --}}
                    </div>

                    <div class="swiper-top__btn">
                        <div class="swiper-button-prev--block swiper__loop-button-prev">
                            <svg class="icon-svg icon-svg-left ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                            </svg>
                        </div>
                        <div class="swiper-button-next--block swiper__loop-button-next">
                            <svg class="icon-svg icon-svg-right ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="swiper-wrapper swiper__loop-wrapper">
                    @foreach($block->getData('variations') as $item)
                        <div class="swiper-slide swiper__product-slide">
                            @include('catalog.inc.variation-frame', ['variation' => $item])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        @endif

        {{-- BLOG --}}
        @if($block = Block::init('home_blog'))
        @if($block->getData('posts')->count())
        <section class="container home__blog">
            <div class="home__blog-top">
                <div class="title">{{ $block->getContent('title') }}</div>
                <a href="{{ route('blog.index') }}" class="btn--more">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#E25566"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M11.3 11.3V8H12.7V11.3H16V12.7H12.7V16H11.3V12.7H8V11.3H11.3Z" fill="white"/>
                    </svg>

                    Показати все
                </a>
            </div>
            <div class="home__blog-content">
                @foreach($block->getData('posts') as $item)
                    <a href="{{ $item->getUrlClient() }}"
                       class="home__blog-item @if($loop->index === 0) home__blog--first @endif">
                        <div class="home__blog-img">
                            <img
                                src="{{ $item->getFirstMediaUrl('image', 'big') ?: Theme::url('img/nophoto.webp') }}"
                                alt="{{ $item->name }}" class="home__blog-img">
                        </div>
                        <div class="home__blog-wrapper">
                            <p class="home__blog-date">{{ $item->getDatetime('created_at', 'd F Y') }}</p>
                            <p class="home__blog-text">
                                {{ $item->name }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif
        @endif


        @if($body = \Seo::getTags()['text'] ?? '')
            <section class="container about-section">
                <h1 class="title">Про home.mama</h1>
                <div class="about-section-text">
                    {!! $body !!}
                </div>
                <button class="btn--more about-section__btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#E25566"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M11.5059 14.4951L7.50586 10.4951L8.49581 9.50513L12.0008 13.0102L15.5059 9.50513L16.4958 10.4951L12.4958 14.4951C12.2224 14.7684 11.7792 14.7684 11.5059 14.4951Z"
                              fill="white"/>
                    </svg>
                    <span> Читати далі</span>

                </button>
            </section>
        @else
            <section class="container about-section">
                <h1 class="title">Про home.mama</h1>
                <div class="about-section-text">
                    <p>
                        Ласкаво просимо до home.mama️ - вашого найкращого вибору в світі модного
                        одягу для майбутніх мам! Наш інтернет-магазин пропонує найбільший
                        асортимент стильного одягу, спеціально розробленого для комфорту та
                        елегантності під час вагітності.
                    </p>
                    <p>
                        🤰 Комфорт Передусім: Кожна модель нашого одягу розроблена з урахуванням
                        зростання вашого животика. Використовуючи найкращі тканини та
                        технології, ми забезпечуємо максимальний комфорт на кожному етапі
                        вагітності.
                    </p>
                    <p>
                        🌟 Трендовий Стиль: Ми розуміємо, що бути вагітною - це особливий час у
                        вашому житті. Тому наші дизайнери врахували останні модні тенденції,
                        створюючи колекції, які допоможуть вам залишатися в тренді. Ви будете
                        виглядати неймовірно в кожному образі!
                    </p>
                    <p>
                        🤰 Комфорт Передусім: Кожна модель нашого одягу розроблена з урахуванням
                        зростання вашого животика. Використовуючи найкращі тканини та
                        технології, ми забезпечуємо максимальний комфорт на кожному етапі
                        вагітності.
                    </p>
                    <p>
                        🌟 Трендовий Стиль: Ми розуміємо, що бути вагітною - це особливий час у
                        вашому житті. Тому наші дизайнери врахували останні модні тенденції,
                        створюючи колекції, які допоможуть вам залишатися в тренді. Ви будете
                        виглядати неймовірно в кожному образі!
                    </p>
                    <p>
                        🤰 Комфорт Передусім: Кожна модель нашого одягу розроблена з урахуванням
                        зростання вашого животика. Використовуючи найкращі тканини та
                        технології, ми забезпечуємо максимальний комфорт на кожному етапі
                        вагітності.
                    </p>
                    <p>
                        🌟 Трендовий Стиль: Ми розуміємо, що бути вагітною - це особливий час у
                        вашому житті. Тому наші дизайнери врахували останні модні тенденції,
                        створюючи колекції, які допоможуть вам залишатися в тренді. Ви будете
                        виглядати неймовірно в кожному образі!
                    </p>
                </div>
                <button class="btn--more about-section__btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#E25566" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M11.5059 14.4951L7.50586 10.4951L8.49581 9.50513L12.0008 13.0102L15.5059 9.50513L16.4958 10.4951L12.4958 14.4951C12.2224 14.7684 11.7792 14.7684 11.5059 14.4951Z"
                              fill="white" />
                    </svg>
                    <span> Читати далі</span>

                </button>
            </section>
        @endif
    </main>
@endsection
