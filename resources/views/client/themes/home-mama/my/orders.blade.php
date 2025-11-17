@extends('layouts.app', ['bodyClass' => 'profile__page'])

@php
    Seo::setDefault(['title' => 'Особистий кабінет: Замовлення']);
@endphp

@section('content')
    <main class="default-page profile">
        <div class="profile__wrapper container">
            @include('client.themes.home-mama.my.inc.aside')
            <button class="profile__menu-btn" type="button" data-bs-toggle="modal" data-bs-target="#profilePage">
                <svg class="icon-svg icon-svg-save "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#save"></use></svg>
                    Мої замовлення
                <svg class="icon-svg icon-svg-down "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#down"></use></svg>
            </button>
            <div class="profile__content">
                <h1 class="profile__title">Мої замовлення</h1>
                <div
                    class="accordion accordion--collapse"
                    id="accordionPanelsStayOpenExample"
                >
                    @if($orders->count())
                        @foreach($orders as $order)
                            <div class="accordion-item">
                                <div class="accordion-header" id="panelsStayOpen-heading{{ $order->id }}">
                                    <button
                                        class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapse{{ $order->id }}"
                                        aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapse{{ $order->id }}"
                                    >
                                    <span class="accordion-button__wrapper">
                                      <span
                                          class="accordion-button__date">№ {{ $order->number }} від {{ $order->getDatetime('ordered_at', 'd.m.y') }}</span>
                                      <span class="accordion-button__content">
                                          <span class="accordion-button__title">Сума замовлення: <span
                                                  class="js-num-format">{{ $order->getTotalSum() }}</span> грн</span>
                                        <span class="accordion-button__status
                                        @if($order->getStatus('key') === 'shipped') accordion-button__status--delivered @endif
                                        @if($order->getStatus('key') === 'awaiting_fulfillment' || $order->getStatus('key') === 'awaiting') accordion-button__status--pending @endif
                                        @if($order->getStatus('key') === 'declined' || $order->getStatus('key') === 'canceled') accordion-button__status--cancel @endif
                                        ">{{ $order->getStatus('name') }}</span>
                                      </span>
                                    </span>
                                        <span class="accordion-images">
                                            @foreach($order->purchases as $purchase)
                                                @if($variation = $purchase->variationwithTrashed)
                                                <img
                                                    src="{{ $variation->getImageUrl() ?: Theme::url('img/nophoto.webp') }}"
                                                    alt="Product"
                                                    class="accordion-img"/>
                                                @endif
                                            @endforeach
                                        </span>
                                        <svg class="icon-svg icon-svg-top ">
                                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#top"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    id="panelsStayOpen-collapse{{ $order->id }}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="panelsStayOpen-heading{{ $order->id }}"
                                >
                                    <div class="accordion-body">
                                        <ul class="accordion-list">
                                            <li class="accordion-list__item">
                                                <span>Отримувач</span>
                                                <span>{{ $order->getAdded('recipient.name') ?: $order->getAdded('user.name') }}
                                                    {{ $order->getAdded('recipient.lastname') ?: $order->getAdded('user.lastname') }}</span>
                                            </li>
                                            <li class="accordion-list__item">
                                                <span>Телефон отримувача</span>
                                                <span>{{ $order->getAdded('recipient.phone') ?: $order->getAdded('user.phone') }}</span>
                                            </li>
                                            <li class="accordion-list__item">
                                                <span>E-mail</span>
                                                <span>{{ $order->getAdded('user.email') }}</span>
                                            </li>
                                            <li class="accordion-list__item">
                                                <span>Спосіб доставки</span>
                                                <span>{{ $order->getShippingMethod() }}</span>
                                            </li>
                                            @if($adr = $order->getShippingAddressStr())
                                            <li class="accordion-list__item">
                                                <span>Адреса</span>
                                                <span>{{ $adr }}</span>
                                            </li>
                                            @endif
                                        </ul>
                                        <div class="accordion-content">
                                            <ul class="order-list">
                                                @foreach($order->purchases as $purchase)
                                                    @if($variation = $purchase->variationwithTrashed)
                                                    <li class="order-item">
                                                        <a href="{{ $purchase->getUrlClient() }}" class="order-link">
                                                            <img
                                                                src="{{ $variation->getImageUrl() ?: Theme::url('img/nophoto.webp') }}"
                                                                alt="Image"
                                                                class="order-img"
                                                            />
                                                        </a>
                                                        <div class="order-wrapper">
                                                            <div class="order-text--title">
                                                                <a href="{{ $purchase->getUrlClient() }}"
                                                                   class="order-text">
                                                                    {{ $purchase->variationwithTrashed->getName() }}
                                                                </a>
                                                            </div>
                                                            <span class="order-wrapper__info">
                                                                <span class="order-text">{{ $purchase->getQty() }}х</span>
                                                                <span
                                                                    class="order-price">{{ $purchase->getTotalSum() }} грн</span>
                                                            </span>
                                                        </div>
                                                    </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                            <ul class="profile__list profile__list-info">
                                                <li class="profile__order-item">
                                                    Вартість товару <span><span
                                                            class="js-num-format">{{ $order->allPurchasesSum() }}</span> грн</span>
                                                </li>
                                                <li class="profile__order-item">
                                                    Доставка
                                                    <span>{{ $order->deliverySum() ?: 'За тарифами перевізника' }}</span>
                                                </li>
                                            </ul>
                                            <div class="profile__order-item total">
                                                Разом <span><span
                                                        class="js-num-format">{{ $order->getTotalSum() }}</span> грн</span>
                                            </div>
                                            <div class="profile__order-buttons">
                                                <button hidden class="btn--extern">
                                                    Залишити відгук
                                                    <svg
                                                        width="24"
                                                        height="24"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M1 22L2.14634 16.561M1 22L6.43902 20.8537M1 22H13.5M2.14634 16.561L16.2931 2.41421C17.0741 1.63317 18.3405 1.63316 19.1215 2.41421L20.7927 4.08537C21.4595 4.75215 21.4595 5.83322 20.7927 6.5V6.5M2.14634 16.561L6.43902 20.8537M6.43902 20.8537L20.7927 6.5M16.561 22H21.9268M20.7927 6.5L22.2927 8L16.5 13.7927"
                                                            stroke="#1B1818"
                                                            stroke-width="1.4"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                    </svg>
                                                </button>
                                                <button hidden class="btn--intern">
                                                    Повторити замовлення
                                                    <svg
                                                        width="25"
                                                        height="24"
                                                        viewBox="0 0 25 24"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M19.2344 14.6966C18.5978 16.1911 17.4521 17.4231 15.9926 18.1825C14.533 18.942 12.8499 19.182 11.23 18.8617C9.61012 18.5414 8.15369 17.6805 7.10886 16.4258C6.06403 15.1711 5.49546 13.6001 5.50003 11.9807C5.50459 10.3612 6.08201 8.79336 7.13389 7.54433C8.18577 6.29529 9.64704 5.44233 11.2687 5.13077C12.8904 4.81921 14.5721 5.06834 16.0274 5.8357C17.4826 6.60306 18.6213 7.84117 19.2495 9.33909M19.2495 9.33909L20.5 5.13077M19.2495 9.33909L15.1429 8.49999"
                                                            stroke="white"
                                                            stroke-width="1.4"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                    </svg>
                                                </button>
{{--                                                @if($order->getStatus('key') === 'awaiting_payment' || $order->getStatus('key') === 'pending')--}}
{{--                                                <button class="btn--extern js-click-submit" data-url="{{ route('my.orders.status', ['order' => $order, 'status' => 'canceled']) }}" type="button" >--}}
{{--                                                    Скасувати замовлення--}}
{{--                                                    <svg class="icon-svg icon-svg-exit ">--}}
{{--                                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>--}}
{{--                                                    </svg>--}}
{{--                                                </button>--}}
{{--                                                @endif--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="profile__empty">
                            <svg
                                width="201"
                                height="201"
                                viewBox="0 0 201 201"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                    x="0.5"
                                    y="0.5"
                                    width="200"
                                    height="200"
                                    rx="100"
                                    fill="#F1F1F1"
                                />
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M97.7582 46.6188C88.5534 46.6188 81.0915 54.0808 81.0915 63.2855V77.8974H74.2422V63.2855C74.2422 50.298 84.7707 39.7695 97.7582 39.7695C110.746 39.7695 121.274 50.298 121.274 63.2855V77.8974H114.425V63.2855C114.425 54.0808 106.963 46.6188 97.7582 46.6188Z"
                                    fill="#777777"
                                />
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M101.414 42.9658C92.2097 42.9658 84.7478 50.4277 84.7478 59.6324V74.2443H77.8984V59.6324C77.8984 46.6449 88.4269 36.1165 101.414 36.1165C114.402 36.1165 124.93 46.6449 124.93 59.6324V74.2443H118.081V59.6324C118.081 50.4277 110.619 42.9658 101.414 42.9658Z"
                                    fill="#C6C6C6"
                                />
                                <path
                                    d="M48.7143 156.192L56.0905 73.9997C56.2173 72.5872 57.4011 71.5049 58.8193 71.5049H140.357C141.775 71.5049 142.959 72.5872 143.086 73.9997L150.462 156.192C150.606 157.795 149.343 159.176 147.733 159.176H51.4431C49.8333 159.176 48.5704 157.795 48.7143 156.192Z"
                                    fill="#777777"
                                />
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M62.9627 71.5049L56.0183 148.886C55.8744 150.489 57.1373 151.87 58.747 151.87H150.074L150.462 156.192C150.606 157.795 149.343 159.176 147.733 159.176H51.4431C49.8333 159.176 48.5704 157.795 48.7143 156.192L56.0905 73.9997C56.2173 72.5872 57.4011 71.5049 58.8193 71.5049H62.9627Z"
                                    fill="#575555"
                                />
                                <path
                                    d="M93.0333 108.927C92.1859 108.927 91.5283 108.694 91.0607 108.226C90.5932 107.729 90.3594 107.072 90.3594 106.253C90.3594 105.435 90.5932 104.792 91.0607 104.325C91.5283 103.828 92.1859 103.579 93.0333 103.579C93.8808 103.579 94.5384 103.828 95.006 104.325C95.4735 104.792 95.7073 105.435 95.7073 106.253C95.7073 107.072 95.4735 107.729 95.006 108.226C94.5384 108.694 93.8808 108.927 93.0333 108.927ZM93.0333 124.401C92.1859 124.401 91.5283 124.168 91.0607 123.7C90.5932 123.203 90.3594 122.546 90.3594 121.727C90.3594 120.909 90.5932 120.266 91.0607 119.799C91.5283 119.302 92.1859 119.053 93.0333 119.053C93.8808 119.053 94.5384 119.302 95.006 119.799C95.4735 120.266 95.7073 120.909 95.7073 121.727C95.7073 122.546 95.4735 123.203 95.006 123.7C94.5384 124.168 93.8808 124.401 93.0333 124.401Z"
                                    fill="white"
                                />
                                <path
                                    d="M112.158 133.432C108.184 130.743 105.188 127.733 103.172 124.401C101.184 121.099 100.191 117.388 100.191 113.267C100.191 109.147 101.184 105.435 103.172 102.133C105.188 98.8014 108.184 95.7914 112.158 93.1028L112.772 93.9795C110.317 96.1421 108.549 98.8599 107.468 102.133C106.416 105.377 105.89 109.088 105.89 113.267C105.89 117.446 106.416 121.158 107.468 124.401C108.549 127.674 110.317 130.392 112.772 132.555L112.158 133.432Z"
                                    fill="white"
                                />
                            </svg>
                            <p class="profile__empty-text">У вас немає замовлень</p>
                            <a href="{{ route('catalog.index') }}" class="btn--intern">До покупок</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
