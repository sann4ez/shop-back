@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Brand'),
        'canonical' => URL::full(),
    ])->setModel($term);

   $tags = $term->getSeoTags();

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
        <div class="search ">
            <div class="search-top container">
                {{ Breadcrumbs::render('catalog.brands.show', $term) }}

                <h1 class="title">{{ $term->name }}</h1>
            </div>

            <div class="dashed-line container"></div>
            <section class="search-page container">
                {{-- Фільтр --}}
                @include('catalog.inc.facet-filter')

                <div class="search__wrapper">
                    <div class="search__wrapper-top">
                        @include('catalog.inc.catalog-view-settings')
                    </div>
                    <!--   if change quantity cart, add class .six-items -->
                    @if(!$variations->isEmpty())
                        <div
                            class="wrapper @if(request()->cookie('view') === 'list' || session('view') === 'list') six-items @endif js-perpage-source">

                            @include('catalog.inc.variations-list', ['variations' => $variations])
                        </div>
                    @else
                        <div class="search-empty">
                            <svg width="200" height="200" viewBox="0 0 200 200" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <rect width="200" height="200" rx="100" fill="#F1F1F1"/>
                                <path
                                    d="M83.2496 91.9989C81.5858 91.9989 80.2949 91.5409 79.3769 90.625C78.459 89.6518 78 88.3638 78 86.7609C78 85.158 78.459 83.8986 79.3769 82.9826C80.2949 82.0094 81.5858 81.5228 83.2496 81.5228C84.9134 81.5228 86.2042 82.0094 87.1222 82.9826C88.0401 83.8986 88.4991 85.158 88.4991 86.7609C88.4991 88.3638 88.0401 89.6518 87.1222 90.625C86.2042 91.5409 84.9134 91.9989 83.2496 91.9989ZM83.2496 122.311C81.5858 122.311 80.2949 121.853 79.3769 120.937C78.459 119.964 78 118.676 78 117.073C78 115.47 78.459 114.211 79.3769 113.295C80.2949 112.321 81.5858 111.835 83.2496 111.835C84.9134 111.835 86.2042 112.321 87.1222 113.295C88.0401 114.211 88.4991 115.47 88.4991 117.073C88.4991 118.676 88.0401 119.964 87.1222 120.937C86.2042 121.853 84.9134 122.311 83.2496 122.311Z"
                                    fill="#1B1818"/>
                                <path
                                    d="M120.795 140C112.993 134.733 107.112 128.837 103.153 122.311C99.2519 115.842 97.3013 108.572 97.3013 100.5C97.3013 92.4283 99.2519 85.158 103.153 78.6891C107.112 72.163 112.993 66.2667 120.795 61L122 62.7174C117.181 66.9536 113.71 72.2775 111.587 78.6891C109.522 85.0435 108.489 92.3138 108.489 100.5C108.489 108.686 109.522 115.957 111.587 122.311C113.71 128.722 117.181 134.046 122 138.283L120.795 140Z"
                                    fill="#1B1818"/>
                            </svg>
                            <p class="text">Не знайдено продуктів з цим брендом</p>
                        </div>
                    @endif

                    <div>
                        @include('parts.pagination-navigation', ['items' => $variations])
                    </div>

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
@endsection
