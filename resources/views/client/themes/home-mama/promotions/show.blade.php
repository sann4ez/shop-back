@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Promotion'),
        'canonical' => URL::full(),
    ]);

   $tags = $promotion->getSeoTags();

   $sorts = [
       ['title' => 'по замовчуванню', 'sort' => 'default', 'order' => 'desc'],
       ['title' => 'за новизною', 'order' => 'desc', 'sort' => 'income_at'],
       ['title' => 'за популярністю', 'order' => 'desc', 'sort' => 'rating'],
       ['title' => 'за ціною (дешевші)', 'order' => 'asc', 'sort' => 'price'],
       ['title' => 'за ціною (дорожчі)', 'order' => 'desc', 'sort' => 'price'],
   ];

   seo_suffix($tags, $sorts);

   Seo::setTags($tags);
@endphp

@section('content')
    <main class="default-page">
        <div class="promotions ">
            <div class="promotions-top container">
                {{ Breadcrumbs::render('promotions.show', $promotion) }}
            </div>

            <div class="promotions__wrapper container">
                <div class="promotions__block">
                    <img class="promotions__block-img" src="{{ $promotion->getFirstMediaUrl('image') ?: Theme::url('img/nophoto.webp') }}"
                         alt="promotion-page-img">
                    <div class="promotions__block-content">
                        <div class="promotions__block-info">
                            <h1 class="title">
                                {{ $promotion->name }}
                            </h1>
                            <p class="text text-gray">{{ $promotion->getDatePeriodStr('d F') }}</p>
                            @unless($promotion->is_dateless)
                            <div class="promotions__block-timing">
                                <div class="promotions__block-icon">
                                    <svg class="icon-svg icon-svg-timer ">
                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#timer"></use>
                                    </svg>
                                    <p class="text">Залишилося:</p>
                                </div>
                                <p class="promotions__block-mean">
                                <span class="promotions__block-mean--number">
                                    {{ $promotion->getTimeRemaining()['days'] }}
                                </span>
                                    дн
                                    <span>
                                    :
                                </span>
                                    <span class="promotions__block-mean--number">
                                    {{ $promotion->getTimeRemaining()['hours'] }}
                                </span>
                                    год
                                    <span>
                                    :
                                </span>
                                    <span class="promotions__block-mean--number">
                                    {{ $promotion->getTimeRemaining()['minutes'] }}
                                </span>
                                    хв
                                </p>
                            </div>
                            @endunless
                            @if($body = $promotion->body)
                            <div class="container about-section">
                                <div class="about-section-text">
                                    {!! $body !!}
                                </div>
                            </div>
                            <button class="btn--more about-section__btn">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <g id="more_arrow">
                                        <circle id="Ellipse 38" cx="12" cy="12" r="10" fill="#E25566"/>
                                        <path id="Vector 33 (Stroke)" fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.5078 14.4951L7.50781 10.4951L8.49776 9.50513L12.0028 13.0102L15.5078 9.50513L16.4978 10.4951L12.4978 14.4951C12.2244 14.7684 11.7812 14.7684 11.5078 14.4951Z"
                                              fill="white"/>
                                    </g>
                                </svg>
                                Читати далі
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <section class="catalog-page container">
{{--                @include('catalog.inc.facet-filter')--}}
            <div class="filter">
                <div class="accordion" id="accordionPanelsStayOpenExampleModal">
                    @if(isset($facet['brands']) && count($facet['brands']))
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                            aria-controls="panelsStayOpen-collapseOne">
                                        Бренди
                                        <svg class="icon-svg icon-svg-top ">
                                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <ul class="filter-list">
                                            @foreach($facet['brands'] as $brand)
                                                @if($brand->is_allowed)
                                                    <li class="filter-item">
                                                        <label class="checkbox-label js-click-url"
                                                               data-url="{{ \FacetFilter::build('brands', $brand->slug) }}">
                                                            <input type="checkbox"
                                                                   @if(\FacetFilter::has('brands', $brand->slug)) checked @endif
                                                                   id="prop-{{$brand->id}}">
                                                            <span class="checkmark"></span>

                                                            {{ $brand->name }}
                                                        </label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="accordion-item">
                            <form action="{{ \Request::url() }}" method="get">
                                <div class="accordion-header">
                                    <button
                                        class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseThree"
                                        aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapseThree"
                                    >
                                        Ціна
                                        <svg class="icon-svg icon-svg-top ">
                                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    id="panelsStayOpen-collapseThree"
                                    class="accordion-collapse collapse show"
                                >
                                    <div class="accordion-body">
                                        <div class="accordion-body__wrapper">
                                            <div
                                                id="polzunokPrice"
                                                data-min="{{ $facet['prices']['min'] ?? 0 }}"
                                                data-max="{{ $facet['prices']['max'] ?? 100000 }}"
                                                data-step="1"
                                                class="polzunok polzunokPrice"
                                            ></div>
                                            <div class="filter-inputs">
                                                <input
                                                    type="text"
                                                    name="price[from]"
                                                    value="{{ request('price.from', $facet['prices']['min'] ?? 0) }}"
                                                    class="polzunok-input-left polzunok-price polzunok-price-min"
                                                />
                                                <span class="default-text">-</span>
                                                <input
                                                    type="text"
                                                    name="price[to]"
                                                    value="{{ request('price.to', $facet['prices']['max'] ?? 100000) }}"
                                                    class="polzunok-input-left polzunok-price polzunok-price-max"
                                                />
                                                <button type="submit" class="btn--extern">ОК</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="dashed-line"></div>
                </div>
                <a href="{{ \FacetFilter::reset(['price']) }}" class="filter-reset link">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#E25566"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M13.688 8.38386C12.8603 7.9396 11.9035 7.79531 10.9809 7.97572C10.0583 8.15614 9.22735 8.65001 8.62937 9.37274C8.03143 10.0954 7.70339 11.0023 7.7008 11.9388C7.6982 12.8753 8.02121 13.784 8.61514 14.51C9.2091 15.236 10.0373 15.7344 10.9589 15.9199C11.8805 16.1054 12.838 15.9664 13.6682 15.5267C14.4983 15.087 15.1496 14.3741 15.5113 13.5096C15.6606 13.153 16.0707 12.9849 16.4273 13.1341C16.7839 13.2834 16.952 13.6935 16.8028 14.0501C16.3157 15.2139 15.4394 16.1728 14.3235 16.7639C13.2076 17.3549 11.9209 17.5416 10.6827 17.2924C9.44439 17.0431 8.33071 16.3733 7.53157 15.3965C6.73239 14.4196 6.29731 13.1963 6.3008 11.9349C6.30429 10.6736 6.74614 9.4527 7.55071 8.48028C8.35524 7.50789 9.47261 6.84416 10.7122 6.60175C11.9519 6.35934 13.2374 6.55314 14.3501 7.1503C14.9597 7.47751 15.4968 7.91477 15.9369 8.43513L16.3288 7.09257C16.4372 6.72146 16.8258 6.50845 17.1969 6.61678C17.5681 6.72512 17.7811 7.11379 17.6727 7.4849L16.8391 10.3405C16.7367 10.6911 16.3821 10.9041 16.0246 10.8297L13.2868 10.2603C12.9083 10.1816 12.6653 9.81097 12.744 9.43247C12.8227 9.05397 13.1934 8.81094 13.5719 8.88966L14.6655 9.11711C14.3808 8.82701 14.0518 8.57913 13.688 8.38386Z"
                              fill="white"/>
                    </svg>
                    Скинути всі фільтри</a>
            </div>

                <div class="catalog__wrapper">
                    <div class="catalog__wrapper-top">
                        @include('catalog.inc.catalog-view-settings')
                    </div>
                    <div class="wrapper js-perpage-source @if(request()->cookie('view') === 'list' || session('view') === 'list') six-items @endif">
                        @include('catalog.inc.variations-list', ['variations' => $variations])
                    </div>

                    @include('parts.pagination-show-more', ['items' => $variations])

                </div>
            </section>
            @if($block = Block::init('hit_variations'))
            @if($block->getData('variations')->count())
            <section class="search__swiper">
                <div class="swiper__product">
                    <div class="swiper-top container">
                        <div class="swiper-top__wrapper">
                            <div class="title">{{ $block->getContent('title') }}</div>
                            {{--<button class="btn--more">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" fill="#E25566"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M11.3 11.3V8H12.7V11.3H16V12.7H12.7V16H11.3V12.7H8V11.3H11.3Z"
                                          fill="white"/>
                                </svg>

                                Показати все
                            </button>--}}
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
        </div>
    </main>

    <!-- Modal Filter -->

    <div class="modal modal-default fade modal-filter" id="filterModal" aria-labelledby="filterModalLabel">
        <div class="modal-dialog  ">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body__head">
                        <div class="title">Фільтр</div>
                        <button class="btn--close" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <svg class="icon-svg icon-svg-exit ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                            </svg>
                        </button>
                    </div>
                    <div class="filter">
                        <div class="accordion" id="accordionPanelsStayOpenExampleModal">
                            @if(isset($facet['brands']) && count($facet['brands']))
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseOneModal" aria-expanded="true"
                                                aria-controls="panelsStayOpen-collapseOneModal">
                                            Бренди
                                            <svg class="icon-svg icon-svg-top ">
                                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="panelsStayOpen-collapseOneModal" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <ul class="filter-list">
                                                @foreach($facet['brands'] as $brand)
                                                    @if($brand->is_allowed)
                                                        <li class="filter-item js-click-url"
                                                            data-url="{{ \FacetFilter::build('brands', $brand->slug) }}">
                                                            <label class="checkbox-label">
                                                                <input type="checkbox"
                                                                       @if(\FacetFilter::has('brands', $brand->slug)) checked
                                                                       @endif
                                                                       id="prop-{{$brand->id}}">
                                                                <span class="checkmark"></span>

                                                                {{ $brand->name }}
                                                            </label>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="dashed-line"></div>
                            @endif
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseThreeModal" aria-expanded="true"
                                            aria-controls="panelsStayOpen-collapseThreeModal">
                                        Ціна
                                        <svg class="icon-svg icon-svg-top ">
                                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                        </svg>
                                    </button>
                                </div>
                                <form action="{{ \Request::url() }}" method="get">
                                    <div id="panelsStayOpen-collapseThreeModal" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="accordion-body__wrapper">
                                                <div id="polzunokPrice2"
                                                     data-min="{{ $facet['prices']['min'] ?? 0 }}"
                                                     data-max="{{ $facet['prices']['max'] ?? 100000 }}"
                                                     data-step="1"
                                                     class="polzunok polzunokPrice">
                                                </div>
                                                <div class="filter-inputs">
                                                    <input type="text"
                                                           name="price[from]"
                                                           value="{{ request('price.from', $facet['prices']['min'] ?? 0) }}"
                                                           class="polzunok-input-left polzunok-price2 polzunok-price-min2">
                                                    <span class="default-text">-</span>
                                                    <input type="text"
                                                           name="price[to]"
                                                           value="{{ request('price.to', $facet['prices']['max'] ?? 100000) }}"
                                                           class="polzunok-input-left polzunok-price2  polzunok-price-max2">
                                                    <button class="btn--extern">ОК</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="dashed-line"></div>
                        </div>
                        <button class="btn--extern modal-btn--bottom">Назад</button>
                        {{--                        <button type="submit" class="btn--intern modal-btn--bottom">Застосувати</button>--}}
                        <a href="{{ \FacetFilter::reset(['price']) }}" class="filter-reset link">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#E25566"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M13.688 8.38386C12.8603 7.9396 11.9035 7.79531 10.9809 7.97572C10.0583 8.15614 9.22735 8.65001 8.62937 9.37274C8.03143 10.0954 7.70339 11.0023 7.7008 11.9388C7.6982 12.8753 8.02121 13.784 8.61514 14.51C9.2091 15.236 10.0373 15.7344 10.9589 15.9199C11.8805 16.1054 12.838 15.9664 13.6682 15.5267C14.4983 15.087 15.1496 14.3741 15.5113 13.5096C15.6606 13.153 16.0707 12.9849 16.4273 13.1341C16.7839 13.2834 16.952 13.6935 16.8028 14.0501C16.3157 15.2139 15.4394 16.1728 14.3235 16.7639C13.2076 17.3549 11.9209 17.5416 10.6827 17.2924C9.44439 17.0431 8.33071 16.3733 7.53157 15.3965C6.73239 14.4196 6.29731 13.1963 6.3008 11.9349C6.30429 10.6736 6.74614 9.4527 7.55071 8.48028C8.35524 7.50789 9.47261 6.84416 10.7122 6.60175C11.9519 6.35934 13.2374 6.55314 14.3501 7.1503C14.9597 7.47751 15.4968 7.91477 15.9369 8.43513L16.3288 7.09257C16.4372 6.72146 16.8258 6.50845 17.1969 6.61678C17.5681 6.72512 17.7811 7.11379 17.6727 7.4849L16.8391 10.3405C16.7367 10.6911 16.3821 10.9041 16.0246 10.8297L13.2868 10.2603C12.9083 10.1816 12.6653 9.81097 12.744 9.43247C12.8227 9.05397 13.1934 8.81094 13.5719 8.88966L14.6655 9.11711C14.3808 8.82701 14.0518 8.57913 13.688 8.38386Z"
                                      fill="white"/>
                            </svg>
                            Скинути всі фільтри</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
