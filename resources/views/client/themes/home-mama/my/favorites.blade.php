@extends('layouts.app', ['bodyClass' => 'profile__page'])

@php
    $tags = ['title' => 'Особистий кабінет: Обрані'];

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
    <main class="default-page profile">
        <div class="profile__wrapper container">
            @include('my.inc.aside')
            <button class="profile__menu-btn" type="button" data-bs-toggle="modal" data-bs-target="#profilePage">
                <svg class="icon-svg icon-svg-like "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#like"></use></svg>
                    Обрані
                <svg class="icon-svg icon-svg-down "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#down"></use></svg>
            </button>
            <div class="profile__content">
                <div class="profile__top">
                    <h1 class="profile__title">Обрані ({{ $count = \Favorite::getQty() }})</h1>
                    @if($count > 0)
                        <button class="btn--intern js-click-submit" data-url="{{ route('my.favorites.add_to_cart') }}">
                            Додати все в кошик
                            <svg class="icon-svg icon-svg-cart_add ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#cart_add"></use>
                            </svg>
                        </button>
                        <button class="btn--extern" data-bs-toggle="modal" data-bs-target="#favoriteClearModal">
                            Очистити все
                            <svg class="icon-svg icon-svg-delete ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use>
                            </svg>
                        </button>
                    @endif
                </div>

                @if($count<= 0)
                    <div class="profile__empty">
                        <svg width="201" height="201" viewBox="0 0 201 201" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="200" height="200" rx="100" fill="#F1F1F1"/>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M49.4736 64.7652C43.3024 70.893 39.8359 79.2009 39.8359 87.8631C39.8359 96.5254 43.3024 104.833 49.4736 110.961C53.7417 115.199 76.5154 132.275 90.1552 142.404C96.3128 146.977 104.692 146.977 110.85 142.404C124.49 132.275 147.263 115.199 151.532 110.961C157.703 104.833 161.169 96.5254 161.169 87.8631C161.169 79.2009 157.703 70.893 151.532 64.7652C148.496 61.7241 144.883 59.31 140.902 57.6625C136.921 56.015 132.65 55.1667 128.336 55.1667C124.022 55.1667 119.751 56.015 115.769 57.6625C111.788 59.31 108.175 61.7241 105.14 64.7652L102.565 67.3302C101.423 68.4677 99.5824 68.468 98.44 67.3308L95.8629 64.7652C92.8276 61.7245 89.2149 59.3108 85.2337 57.6635C81.2526 56.0162 76.9818 55.1681 72.6682 55.1681C68.3547 55.1681 64.0839 56.0162 60.1027 57.6635C56.1215 59.3108 52.5089 61.7245 49.4736 64.7652Z"
                                  fill="#777777"/>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M48.105 82.4785C48.105 73.873 51.5981 65.6195 57.8166 59.5319C57.9409 59.4091 58.0661 59.2874 58.1923 59.1667C54.9932 60.7335 52.0722 62.8026 49.5475 65.2961C43.329 71.3837 39.8359 79.6372 39.8359 88.2427C39.8359 96.8482 43.329 105.102 49.5475 111.189C53.8484 115.4 76.7968 132.364 90.5413 142.426C96.7462 146.969 105.19 146.969 111.395 142.426C113.184 141.117 115.129 139.69 117.169 138.189C111.325 141.13 104.218 140.621 98.8104 136.662C85.0659 126.6 62.1175 109.636 57.8166 105.425C51.5981 99.3376 48.105 91.0841 48.105 82.4785Z"
                                  fill="#575555"/>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M136.948 104.5C137.823 102.294 138.304 99.8889 138.304 97.3714C138.304 86.6877 129.643 78.0269 118.959 78.0269C116.442 78.0269 114.037 78.5078 111.831 79.3827C114.669 72.2269 121.653 67.1667 129.819 67.1667C140.503 67.1667 149.164 75.8276 149.164 86.5113C149.164 94.6776 144.104 101.662 136.948 104.5Z"
                                  fill="#C6C6C6"/>
                        </svg>

                        <p class="profile__empty-text">У вас немає обраних</p>
                    </div>
                @endif
                <div class="wrapper js-perpage-source">
                @foreach($favorites as $favorite)
                    @include('catalog.inc.variation-frame', ['variation' => $favorite->model, 'key' => 'catalog'])
                @endforeach
                </div>

                @include('parts.pagination-navigation', ['items' => $favorites])

            </div>
        </div>
    </main>
@endsection

@push('modals')
    <!-- Modal Favorite Clear -->

    <div class="modal modal-default modal-logout fade" id="favoriteClearModal" aria-labelledby="favoriteClearModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="text">Ви впевнені, що хочете очистити? </p>
                    <div class="modal-body__actions">
                        <button class="btn--intern js-click-submit" data-url="{{ route('my.favorites.clear') }}">
                            Очистити
                            <svg class="icon-svg icon-svg-exit_door ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit_door"></use>
                            </svg>
                        </button>
                        <button class="btn--extern" data-bs-dismiss="modal" aria-label="Close">
                            Скасувати
                            <svg class="icon-svg icon-svg-exit ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
