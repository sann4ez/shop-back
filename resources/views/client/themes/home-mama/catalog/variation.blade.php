@extends('layouts.app')

@php
    Seo::setModel($variation)
        ->setTags(array_merge($variation->getSeoTags(), array_filter(\Seo::getSeopath()?->getSeoTags() ?: [])));

    if (!in_array($variation->id, session()->get('viewed_variations', []))) {
        session()->push('viewed_variations', $variation->id);
    }
@endphp

@section('content')
    <main class="default-page">
        <div class="product container">
            <div class="swiper__gallery">
                @php($images = $variation->getImages())
                <div class="swiper-container swiper__gallery-top">
                    <div class="swiper-wrapper">
                        @if($url = $variation->product->getFields('video_url'))
                        <div class="swiper-slide">
                            <iframe  src="{{ $url }}" allowfullscreen class="iframe-video">
                            </iframe>
                        </div>
                        @endif
                        @forelse($images as $media)
                            <button class="swiper-slide">
                                <img src="{{ $media->getUrl('big') }}" alt="{{ $media->name }}"
                                     width="488" height="700">
                            </button>
                        @empty
                            <a href="{{ $variation->getUrlClient() }}" class="swiper-slide">
                                <img
                                    src="{{ Theme::url('img/nophoto.webp') }}"
                                    alt="Image"
                                />
                            </a>
                        @endforelse
                    </div>

                    <div class="swiper-pagination swiper__gallery-pagination"></div>

                    <div class="swiper-button-prev swiper__product-button-prev">
                        <svg class="icon-svg icon-svg-left ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#left"></use>
                        </svg>
                    </div>
                    <div class="swiper-button-next swiper__product-button-next">
                        <svg class="icon-svg icon-svg-right ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#right"></use>
                        </svg>
                    </div>
                </div>

                <div class="swiper-container swiper__gallery-thumbs">
                    <div class="swiper-wrapper">
                        @if($url = $variation->product->getFields('video_url'))
                            <div class="swiper-slide video">
                                <img src="{{ Theme::url('img/nophoto.webp') }}"
                                     alt="#" width="80" height="100">
                            </div>
                        @endif
                        @forelse($images as $media)
                            <div class="swiper-slide">
                                <img
                                    src={{ $media->getUrl('big') }}
                                    alt="{{ $media->name }}"
                                />
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <img
                                    src="{{ Theme::url('img/nophoto.webp') }}"
                                    alt="Image"
                                />
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="product__content">
                <div class="product__content-top">

                    {{ Breadcrumbs::render('catalog.variation.show', $variation) }}

                    <h1 class="title">{{ $variation->getName() }}</h1>
                    <div class="product__header-rating">
                        @switch($variation->getAvailableStatus())
                            @case('missing')
                                <span class="product__header-label product__header-label--available disabled">Немає в наявності</span>
                                @break
                            @case('terminate')
                                <span class="product__header-label ending">Закінчується (залишилося {{$variation->stock_qty}}шт)</span>
                                @break
                            @case('available')
                                <span
                                    class="product__header-label">В наявності (залишилося {{$variation->stock_qty}}шт)</span>
                                @break
                            @case('unlimited')
                                <span class="product__header-label">В наявності</span>
                                @break
                        @endswitch
                        <div class="product-rating__content">
                            <div class="product-rating rating" data-rating="{{ $variation->getRating() }}"></div>
                            <span>{{ $variation->getCommentsCount() }} відгуків</span>
                        </div>
                    </div>
                </div>
                @foreach($switching as $attr)
                    @if($attr['attribute']['has_image'])
                        <div class="product__color">
                            <span class="product-label">Колір</span>
                            <ul class="radio-list color-list">
                                @foreach($attr['properties'] as $prop)
                                    @php($propVariation = $prop['variation'])
                                    <li class="radio-item @if($prop['is_current']) active @endif @if($propVariation['stock_qty'] < 1) disabled @endif" @if($propVariation['stock_qty'] < 1) title="Товара немає в наявності" @endif>
                                        <a href="{{ $propVariation['url'] }}" class="radio-link">
                                            @if($url = $prop['property']['image'])
                                                <img src="{{ $url }}"
                                                     alt="{{ $prop['property']['value'] }}"/>
                                            @else
                                                <span>{{ $prop['property']['value'] }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="product__size">
                            <span class="product-label">{{ $attr['attribute']['name'] }}</span>
                            <ul class="radio-list">
                                @foreach($attr['properties'] as $prop)
                                    @php($propVariation = $prop['variation'])
                                    <li class="radio-item @if($prop['is_current']) active @endif @if($propVariation['stock_qty'] < 1) disabled @endif" @if($propVariation['stock_qty'] < 1) title="Товара немає в наявності" @endif>
                                        <a href="{{ $prop['variation']['url'] }}"
                                           class="radio-link">{{ $prop['property']['value'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach

                {{--<button class="link--underline">Таблиця розмірів</button>--}}

                <div class="product-price__wrapper">
                    @if($variation->getPriceOld())
                        <div class="card-price--old"><span class="js-num-format">{{$variation->getPriceOld()}}</span> грн</div>
                        <div class="product__price card-price new"><span class="js-num-format">{{$variation->getPrice()}}</span> грн</div>
                    @else
                        <div class="product__price card-price"><span class="js-num-format">{{ $variation->getPrice() }}</span> грн</div>
                    @endif
                </div>

                <div class="product__btn">
                    {{--                    <form action="{{ route('cart.add', $variation) }}" method="post">--}}
                    {{--                        @csrf--}}
                    <button class="btn--intern js-click-submit" @if($variation->getAvailableStatus() === 'missing') disabled style="opacity: .5" @endif data-url="{{ route('cart.add', $variation) }}">
                        @if($variation->inCart())
                            Товар в кошику
                        @else
                            Додати в кошик
                        @endif
                        <svg class="icon-svg icon-svg-cart_add ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#cart_add"></use>
                        </svg>
                    </button>
                    {{--                    </form>--}}
                    <button class="btn--extern js-click-submit" data-url="{{ route('cart.quickBuy', $variation) }}" @if($variation->getAvailableStatus() === 'missing') disabled style="opacity: .5" @endif >Купити в 1 клік</button>
                    @if($variation->isFavorite())
                        <button class="product__btn--favorite active js-click-submit" data-url="{{ route('my.favorites.store', $variation) }}">
                            <svg class="icon-svg icon-svg-heart-full ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#heart-full"></use>
                            </svg>
                            <span class="text">Додано в обране</span>
                        </button>
                    @else
                        <button class="product__btn--favorite js-click-submit" data-url="{{ route('my.favorites.store', $variation) }}">
                            <svg class="icon-svg icon-svg-default ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#default"></use>
                            </svg>
                            <span class="text">Додати в обране</span>
                        </button>
                    @endif
                </div>
                <ul class="product__list">
                    <li class="product__item">
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <circle cx="7.5" cy="7.5" r="7.5" fill="#F1F1F1"/>
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M5 7.5C4.17157 7.5 3.5 8.17157 3.5 9V16.5C3.5 17.3284 4.17157 18 5 18H16V9C16 8.17157 15.3284 7.5 14.5 7.5H5ZM17 18H20.5C21.3284 18 22 17.3284 22 16.5V14.5H17V18ZM17 13.5H20.8486L17 10.9343V13.5ZM17 9.73241V9C17 7.61929 15.8807 6.5 14.5 6.5H5C3.61929 6.5 2.5 7.61929 2.5 9V16.5C2.5 17.8807 3.61929 19 5 19H20.5C21.8807 19 23 17.8807 23 16.5V14C23 13.8328 22.9164 13.6767 22.7773 13.584L17 9.73241Z"
                                fill="#777777"
                            />
                            <path
                                d="M9 19.5C9 20.8807 7.88071 22 6.5 22C5.11929 22 4 20.8807 4 19.5C4 18.1193 5.11929 17 6.5 17C7.88071 17 9 18.1193 9 19.5Z"
                                fill="white"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M6.5 21C7.32843 21 8 20.3284 8 19.5C8 18.6716 7.32843 18 6.5 18C5.67157 18 5 18.6716 5 19.5C5 20.3284 5.67157 21 6.5 21ZM6.5 22C7.88071 22 9 20.8807 9 19.5C9 18.1193 7.88071 17 6.5 17C5.11929 17 4 18.1193 4 19.5C4 20.8807 5.11929 22 6.5 22Z"
                                fill="#777777"
                            />
                            <path
                                d="M22 19.5C22 20.8807 20.8807 22 19.5 22C18.1193 22 17 20.8807 17 19.5C17 18.1193 18.1193 17 19.5 17C20.8807 17 22 18.1193 22 19.5Z"
                                fill="white"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M19.5 21C20.3284 21 21 20.3284 21 19.5C21 18.6716 20.3284 18 19.5 18C18.6716 18 18 18.6716 18 19.5C18 20.3284 18.6716 21 19.5 21ZM19.5 22C20.8807 22 22 20.8807 22 19.5C22 18.1193 20.8807 17 19.5 17C18.1193 17 17 18.1193 17 19.5C17 20.8807 18.1193 22 19.5 22Z"
                                fill="#777777"
                            />
                        </svg>
                        Доставка у відділення Нова Пошта, Укрпошта
                    </li>
                    <li class="product__item">
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <circle cx="7.5" cy="7.5" r="7.5" fill="#F1F1F1"/>
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M21.7672 11.8412L7.73986 3.74249C7.26157 3.46635 6.64998 3.63022 6.37384 4.10852L2.86546 10.1852C2.58931 10.6635 2.75319 11.2751 3.23148 11.5512L17.2588 19.6499C17.7371 19.9261 18.3487 19.7622 18.6249 19.2839L22.1332 13.2072C22.4094 12.7289 22.2455 12.1173 21.7672 11.8412ZM8.23986 2.87646C7.28328 2.32418 6.0601 2.65193 5.50781 3.60852L1.99943 9.68521C1.44715 10.6418 1.7749 11.865 2.73148 12.4173L16.7588 20.516C17.7154 21.0682 18.9386 20.7405 19.4909 19.7839L22.9993 13.7072C23.5516 12.7506 23.2238 11.5274 22.2672 10.9752L8.23986 2.87646Z"
                                fill="#777777"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M11.1623 14.0103C12.4404 14.7482 14.0748 14.3103 14.8127 13.0322C15.5506 11.7541 15.1127 10.1198 13.8346 9.38184C12.5564 8.64392 10.9221 9.08183 10.1842 10.36C9.44627 11.6381 9.88419 13.2724 11.1623 14.0103ZM10.6623 14.8763C12.4187 15.8904 14.6646 15.2886 15.6787 13.5322C16.6928 11.7758 16.091 9.52988 14.3346 8.51581C12.5782 7.50175 10.3322 8.10354 9.31817 9.85995C8.30411 11.6164 8.9059 13.8623 10.6623 14.8763Z"
                                fill="#777777"
                            />
                            <path
                                d="M7.72876 8.94213C7.47524 9.38124 6.91376 9.53168 6.47466 9.27817C6.03556 9.02465 5.88511 8.46317 6.13862 8.02407C6.39214 7.58497 6.95362 7.43452 7.39272 7.68804C7.83183 7.94155 7.98227 8.50303 7.72876 8.94213Z"
                                fill="#1B1818"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M6.97466 8.41214C6.93547 8.38952 6.88536 8.40294 6.86273 8.44213C6.84011 8.48132 6.85353 8.53143 6.89272 8.55406C6.93191 8.57669 6.98202 8.56326 7.00465 8.52407C7.02728 8.48488 7.01385 8.43477 6.97466 8.41214ZM6.47466 9.27817C6.91376 9.53168 7.47524 9.38124 7.72876 8.94213C7.98227 8.50303 7.83183 7.94155 7.39272 7.68804C6.95362 7.43452 6.39214 7.58497 6.13862 8.02407C5.88511 8.46317 6.03556 9.02465 6.47466 9.27817Z"
                                fill="#777777"
                            />
                            <path
                                d="M18.8596 15.3682C18.6061 15.8073 18.0446 15.9577 17.6055 15.7042C17.1664 15.4507 17.016 14.8892 17.2695 14.4501C17.523 14.011 18.0845 13.8605 18.5236 14.1141C18.9627 14.3676 19.1131 14.9291 18.8596 15.3682Z"
                                fill="#1B1818"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M18.1055 14.8382C18.0663 14.8155 18.0162 14.829 17.9936 14.8682C17.971 14.9073 17.9844 14.9575 18.0236 14.9801C18.0628 15.0027 18.1129 14.9893 18.1355 14.9501C18.1581 14.9109 18.1447 14.8608 18.1055 14.8382ZM17.6055 15.7042C18.0446 15.9577 18.6061 15.8073 18.8596 15.3682C19.1131 14.9291 18.9627 14.3676 18.5236 14.1141C18.0845 13.8605 17.523 14.011 17.2695 14.4501C17.016 14.8892 17.1664 15.4507 17.6055 15.7042Z"
                                fill="#777777"
                            />
                        </svg>
                        Оплата при отриманні та онлайн
                    </li>
                    <li class="product__item">
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <circle cx="7.5" cy="7.5" r="7.5" fill="#F1F1F1"/>
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M10.3536 3.64645C10.5488 3.84171 10.5488 4.15829 10.3536 4.35355L6.20711 8.5H17.7929L13.6464 4.35355C13.4512 4.15829 13.4512 3.84171 13.6464 3.64645C13.8417 3.45118 14.1583 3.45118 14.3536 3.64645L19.5 8.79289V19C19.5 19.8284 18.8284 20.5 18 20.5H6C5.17157 20.5 4.5 19.8284 4.5 19V8.79289L9.64645 3.64645C9.84171 3.45118 10.1583 3.45118 10.3536 3.64645ZM18.5 9.5H16V12.5C16 13.3284 15.3284 14 14.5 14H9.5C8.67157 14 8 13.3284 8 12.5V9.5H5.5V19C5.5 19.2761 5.72386 19.5 6 19.5H18C18.2761 19.5 18.5 19.2761 18.5 19V9.5ZM9 9.5V12.5C9 12.7761 9.22386 13 9.5 13H14.5C14.7761 13 15 12.7761 15 12.5V9.5H9Z"
                                fill="#777777"
                            />
                        </svg>
                        Відправлення протягом 1-2 днів
                    </li>
                </ul>
            </div>

            <div class="product__desc">
                <div class="product__desc-title">Опис товару</div>
                <div class="typographics  typographics--product">
                    {!! $variation->getBody() !!}
                </div>
            </div>
        </div>

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
                <div class="title">Додати відгук до товару</div>
                @include('catalog.inc.comments', ['variation' => $variation])
            </div>
        </div>

        @php($recommends = $variation->getRecommends(true))
        @if($recommends->count())
        <div class="swiper__product">
            <div class="swiper-top container">
                <div class="swiper-top__wrapper">
                    <div class="title">Рекомендовані товари</div>
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
                @foreach($recommends as $item)
                    <div class="swiper-slide swiper__product-slide">
                        @include('catalog.inc.variation-frame', ['variation' => $item])
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </main>
@endsection
