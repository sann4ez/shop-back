@extends('layouts.app')

@php
    Seo::setModel($variation->product)
        ->setTags($variation->getSeoTags());

    if (!in_array($variation->id, session()->get('viewed_variations', []))) {
        session()->push('viewed_variations', $variation->id);
    }
@endphp

@section('content')

    <main>
        <section class="single container">

            <div class="single__content">

                <div class="single__block">

                    <div class="header-page">
                        {{ Breadcrumbs::render('catalog.variation.show', $variation) }}
                    </div>

                    <div class="single__product">

                        <div class="single__product-slider-wrapper">

                            @php($images = $variation->getImages())
                            <div class="single__product-slider swiper-container mySwiper">
                                @if($images->count() > 1)
                                    <button class="swiper-button-prev home__promo-arrow home__promo-arrow--left">
                                        <svg class="icon-svg icon-svg-arrow-left color-red arrow-left"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-left"></use></svg>
                                    </button>
                                @endif

                                <div class="swiper-wrapper">
                                    @forelse($images as $media)
                                        <img loading="lazy" src="{{ $media->getUrl('big') }}" alt="{{ $media->name }}" class="single__product-slide swiper-slide">
                                    @empty
                                        <img loading="lazy" src="{{ Theme::url('./img/img-error.png') }}" alt="slide" class="single__product-slide swiper-slide">
                                    @endforelse
                                </div>

                                @if($images->count() > 1)
                                        <button class="swiper-button-next home__promo-arrow home__promo-arrow--right ">
                                            <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                        </button>
                                @endif
                            </div>


                            <div class="single__product-slider swiper-container single__product-slider-2 mySwiper2">
                                <div class="swiper-wrapper">
                                    @forelse($images as $media)
                                        <div class="single__product-slide-thumb swiper-slide">
                                            <img loading="lazy" src="{{ $media->getUrl('big') }}" alt="{{ $media->name }}" class="single__product-slide ">
                                        </div>
                                    @empty
                                        <div class="single__product-slide-thumb swiper-slide">
                                            <img loading="lazy" src="{{ Theme::url('./img/img-error.png') }}" alt="slide" class="single__product-slide swiper-slide">
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>




                        <form method="POST" action="{{ route('cart.add', $variation) }}" class="single__product-wrapper">
                            @csrf
                            <div class="single__product-header">
                                <h1 class="title">{{ $variation->getName() }}</h1>
                                <div class="single__product-header-block">
                                    <div class="single__product-available main-text main-text--caption">
                                    @switch($variation->getAvailableStatus())
                                        @case('missing')
                                            <div class="single__product-available-false">Немає в наявності</div>
                                            @break
                                        @case('terminate')
                                        @case('available')
                                        @case('unlimited')
                                            <div class="single__product-available-true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="13" viewBox="0 0 11 13" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.02609 12.4644L5.02656 12.4627L5.02976 12.4509L5.04441 12.3984C5.05786 12.3507 5.07868 12.2783 5.10703 12.1836C5.16375 11.994 5.25048 11.7154 5.36837 11.3673C5.60442 10.6702 5.96402 9.69861 6.45594 8.60713C7.44814 6.40563 8.94228 3.79904 10.9821 1.94702L9.63768 0.466293C7.30248 2.5865 5.67162 5.47991 4.63257 7.78535C4.29901 8.52545 4.02271 9.21345 3.80086 9.80793C3.12115 8.97259 2.09362 8.06958 0.619764 7.58922L0 9.49077C1.14827 9.86502 1.9281 10.6171 2.43208 11.307C2.68303 11.6505 2.85839 11.9693 2.9695 12.1986C3.0248 12.3128 3.06345 12.4033 3.08702 12.4616C3.09879 12.4907 3.10674 12.5116 3.11107 12.5233L3.11467 12.5332L3.11478 12.5334L3.11487 12.5337M5.026 12.4648L5.02609 12.4644L5.026 12.4648Z" fill="#2A8927"/>
                                                </svg>
                                                В наявності
                                            </div>
                                            @break
                                    @endswitch
                                    </div>
                                    <div class="single__product-desc main-text main-text--caption">Код: {{ $variation->getSku() }}</div>
                                    <div class="single__product-desc main-text main-text--caption">@if($brand = $variation->product?->brand?->name) Виробник: {{ $brand }}@endif</div>
                                </div>
                            </div>
                            @foreach($switching as $attr)
                                @if($attr['attribute']['has_image'])
                                    {{--TODO--}}
                                @else
                                    <div class="single__product-select">
                                        <h4 class="single__product-select-title">{{ $attr['attribute']['name'] }}</h4>
                                        <div class="single__product-select-wrapper">
                                            @foreach($attr['properties'] as $prop)
                                            <a href="{{ $prop['variation']['url'] }}" class="single__product-select-label @if($prop['is_current']) single__product-select-label--active @endif main-text">
                                                {{ $prop['property']['value'] }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="single__product-add single__product-add--price">
                                <div class="single__product-add-price">
                                    <div class="single__product-price-text title title--small">Ціна:</div>
                                    <div class="single__product-price">
                                    <div class="single__product-price-num title title--medium">{{$variation->getPrice()}}</div>
                                    <div class="single__product-price-curr title title--medium">грн</div>
                                </div>
                            </div>
                                <button type="button" class="card__like @if($variation->isFavorite()) card__like--active @endif js-card-like js-click-submit" data-url="{{ route('my.favorites.store', $variation) }}" aria-label="cart">
                                    <svg class="icon-svg icon-svg-card-like color-red user-like card-like"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#card-like"></use></svg>
                                </button>
                            </div>
                            <div class="single__product-buy">
                                <div class="single__product-add single__product-add--btns single__product-add-amount">
                                    <div class="price__counter js-counter-wrapper">
                                        <button type="button" class="main-btn main-btn--counter main-btn--counter-locked js-counter-decrease">-</button>
                                        <input name="quantity" type="number" class="price__amount main-input main-input--add js-counter-input" value="{{ $variation->getMinQty() }}" min="{{ $variation->stock_qty >= $variation->getMinQty() ? $variation->getMinQty() : 0 }}" max="{{ $variation->getMaxQty() <= 0 ? 0 : $variation->getMaxQty() }}" step="{{ $variation->getStep() }}" placeholder="0" autocomplete="off">
                                        <button type="button" class="main-btn main-btn--counter js-counter-increase">+</button>
                                    </div>
                                    <button class="main-btn main-btn--green main-btn--width100 main-btn--add main-text main-text--semibold">
                                        @if($variation->inCart())
                                            Купити {{--ще--}}
                                        @else
                                            Купити
                                        @endif
                                        <svg class="icon-svg icon-svg-cart cart-single"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#cart"></use></svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="single__wrapper">
                    <div class="single__product-desc">
                        <div class="single__product-desc-content">
                            <h2 class="title title--small">Опис</h2>
                            <div class="single__product-desc-text main-text main-text--color-stat typography typography--about">
                                {!! $variation->getBody() !!}
                            </div>

                        </div>
                        <div class="single__product-desc-content single__product-desc-content--no-bg">
                            <h2 class="title title--small">Характеристики</h2>
                            @if($brand = $variation->product?->brand?->name)
                                <div class="single__product-stat">
                                    <div class="single__product-stat-desc main-text">Виробник</div>
                                    <div class="single__product-stat-devider"></div>
                                    <div class="single__product-stat-value main-text main-text--color-stat">{{ $brand }}</div>
                                </div>
                            @endif
                            @foreach($variation->getAttributesPropertiesListArray('name', 'value') as $attr => $props)
                                <div class="single__product-stat">
                                    <div class="single__product-stat-desc main-text">{{ $attr }}</div>
                                    <div class="single__product-stat-devider"></div>
                                    <div class="single__product-stat-value main-text main-text--color-stat">{{ implode(', ', array_unique($props)) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @if($block = Block::init('similar_goods'))
                @if($block->getData('variations')->count())
                <div class="sale">
                    <!-- <div class="container"> -->
                    <div class="sale__content">
                        <div class="sale__wrapper">
                            <div class="sale__header">
                                <div class="sale__descr">
                                    <h2 class="title">{{ $block->getContent('title') }}</h2>
                                </div>
                                <div class="arrow">
                                    <button class="sale__arrow--left arrow__left">
                                        <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                    </button>
                                    <button class="sale__arrow--right arrow__right">
                                        <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="sale__products swiper-container" id="sale-slider-2">

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

            @include('parts.reviewed_variations')

        </section>
    </main>

@endsection
