@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Promotion'),
        'canonical' => URL::full(),
    ]);
@endphp

@section('content')
    <main>

        <section class="shares container">
            <div class="shares__content">
                <div class="shares__header">
                    <div class="header-page">
                        <nav class="header-page__breadcrumbs">

                            {{ Breadcrumbs::render('promotions.show', $promotion) }}

                        </nav>
                        <!-- <h1 class="title">Лікарські культури (малий європакет)</h1> -->
                    </div>
                    <div class="shares__banner-wrapper">
                        <img loading="lazy" src="{{ $promotion->getFirstMediaUrl('image') ?: Theme::url('img/img-error.png') }}" alt="Banner" class="shares__banner-img">
                        <div class="shares__info-wrapper">
                            <h1 class="title title--green">{{ $promotion->name }}</h1>
                            <div class="shares__info-content">
                                @unless($promotion->is_dateless)
                                <span class="shares__info-text"><svg class="icon-svg icon-svg-timer "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#timer"></use></svg> Залишилося</span>
                                <span class="shares__info-time">{{ $promotion->getTimeRemaining()['days'] }}</span>
                                <span class="shares__info-text">дн</span>
                                <span class="shares__info-text">:</span>
                                <span class="shares__info-time">{{ $promotion->getTimeRemaining()['hours'] }}</span>
                                <span class="shares__info-text">год</span>
                                <span class="shares__info-text">:</span>
                                <span class="shares__info-time">{{ $promotion->getTimeRemaining()['minutes'] }}</span>
                                <span class="shares__info-text">хв</span>
                                @else
                                <span class="shares__info-text"><svg class="icon-svg icon-svg-timer "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#timer"></use></svg></span>
                                <span class="shares__info-text">Нескінченна</span>
                                @endunless
                            </div>
                            @if($body = $promotion->body)
                            <div class="about__wrapper read-more read-more--scroll">
                                <div class="about__text read-more__text typography typography--about">
                                    {!! $body !!}
                                </div>
                                <button class="read-more__btn main-btn main-btn--gray main-btn--about main-text main-text--semibold">
                                    <span class="read-more__desc">Читати далі</span>
                                    <span class="read-more__img">
                                        <svg class="icon-svg icon-svg-dropdown color-red dropdown-about"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#dropdown"></use></svg>
                                    </span>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="shares__products-wrapper">
                    <div class="shares__products-items">
                        <div class="shares__products-items-sort">
                            <div class="sort sort--shares">
                                @include('catalog.inc.catalog-view-settings')
                            </div>
                        </div>
                        <div class="shares__products-items-grid js-perpage-source">
                            @include('catalog.inc.variations-list', ['variations' => $variations])
                        </div>

                        @include('parts.pagination-navigation', ['items' => $variations])
                    </div>
                </div>
            </div>

            @include('parts.reviewed_variations')

        </section>

    </main>
@endsection
