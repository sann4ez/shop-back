@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Brands'),
    ]);

    if ($page = \App\Models\Page::whereSlug('page-brands')->withoutGlobalScopes()->first()) {
        Seo::setModel($page);
    }

@endphp

@section('content')
    <main class="default-page">
        <div class="brands container">
            <div class="brands-top">
                {{ Breadcrumbs::render('catalog.brands.index') }}

                <h1 class="title">Бренди</h1>
            </div>
            <div class="brands__wrapper">
                <div class="wrapper four-items">
                    @foreach($brands as $term)
                        <div class="brands-item">
                            <a class="brands-item__link" href="{{ $term->getUrlClient() }}">
                                <img class="brands-item__img" src="{{ $term->getMyFirstMediaUrl('logo', 'preview') ?: Theme::url('img/nophoto.webp') }}"
                                     alt="{{ $term->name }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
@endsection
