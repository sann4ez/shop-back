@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Catalog'),
        'canonical' => URL::full(),
    ])->setModel($term);

   $tags = $term->getSeoTags();

   // якщо застосовано фільтрування то використати:
   // title: Купити {category} {filter_value} в Луцьку, Україні | вигідна ціна в HomeMama
   // description: {category} {filter_value} за привабливою ціною в HomeMama ▶️ Описи та фото (products_count) товарів в каталозі для мам та малюків ✅ Вигідні ціни від {min_price} грн та гарантія якості 🚚 Доставка по Луцьку та Україні
    if ($facet2 = \FacetFilter::toArray(request()->get(\FacetFilter::getFilterUrlKey()))) {

        $props = array_merge(...array_values($facet2));
        $props = \App\Models\Eav\Property::whereIn('slug', $props)->get()->pluck('value')->toArray();
        $filter_value = implode(', ', $props);

        $tags['title'] = "Купити {$term->name} {$filter_value} в Луцьку, Україні | вигідна ціна в HomeMama";
        $tags['description'] = "Купити {$term->name} {$filter_value} за привабливою ціною в HomeMama ▶️ Описи та фото {$term->variations_count} товарів в каталозі для мам та малюків ✅ Вигідні ціни від {$term->strTokenPriceMin()} грн та гарантія якості 🚚 Доставка по Луцьку та Україні";
    }

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
        <div class="catalog container">
            <div class="catalog-top">

                {{ Breadcrumbs::render('catalog.category.show', $term) }}

                <h1 class="title">{{ isset($term) ? $term->name : trans('client.Catalog') }}</h1>

                @if(count($categories))
                <div class="categories_img">
                    <div class="categories-wrapper">
                        @include('catalog.inc.categories-list', ['categories' => $categories])
                    </div>
                    <div class="categories-swiper">
                        <div class="swiper-wrapper">
                        @foreach($categories as $category)
                            <a href="{{ $category->getUrlClient() }}" class="category_img swiper-slide">
                                <img src="{{ $category->getMyFirstMediaUrl('image', 'preview') ?: Theme::url('img/nophoto.webp') }}"
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
            <section class="catalog-page container">

                @include('catalog.inc.facet-filter')

                <div class="catalog__wrapper">
                    <div class="catalog__wrapper-top">
                        @include('catalog.inc.catalog-view-settings')
                    </div>
                    <div
                        class="wrapper js-perpage-source @if(request()->cookie('view') === 'list' || session('view') === 'list') six-items @endif">
                        @include('catalog.inc.variations-list', ['variations' => $variations])
                    </div>
                    @if($variations->isEmpty())
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
                        <p class="text">За даним пошуковим запитом нічого не знайдено</p>
                    </div>
                    @endif

                    @include('parts.pagination-navigation', ['items' => $variations])

                </div>
            </section>
            @if($body = $term->body)
                <section class="about-section">
                    <h2 class="title">{{ isset($term) ? $term->name : trans('client.Catalog') }}</h2>
                    <div class="about-section-text">
                        {!! $body !!}
                    </div>
                    <button class="btn--more about-section__btn">
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
                                d="M11.5059 14.4951L7.50586 10.4951L8.49581 9.50513L12.0008 13.0102L15.5059 9.50513L16.4958 10.4951L12.4958 14.4951C12.2224 14.7684 11.7792 14.7684 11.5059 14.4951Z"
                                fill="white"
                            />
                        </svg>
                        Читати далі
                    </button>
                </section>
            @endif
        </div>
    </main>
@endsection
