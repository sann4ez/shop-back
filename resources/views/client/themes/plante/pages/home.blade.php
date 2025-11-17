@extends('layouts.app')

@php
    Seo::setModel($page);
@endphp

@section('content')

    <main>
        <section class="home">
            <div class="home__promo ">
                <div class="container container--no-padding">
                    <div class="home__promo-wrapper">
                        <div class="home__promo-category">
                            <div class="home__promo-category-list" aria-label="categories">
                                @include('parts.aside')
                            </div>
                            <div class="bg-gray"></div>
                        </div>

                        @if($block = Block::init('home_slider'))
                            @if($items = $block->getContentSort('items'))
                            <div class="home__slider swiper-container" aria-label="sliderGoods">

                                <button class="swiper-button-prev home__promo-arrow home__promo-arrow--left " aria-label="previousSlide">
                                    <svg class="icon-svg icon-svg-arrow-left color-red arrow-left"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-left"></use></svg>
                                </button>

                                <div class="swiper-wrapper">
                                    @foreach($items as $item)
                                        <a href="{{ $item['url'] ?: '#' }}" class="home__promo-slide-link swiper-slide" role="presentation">
                                            <img @if($loop->index !== 0) loading="lazy" @endif class=" home__promo-slide " src="{{ $item['img'] }}" alt="{{ $item['title'] }}" width="1119" height="575">
                                        </a>
                                    @endforeach
                                </div>

                                <button class="swiper-button-next home__promo-arrow home__promo-arrow--right " aria-label="nextSlide">
                                    <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                </button>

                                <div class="dots swiper-pagination" aria-label="slider navigation">
                                    <button class="dots__item swiper-pagination-bullet"></button>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="home__sale-wrapper ">
                @if($block = Block::init('hit_variations'))
                    @if($block->getData('variations')->count())
                    <div class="sale container" aria-label="sliderGoods">
                        <!-- <div class="container"> -->
                        <div class="sale__content">
                            <div class="sale__wrapper">
                                <div class="sale__header">
                                    <div class="sale__descr">
                                        <h2 class="title">{{ $block->getContent('title') }}</h2>
                                        <span class="special special--sale">Хіт продажів</span>
                                    </div>
                                    <div class="arrow">
                                        <button class="sale__arrow--left arrow__left" aria-label="previousSlide">
                                            <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                        </button>
                                        <button class="sale__arrow--right arrow__right" aria-label="nextSlide">
                                            <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="sale__products swiper-container" id="sale-slider-3">
                                <div class="swiper-wrapper">

                                    @foreach($block->getData('variations') as $item)
                                        @include('catalog.inc.variation-frame', ['variation' => $item])
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif

                @if($block = Block::init('discount_variations'))
                    @if($block->getData('variations') && count($block->getData('variations')))
                    <div class="sale container" aria-label="sliderGoods">
                        <div class="sale__content">
                            <div class="sale__wrapper">
                                <div class="sale__header">
                                    <div class="sale__descr">
                                        <h2 class="title">{{ $block->getContent('title') }}</h2>
                                        <span class="special special--discount">Вигідні пропозиції</span>
                                    </div>
                                    <div class="arrow">
                                        <button class="sale__arrow--left arrow__left" aria-label="previousSlide">
                                            <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                        </button>
                                        <button class="sale__arrow--right arrow__right" aria-label="nextSlide">
                                            <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="sale__products swiper-container" id="sale-slider-4">
                                <div class="swiper-wrapper">

                                    @foreach($block->getData('variations') as $item)
                                        @include('catalog.inc.variation-frame', ['variation' => $item])
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif

                @if($block = Block::init('novelty_variations'))
                    @if($block->getData('variations'))
                    <div class="sale container" aria-label="sliderGoods">
                        <!-- <div class="container"> -->
                        <div class="sale__content">
                            <div class="sale__wrapper">
                                <div class="sale__header">
                                    <div class="sale__descr">
                                        <h2 class="title">{{ $block->getContent('title') }}</h2>
                                        <span class="special special--new">NEW</span>
                                    </div>
                                    <div class="arrow">
                                        <button class="sale__arrow--left arrow__left" aria-label="previousSlide">
                                            <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                        </button>
                                        <button class="sale__arrow--right arrow__right" aria-label="nextSlide">
                                            <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="sale__products swiper-container" id="sale-slider-5">
                                <div class="swiper-wrapper">

                                    @foreach($block->getData('variations') as $item)
                                        @include('catalog.inc.variation-frame', ['variation' => $item])
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
            </div>

            <div class="container">

                <div class="about">
                    <div class="about__content">
                        <div class="about__wrapper read-more">
                            @if($body = $page->body)
                                <div class="about__text read-more__text typography typography--about">
                                    {!! $body !!}
                                </div>
                                <button aria-label="expended" class="read-more__btn main-btn main-btn--gray main-btn--about main-text main-text--semibold">
                                    <span class="read-more__desc">Читати далі</span>
                                    <span class="read-more__img">
                                        <svg class="icon-svg icon-svg-dropdown color-red dropdown-about"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#dropdown"></use></svg>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="about__benefits">
                            <div class="about__benefits-wrapper">
                                <div class="about__benefits-header">

                                    <h3 class="title--small">
                                        10 років успішної роботи – сотні задоволених клієнтів
                                    </h3>
                                </div>
                                <ul class="about__benefits-list">
                                    <li class="about__benefits-item main-text">грамотна консультація фахівців з асортименту</li>
                                    <li class="about__benefits-item main-text">поради практиків щодо застосування продукції</li>
                                    <li class="about__benefits-item main-text">доставка товару кур'єрськими службами по всій Україні</li>
                                    <li class="about__benefits-item main-text">гарантія високої якості кожного виду продукції</li>
                                    <li class="about__benefits-item main-text">найвигідніші ціни</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
