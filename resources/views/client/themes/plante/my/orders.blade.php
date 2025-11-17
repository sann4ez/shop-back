@extends('layouts.app')

@php
    Seo::setDefault(['title' => 'Особистий кабінет: Замовлення']);
@endphp

@section('content')
    <main>
        <section class="personal-data orders container">
            <div class="header-page header-page--personal">
                <!-- <nav class="header-page__breadcrumbs">
                              <ul class="breadcrumbs">
                                  <li class="breadcrumbs__item main-text main-text--caption main-text--caption-mobile">
                                      <a class="breadcrumbs__item-link" href="home.html">Головна</a>
                                  </li>
                              </ul>
                          </nav> -->
                <h1 class="title">Історія замовлень</h1>
                <a
                    href=""
                    class="main-link"
                    data-bs-target="#logOut"
                    data-bs-toggle="modal"
                    aria-label="open modal"
                >Вийти з акаунту</a
                >
            </div>

            <div class="personal-data__content">
                @include('my.inc.aside')

                <div class="accordion order" id="accordionPanelsStayOpenExample">
                    @if($orders->count())
                    @foreach($orders as $order)
                    <div class="accordion-item">
                        <div class="accordion-header" id="panelsStayOpen-heading{{ $order->id }}">
                            <div
                                class="accordion-button main-text collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapse{{ $order->id }}"
                                aria-expanded="true"
                                aria-controls="panelsStayOpen-collapse{{ $order->id }}"
                            >
                                <div class="order__accordion-header">
                                    <div class="order__info">
                                        <div
                                            class="order__pubdate"
                                        >
                                            {{ $order->getDatetime('ordered_at', 'd M y') }}
                                        </div>
                                        <div class="order__offer main-text main-text--semibold">
                                            <div class="main-text main-text--semibold">
                                                Замовлення на суму:
                                            </div>
                                            <div
                                                class="order__price main-text main-text--semibold main-text--green"
                                            >
                                                <span class="js-num-format">{{ $order->getTotalSum() }}</span> грн
                                            </div>
                                        </div>
                                        <div class="order__status main-text
                                        @switch($order->getStatus('key'))
                                            @case('awaiting_fulfillment')
                                            @case('awaiting')
                                            @case('pending')
                                            @case('awaiting_payment')
                                                order__status--pending
                                                @break
                                            @case('shipped')
                                                order__status--ontheway
                                                @break
                                            @case('completed')
                                                order__status--done
                                                @break
                                            @case('declined')
                                            @case('canceled')
                                                order__status--reject
                                                @break
                                        @endswitch
                                        ">
                                            {{ $order->getStatus('name') }}
                                        </div>
                                    </div>
                                    <div class="order__arrow">
                                        <span class="order__arrow-line"></span>
                                        <span class="order__arrow-line"></span>
                                    </div>
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="17"
                                    viewBox="0 0 16 17"
                                    fill="none"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M0.511963 6.06955L1.48815 4.93066L8.00006 10.5123L14.512 4.93066L15.4881 6.06955L8.00006 12.4879L0.511963 6.06955Z"
                                        fill="#121212"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div
                            id="panelsStayOpen-collapse{{ $order->id }}"
                            class="accordion-collapse collapse"
                            aria-labelledby="panelsStayOpen-heading{{ $order->id }}"
                        >
                            <div class="accordion-body">
                                <div class="order__content">
                                    <div class="order__wrapper">
                                        <div class="order__details">
                                            <div class="order__stat">
                                                <div
                                                    class="main-text main-text--mobile main-text--color-dark-gray"
                                                >
                                                    Отримувач
                                                </div>
                                                <div class="order__value">{{ $order->getAdded('recipient.name') ?: $order->getAdded('user.name') }}
                                                    {{ $order->getAdded('recipient.lastname') ?: $order->getAdded('user.lastname') }}</div>
                                            </div>

                                            <div class="order__stat">
                                                <div
                                                    class="main-text main-text--mobile main-text--color-dark-gray"
                                                >
                                                    Телефон отримувача
                                                </div>
                                                <div class="order__value">{{ $order->getAdded('recipient.phone') ?: $order->getAdded('user.phone') }}</div>
                                            </div>

                                            <div class="order__stat">
                                                <div
                                                    class="main-text main-text--mobile main-text--color-dark-gray"
                                                >
                                                    E-mail
                                                </div>
                                                <div class="order__value">{{ $order->getAdded('user.email') }}</div>
                                            </div>

                                            <div class="order__stat">
                                                <div
                                                    class="main-text main-text--mobile main-text--color-dark-gray"
                                                >
                                                    Спосіб доставки
                                                </div>

                                                <div class="order__value">{{ $order->getAdded('shipping.submethod') }} {{ $order->getAdded('shipping.method') }}</div>
                                            </div>


                                            @if($adr = $order->getShippingAddressStr())
                                            <div class="order__stat">
                                                <div class="main-text main-text--mobile main-text--color-dark-gray">
                                                    Адреса
                                                </div>
                                                <div class="order__value">{{ $adr }}</div>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="checkout__order-goods">
                                        @foreach($order->purchases as $purchase)
                                            <div class="checkout__order-good">
                                                <div class="checkout__order-good-item">
                                                    <a href="{{ $purchase->getUrlClient() }}" class="checkout__order-good-link">
                                                        <img
                                                            loading="lazy"
                                                            src="{{ $purchase->getImageUrl() ?: Theme::url('img/img-error.png') }}"
                                                            alt="Image"
                                                            class="checkout__order-good-img"
                                                        />
                                                    </a>
                                                    <a href="{{ $purchase->getUrlClient() }}" class="checkout__order-good-name">
                                                        {{ $purchase->getName() }}
                                                    </a>
                                                </div>
                                                <div class="checkout__order-good-amount main-text">
                                                    {{ $purchase->getQty() }}х
                                                </div>
                                                <div class="checkout__order-good-price main-text">
                                                    {{ $purchase->getTotalSum() }} грн
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
{{--                                    <button class="main-btn main-btn--green">--}}
{{--                                        Повторити замовлення--}}
{{--                                    </button>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                        <div class="search-fail">
                            <div class="search-fail__logo">:(</div>
                            <div class="search-fail__msg">У вас немає замовлень</div>
                            <a href="{{ route('catalog.index') }}" class="main-btn main-btn--green main-btn--notfound">До покупок</a>
                        </div>
                    @endif
                </div>
            </div>

            @include('my.inc.modal-exit')
        </section>
    </main>
@endsection
