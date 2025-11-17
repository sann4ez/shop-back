@extends('layouts.app')

@php
    \Seo::setModel($category);
@endphp

@section('content')
    <div role="main" class="main">
        <div class="container">

            {!! Breadcrumbs::render('catalog.category', $category) !!}
            
            @include('client.themes.pet.catalog.inc.categories-list', ['categories' => $categories, 'category' => $category])

            <ul class="nav sort-source mb-3" data-sort-id="products">
                <li class="nav-item" data-option-value="*"></li>
            </ul>
            <div class="sort-destination-loader sort-destination-loader-showing">
                <div class="portfolio-list sort-destination" data-sort-id="products">

                    @foreach($variations as $variation)
                        <div class="col-sm-6 col-md-4 p-0 isotope-item clothes">
                            <div class="product portfolio-item portfolio-item-style-2">
                                @include('catalog.inc.variation-frame-content', ['variation' => $variation])
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            @include('parts.pagination', ['items' => $variations])

        </div>
    </div>
    <div class="mb-5"></div>
@endsection
