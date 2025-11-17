@extends('layouts.app')

@php
    Seo::setDefault(['title' => 'Особистий кабінет: Обрані']);
@endphp

@section('content')
    <main>

        <section class="personal-data favorite container">
            <div class="header-page header-page--personal">
                <!-- <nav class="header-page__breadcrumbs">
                    <ul class="breadcrumbs">
                        <li class="breadcrumbs__item main-text main-text--caption main-text--caption-mobile">
                            <a class="breadcrumbs__item-link" href="home.html">Головна</a>
                        </li>
                    </ul>
                </nav> -->
                <h1 class="title">Вибране</h1>
                <a href="" class="main-link" data-bs-target="#logOut" data-bs-toggle="modal" aria-label="openModal">Вийти з акаунту</a>
            </div>

            <div class="personal-data__content">

                @include('my.inc.aside')

                <div class="personal-data__wrapper">
                    @if(\Favorite::getQty())
                    <button aria-label="clearAll" class="main-btn main-btn--gray js-click-submit" data-url="{{ route('my.favorites.clear') }}">Очистити все</button>
                    <div class="favorite__grid">
                        @foreach($favorites as $favorite)
                            @include('catalog.inc.variation-frame', ['variation' => $favorite->model])
                        @endforeach
                    </div>
                    @else
                    <div class="search-fail">
                        <div class="search-fail__logo">:(</div>
                        <div class="search-fail__msg">У вас немає обраних</div>
                        <a href="{{ route('catalog.index') }}" class="main-btn main-btn--green main-btn--notfound">До покупок</a>
                    </div>
                    @endif
            </div>

            @include('my.inc.modal-exit')

        </section>

    </main>
@endsection
