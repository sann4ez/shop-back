@extends('layouts.app')

@php
    Seo::setModel($page);
//dump(\Cache::has('categories'));
    if (\Cache::has('categories')) {
        $categories = \Cache::get('categories');
    } else {
        $categories = \App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
            ->with(['translations',
            'descendants.translations',
            'descendants.categoryProducts',
            'categoryProducts.variations.translations',
            ])
            ->whereIsRoot()->get();

        \Cache::add('categories', $categories, now()->addHours());
    }
@endphp

@section('content')

    <main>
        <section class="price container">
            <div class="price__content">
                <div class="header-page">
                    {{ Breadcrumbs::render('pages.show', $page) }}
                    <h1 class="title">{{ $page->name }}</h1>
                    <p class="main-text">Приймаємо замовлення на суму від 1000 грн. та не менше 10 одиниць одного найменування</p>
                </div>

                <div class="price__wrapper">
                    <div class="checkout__form-input price__form-input">
                        <label  class="checkout__form-text" >Категорія товару</label>
                        <div class="checkout__form-select-wrap">
                            <select aria-label="selectCategory" name="categoryPrice" id="categoryPrice" class="main-input main-input--checkout checkout__form-select" >
                                @foreach($categories as $category)

                                    @php($productExist = $category->descendants->filter(function ($descendant) {
                                        return $descendant->categoryProducts->isNotEmpty();
                                    })->isNotEmpty())

                                    @if($category->categoryProducts->count() || $productExist)
                                        <option value="{{ $category->id }}">Категорія. {{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="main-btn main-btn--green js-click-add-to-cart" data-url="{{ route('cart.sync') }}">Додати в корзину
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                            <g clip-path="url(#clip0_261_15867)">
                                <path d="M1.52029 0C1.24969 0 0.990177 0.105357 0.798836 0.292893C0.607495 0.48043 0.5 0.734784 0.5 1C0.5 1.26522 0.607495 1.51957 0.798836 1.70711C0.990177 1.89464 1.24969 2 1.52029 2H2.02227C2.24382 2.00038 2.45922 2.07142 2.63593 2.2024C2.81264 2.33338 2.94105 2.51717 3.00175 2.726L6.23811 13.826C6.42115 14.4524 6.80731 15.0034 7.33819 15.3956C7.86907 15.7878 8.51581 16 9.18062 16H18.504C19.1159 16.0001 19.7137 15.8205 20.2204 15.4843C20.7271 15.1481 21.1194 14.6708 21.3466 14.114L24.3544 6.742C24.478 6.43865 24.524 6.11024 24.4882 5.78546C24.4525 5.46068 24.3361 5.14943 24.1493 4.87892C23.9625 4.6084 23.7109 4.38685 23.4165 4.23364C23.1222 4.08042 22.794 4.00021 22.4607 4H5.49534L4.96275 2.176C4.78017 1.54955 4.39448 0.998372 3.86398 0.605793C3.33348 0.213214 2.687 0.000568736 2.02227 0H1.52029ZM8.20115 13.274L6.07894 6H22.4587L19.4509 13.372C19.3751 13.5574 19.2444 13.7163 19.0756 13.8282C18.9069 13.9401 18.7078 14 18.504 14H9.18062C8.95908 13.9996 8.74367 13.9286 8.56697 13.7976C8.39026 13.6666 8.26185 13.4828 8.20115 13.274ZM9.68261 24C10.0846 24 10.4826 23.9224 10.8539 23.7716C11.2253 23.6209 11.5627 23.3999 11.847 23.1213C12.1312 22.8427 12.3567 22.512 12.5105 22.1481C12.6643 21.7841 12.7435 21.394 12.7435 21C12.7435 20.606 12.6643 20.2159 12.5105 19.8519C12.3567 19.488 12.1312 19.1573 11.847 18.8787C11.5627 18.6001 11.2253 18.3791 10.8539 18.2284C10.4826 18.0776 10.0846 18 9.68261 18C8.87081 18 8.09227 18.3161 7.51824 18.8787C6.94422 19.4413 6.62174 20.2044 6.62174 21C6.62174 21.7956 6.94422 22.5587 7.51824 23.1213C8.09227 23.6839 8.87081 24 9.68261 24ZM9.68261 22C9.41201 22 9.15249 21.8946 8.96115 21.7071C8.76981 21.5196 8.66232 21.2652 8.66232 21C8.66232 20.7348 8.76981 20.4804 8.96115 20.2929C9.15249 20.1054 9.41201 20 9.68261 20C9.9532 20 10.2127 20.1054 10.4041 20.2929C10.5954 20.4804 10.7029 20.7348 10.7029 21C10.7029 21.2652 10.5954 21.5196 10.4041 21.7071C10.2127 21.8946 9.9532 22 9.68261 22ZM17.8449 24C18.2469 24 18.6449 23.9224 19.0163 23.7716C19.3876 23.6209 19.7251 23.3999 20.0093 23.1213C20.2935 22.8427 20.519 22.512 20.6728 22.1481C20.8266 21.7841 20.9058 21.394 20.9058 21C20.9058 20.606 20.8266 20.2159 20.6728 19.8519C20.519 19.488 20.2935 19.1573 20.0093 18.8787C19.7251 18.6001 19.3876 18.3791 19.0163 18.2284C18.6449 18.0776 18.2469 18 17.8449 18C17.0331 18 16.2546 18.3161 15.6806 18.8787C15.1065 19.4413 14.7841 20.2044 14.7841 21C14.7841 21.7956 15.1065 22.5587 15.6806 23.1213C16.2546 23.6839 17.0331 24 17.8449 24ZM17.8449 22C17.5743 22 17.3148 21.8946 17.1235 21.7071C16.9321 21.5196 16.8246 21.2652 16.8246 21C16.8246 20.7348 16.9321 20.4804 17.1235 20.2929C17.3148 20.1054 17.5743 20 17.8449 20C18.1155 20 18.375 20.1054 18.5664 20.2929C18.7577 20.4804 18.8652 20.7348 18.8652 21C18.8652 21.2652 18.7577 21.5196 18.5664 21.7071C18.375 21.8946 18.1155 22 17.8449 22Z" fill="#F2F2F2"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_261_15867">
                                    <rect width="24" height="24" fill="white" transform="translate(0.5)"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>

                <div class="price__grid">
                    @foreach($categories as $category)

                        @php($productExist = $category->descendants->filter(function ($descendant) {
                                      return $descendant->categoryProducts->isNotEmpty();
                                  })->isNotEmpty())

                        @if($category->categoryProducts->count() || $productExist)
                            <a href="{{ $category->getUrlClient() }}" target="_blank" id="{{ $category->id }}" class="price__row price__row--green title title--small">Категорія. {{ $category->name }}</a>
                        @endif

                        @foreach($category->categoryProducts->load('variations.translations', 'variations.properties.attribute.translations', 'variations.properties.translations') as $product)
                            @foreach($product->variations as $variation)
                                @include('pages.inc.variation-price-table', ['variation' => $variation])
                            @endforeach
                        @endforeach

                        @foreach($category->descendants->load('categoryProducts.variations.translations', 'categoryProducts.variations.properties.attribute.translations', 'categoryProducts.variations.properties.translations') as $subCategory)
                            @if($subCategory->categoryProducts->pluck('variations')->count())
                                <div class="price__row price__row--light-green main-text main-text--semibold">
                                    Підкатегорія. {{ $subCategory->name }}
                                </div>
                                @foreach($subCategory->categoryProducts as $product)
                                    @foreach($product->variations as $variation)
                                        @include('pages.inc.variation-price-table', ['variation' => $variation])
                                    @endforeach
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>

            @include('parts.reviewed_variations')
        </section>
    </main>

@endsection
