@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Catalog'),
        'canonical' => URL::full(),
    ])->setModel($term);

@endphp

@section('content')

    <main>
        <div class="bg-gray bg-gray--catalog"></div>

        <section class="catalog container">
            <div class="catalog__content">
                <div class="catalog__header ">
                    <div class="header-page">
                        {{ Breadcrumbs::render('catalog.category.show', $term) }}
                        <h1 class="title title--catalog">{{ isset($term) ? $term->name : trans('client.Catalog') }}</h1>
                    </div>
                </div>

                @if(count($categories))
                <div class="catalog__categories-grid">
                    @foreach($categories as $category)
                    <a href="{{ $category->getUrlClient() }}" class="card-category card-category--slider">
                        <img loading="lazy" src="{{ $category->getMyFirstMediaUrl('logo', 'preview') ?: Theme::url('img/img-error.png') }}" alt="{{ $category->name }}" class="card-category__img card-category__img--slider">
                        <span class="card-category__desc main-text">
                            {{ $category->name }}
                        </span>
                    </a>
                    @endforeach
                </div>
                @endif

                <div class="catalog__products ">
                    <div class="catalog__products-wrapper">
                        <div class="catalog__products-select js-filter-select" >

                            @include('catalog.inc.facet-filter')

                        </div>
                        <div class="catalog__products-items">
                            <div class="catalog__products-items-sort">
                                <div class="sort">
                                    <button type="button" class="main-btn main-btn--hide-1000 main-btn--green main-btn--width-100 sort__filter-btn main-text--small js-filter-btn" id="filterModal">
                                        Фільтр
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.61455 6.43853C2.32089 5.18899 3.20544 3 5.00402 3H17.0546C18.8532 3 19.7378 5.18899 18.4441 6.43853L12.0293 12.6346V20H10.0293V12.6346L3.61455 6.43853ZM11.0293 10.8198L17.0546 5H5.00402L11.0293 10.8198Z" fill="white"/>
                                        </svg>
                                    </button>

                                    @include('catalog.inc.catalog-view-settings')

                                </div>
                            </div>

                            <div class="catalog__products-items-grid js-perpage-source">
                                @include('catalog.inc.variations-list', ['variations' => $variations])
                            </div>

                            @if($variations->isEmpty())
                                <div class="search-fail">
                                    <div class="search-fail__logo">:(</div>
                                    <div class="main-text">За вашим пошуковим запитом нічого не знайдено</div>
                                </div>
                            @endif

                            @include('parts.pagination-navigation', ['items' => $variations])

                        </div>
                    </div>
                </div>
            </div>

            <div class="catalog__components">
                @include('parts.reviewed_variations')
            </div>
        </section>
    </main>

@endsection
