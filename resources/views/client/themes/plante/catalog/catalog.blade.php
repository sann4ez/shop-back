@extends('layouts.app')

@php
    Seo::setDefault([
       'title' => trans('client.Catalog'),
       'canonical' => URL::full(),
   ]);
@endphp

@section('content')
    <main>

        <section class="categories container">
            <div class="categories__content">
                <div class="header-page header-page--categories">
                    {{ Breadcrumbs::render('catalog.index') }}
                    <h1 class="title">{{ isset($category) ? $category->name : trans('client.Catalog') }}</h1>
                </div>

                <div class="categories__grid">
                    @foreach($categories as $category)
                    <a href="{{ $category->getUrlClient() }}" class="card-category">
                        <img loading="lazy" src="{{ $category->getMyFirstMediaUrl('logo', 'preview') ?: Theme::url('img/img-error.png') }}" alt="{{ $category->name }}" class="card-category__img">
                        <span class="card-category__desc main-text">
                            {{ $category->name }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection
