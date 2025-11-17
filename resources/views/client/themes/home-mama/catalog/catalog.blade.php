@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Catalog'),
        'canonical' => URL::full(),
    ]);

   if ($page = \App\Models\Page::whereSlug('page-catalog')->withoutGlobalScopes()->first()) {
        Seo::setModel($page);
    }
@endphp

@section('content')
    <main class="default-page">
        <div class="catalog ">
            <div class="catalog-top container">
                {{ Breadcrumbs::render('catalog.index') }}

                <h1 class="title">{{ isset($category) ? $category->name : trans('client.Catalog') }}</h1>

                @if(count($categories))
                    <div class="categories_img">
                        <div class="categories-wrapper">
                            @include('catalog.inc.categories-list', ['categories' => $categories])
                        </div>
                        <div class="categories-swiper">
                            <div class="swiper-wrapper">
                                @foreach($categories as $category)
                                    <a href="{{ $category->getUrlClient() }}" class="category_img swiper-slide">
                                        <img src="{{ $category->getFirstMediaUrl('image', 'preview') ?: Theme::url('img/nophoto.webp') }}"
                                             alt="{{ $category->name }}" width="164" height="160"
                                             class="category-img">
                                        <span>{{ $category->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>
@endsection
