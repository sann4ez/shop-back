@extends('layouts.app')

@php
    Seo::setModel($post);
@endphp

@section('content')

    <main>
        <div class="article container">
            {{ Breadcrumbs::render('blog.show', $post) }}
            <img loading="lazy" class="article__img" src="{{ $post->getFirstMediaUrl('image', 'big') ?: Theme::url('./img/img-error.png') }}" alt="{{ $post->name }}" />
            <section class="article__container">
                <div class="article__header">
                    <h1 class="article__title title title--article">{{ $post->name }}</h1>
                    <div class="article__info">
                        <p class="article__date">
                            <svg class="icon-svg icon-svg-date @@class"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#date"></use></svg> {{ $post->getDatetime('created_at', 'd F Y') }}
                        </p>
                        <p class="article__views">
                            <svg class="icon-svg icon-svg-eye-show @@class"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#eye-show"></use></svg> {{ $post->views_count }} перегляди
                        </p>
                    </div>
                </div>
                <aside class="contents">
                    <h4 class="contents-title title--small">Зміст</h4>
                    <div class="contents-list"></div>
                </aside>
                <p class="article__description">
                    {!! $post->teaser !!}
                </p>

                <div class="typography">
                    {!! $post->body !!}
                </div>

                @if($post->tags->count())
                <div class="article__category">
                    <p class="article__category-title">Теги:</p>
                    <ul class="chips" aria-label="chipsList">
                        @foreach($post->tags as $tag)
                        <li class="chips__item">
                            <a href="{{ $tag->getUrlClient() }}" class="chips__link main-text">#{{ $tag->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="article__share">
                    <div class="article__share-title">
                        <h3 class="title title--small">Сподобалась стаття?</h3>
                        <p class="main-text">Поділіться нею з іншими</p>
                    </div>
                    <div class="article__share-links">
                        <a href="#" class="article__share-link js-social-share" data-social="facebook">
                            <img loading="lazy" src="{{ Theme::url('img/Facebook.png') }}" alt="Facebook">
                        </a>
                        <a href="#" class="article__share-link js-social-share" data-social="telegram">
                            <img loading="lazy" src="{{ Theme::url('img/Telegram.png') }}" alt="Telegram">
                        </a>
                        <a href="#" class="article__share-link js-social-share" data-social="viber">
                            <img loading="lazy" src="{{ Theme::url('img/Viber.png') }}" alt="Viber">
                        </a>
                    </div>
                </div>
            </section>

            <div class="sale" aria-label="sliderGoods">
                <div class="sale__content">
                    <div class="sale__wrapper">
                        <div class="sale__header">
                            <div class="sale__descr">
                                <h2 class="title">Схожі статті</h2>
                            </div>
                            <div class="arrow">
                                <button class="sale__arrow--left arrow__left" aria-label="previousSlide">
                                    <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                </button>
                                <button class="sale__arrow--right arrow__right" aria-label="nextSlide">
                                    <svg class="icon-svg icon-svg-arrow-right color-red arrow-right"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#arrow-right"></use></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="sale__products swiper-container" id="sale-slider-6">

                        <div class="swiper-wrapper">

                            @foreach(\App\Models\Post::inRandomOrder()->where('id', '<>', $post->id)->with('media', 'translations', 'category')->limit(4)->get() as $post)
                                <div class="card swiper-slide">
                                    <a href="{{ $post->getUrlClient() }}" class="card__link" title="cart">
                                        <img loading="lazy" src="{{ $post->getFirstMediaUrl('image', 'preview') ?: Theme::url('img/img-error.png') }}" alt="{{ $post->name }}" class="card__img" onerror="this.onerror = null;this.src = './img/img-error.png'">
                                    </a>
                                    <div class="card__desc card__desc--article">
                                        <a href="{{ $post->getUrlClient() }}" class="card__name main-text">{{ $post->name }}</a>
                                    </div>
                                    <div class="card__date">
                                        {{ $post->getDatetime('created_at', 'd F Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
