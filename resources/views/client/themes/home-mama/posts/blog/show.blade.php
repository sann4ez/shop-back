@extends('layouts.app')

@php
    Seo::setModel($post);
@endphp

@section('content')
    <main class="default-page">
        <div class="article container">
            <div class="about-top">
                {{ Breadcrumbs::render('blog.show', $post) }}
            </div>
            <div class="article__content">
                <h1 class="article__title">{{ $post->name }}</h1>
                <p class="article__date">{{ $post->getAuthor() ? $post->getDatetime('created_at', 'd F Y') . ' | ' . $post->getAuthor() : $post->getDatetime('created_at', 'd F Y') }}</p>
                <img src="{{ $post->getFirstMediaUrl('image', 'big') ?: Theme::url('img/nophoto.webp') }}" alt="article-page-img"
                     class="article__img"/>
                <div class="article__page">
                    <div class="article__wrapper typographics paragraph__body">
                        {!! $post->teaser !!}
                        {!! $post->body !!}
                    </div>
                    <aside class="contents">
                        <span class="contents-title">Навігація</span>
                        <ul class="contents-list"></ul>
                    </aside>
                </div>
            </div>

            <div class="dashed-line"></div>
            <div class="article__tags">
                @if($post->tags->count())
                    <div class="categories">
                        <p class="text text-gray">Теги</p>
                        @foreach($post->tags as $tag)
                            <a href="{{ $tag->getUrlClient() }}" class="category">#{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="article__interaction">
                <div class="article__interaction-content">
                    <div class="title--quest">Сподобалась стаття?</div>
                    <p class="text text-gray">Поділіться нею з іншими!</p>
                </div>
                <ul class="article__interaction-social">
                    <li>
                        <a class="js-social-share social-item" href="#" data-social="facebook">
                            <img src="{{ Theme::url('img/facebook.svg') }}" alt="Facebook">
                        </a>
                    </li>
                    <li>
                        <a class="js-social-share social-item" href="#" data-social="viber">
                            <img src="{{ Theme::url('img/Viber.svg') }}" alt="Viber">
                        </a>
                    </li>
                    <li>
                        <a class="js-social-share social-item" href="#" data-social="telegram">
                            <img src="{{ Theme::url('img/Telegram.svg') }}" alt="Telegram">
                        </a>
                    </li>
                </ul>
            </div>

            <div class="article__recomends">
                <div class="wrapper two-items">
                    <div class="article__recomends-item">
                        @if($prev = $post->getPrevModel())
                            <div class="article__recomends-head">
                                <a class="article__recomends-link" href="{{ $prev->getUrlClient() }}">
                                    <svg class="icon-svg icon-svg-left ">
                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                                    </svg>
                                    Попередня стаття
                                </a>
                            </div>
                            <a class="article__recomends-link" href="{{ $prev->getUrlClient() }}">
                                <p class="text">
                                    {{ $prev->name }}
                                </p>
                            </a>
                        @endif
                    </div>
                    <div class="article__recomends-item">
                        @if($next = $post->getNextModel())
                            <div class="article__recomends-head">
                                <a class="article__recomends-link revert" href="{{ $next->getUrlClient() }}">
                                    Наступна стаття
                                    <svg class="icon-svg icon-svg-right ">
                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use>
                                    </svg>
                                </a>
                            </div>
                            <a class="article__recomends-link" href="{{ $next->getUrlClient() }}">
                                <p class="text">
                                    {{ $next->name }}
                                </p>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($post->isCommentable())
        <div class="product__reviews">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                class="product__star-bg"
            >
                <path
                    d="M10 0L12.3607 7.25735H20L13.8197 11.7426L16.1803 19L10 14.5147L3.81966 19L6.18034 11.7426L0 7.25735H7.63932L10 0Z"
                    fill="#FFE9DC"
                />
            </svg
            >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                class="product__star-bg product__star-bg--bottom"
            >
                <path
                    d="M10 0L12.3607 7.25735H20L13.8197 11.7426L16.1803 19L10 14.5147L3.81966 19L6.18034 11.7426L0 7.25735H7.63932L10 0Z"
                    fill="#FFE9DC"
                />
            </svg>
            <div class="product__reviews-wrapper form">
                <div class="title">Додати відгук до статті</div>
                @include('posts.blog.inc.comments', ['post' => $post])
            </div>
        </div>
        @endif

        @php($relatedVariations = $post->getRelatedVariations())
        @if($relatedVariations->count())
            <div class="swiper__product">
                <div class="swiper-top container">
                    <div class="swiper-top__wrapper">
                        <div class="title">Зв'язані товари</div>
                        {{--<button class="btn--more">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#E25566"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M11.3 11.3V8H12.7V11.3H16V12.7H12.7V16H11.3V12.7H8V11.3H11.3Z" fill="white"/>
                            </svg>

                            Показати все
                        </button>--}}
                    </div>

                    <div class="swiper-top__btn">
                        <div class="swiper-button-prev--block swiper__loop-button-prev">
                            <svg class="icon-svg icon-svg-left ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                            </svg>
                        </div>
                        <div class="swiper-button-next--block swiper__loop-button-next">
                            <svg class="icon-svg icon-svg-right ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="swiper-wrapper swiper__loop-wrapper">
                    @foreach($relatedVariations as $item)
                        <div class="swiper-slide swiper__product-slide">
                            @include('catalog.inc.variation-frame', ['variation' => $item])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="article__similar container">
            <div class="title">Подібні статті</div>
            <div class="wrapper two-items">
            @foreach(\App\Models\Post::inRandomOrder()->where('id', '<>', $post->id)->with('media', 'translations', 'category')->limit(4)->get() as $post)
                @include('client.themes.home-mama.posts.blog.inc.frame')
            @endforeach
            </div>
        </div>
    </main>
@endsection
