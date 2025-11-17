@extends('layouts.app-simple-header')

@php
    Seo::setDefault(['title' => 'Оформлення замовлення']);
@endphp

@section('content')

    <main>
        <section class="checkout container">
            @if(\Cart::getQty())
            <div class="checkout__content">
                <div class="checkout__wrapper">
                    <form class="main-form main-form--checkout" method="POST" action="{{ route('cart.checkout.save') }}" id="checkout-form">
                        @csrf
                        <div class="checkout__form-header">
                            <h1 class="title title--checkout">Оформлення замовлення</h1>
                            @guest
                            <div class="checkout__form-warn">
                                <p class="checkout__form-text main-text">Увійдіть у свій кабінет, аби швидше оформити замовлення</p>
                                <a href="#" class="main-link main-text main-text--semibold" data-bs-target="#signIn"
                                   data-bs-toggle="modal" aria-label="signIn">Увійти</a>
                            </div>
                            @endguest
                        </div>
                        <div class="checkout__form-block">
                            <div class="checkout__form-block-header">
                                <div class="round">1</div>
                                <div class="title title--small checkout__form-title">Контактні дані</div>
                            </div>
                            @guest
                            <div class="main-form__input @error('user.email') main-form__input--error @enderror">
                                <label for="email" class="checkout__form-text main-form__input">Електронна пошта</label>
                                <input name="user[email]" id="email" value="{{ old_request('user.email', Auth::user()?->email) }}" class="main-input main-input--checkout" autocomplete="off">
                                @error('user.email') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                            </div>
                            @else
                                <input type="hidden" name="user[email]" value="{{ Auth::user()->email }}" autocomplete="off">
                            @endguest
                            <div class="checkout__form-name">
                                <div class="main-form__input @error('user.name') main-form__input--error @enderror">
                                    <label for="name" class="checkout__form-text">Ім'я</label>
                                    <input name="user[name]" required id="name-1" type="text" value="{{ old_request('user.name', Auth::user()?->name) }}" class="main-input main-input--checkout" autocomplete="off">
                                    @error('user.name') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                </div>
                                <div class="main-form__input @error('user.lastname') main-form__input--error @enderror">
                                    <label for="lastname" class="checkout__form-text">Прізвище</label>
                                    <input name="user[lastname]" required id="lastname" type="text" value="{{ old_request('user.lastname', Auth::user()?->lastname) }}" class="main-input main-input--checkout" autocomplete="off">
                                    @error('user.lastname') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="main-form__input @error('user.phone') main-form__input--error @enderror">
                                <label for="phone" class="checkout__form-text main-form__input">Телефон</label>
                                <input name="user[phone]" id="phone-1" required type="tel" value="+{{ old_request('user.phone', Auth::user()?->phone) }}" class="main-input main-input--checkout" placeholder="+38 (099) 999-99-99" autocomplete="off">
                                @error('user.phone') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="devider"></div>

                        <div class="checkout__form-block">
                            <div class="checkout__form-block-header">
                                <div class="round">2</div>
                                <div class="title title--small checkout__form-title">Доставка</div>
                            </div>

                            <div class="checkout__form-inputs">
                                <div class="main-form__input">
                                    <label  class="checkout__form-text" >Спосіб доставки</label>
                                    <div class="checkout__form-select-wrap">
                                        <select name="shipping[method]" id="delivery" class="main-input main-input--checkout checkout__form-select js-select js-select-change2 js-shipping" >
                                            @foreach([
                                                'novaposhta' => 'Відділення Нова Пошта',
                                                'novaposhta_courier' => 'Кур’єр Нова Пошта',
                                                'ukrposhta' => 'Відділення УкрПошта',
                                                'pickup' => \Variable::getArray("shipping.pickup.title", 'Самовивіз з магазину')
                                            ] as $key => $val)
                                                @if(in_array($key, Domain::getOpt('shippings.methods')))
                                                <option value="{{ $key }}" @if(old_request('shipping.method', 'novaposhta') === $key) selected @endif>{{ $val }}</option>
                                                @endif
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                {{-- Місто відділення НП--}}
                                <input name="shipping[novaposhta][CityName]" type="hidden" value="{{ old_request('shipping.novaposhta.CityName', Auth::user()?->getAdded('shipping.novaposhta.city')) }}">
                                <div class="main-form__input js-shipping-block js-shipping-block-novaposhta @error('shipping.novaposhta.CityName') main-form__input--error @enderror">
                                    <label {{--for="city"--}} class="checkout__form-text" >Ваш населений пункт:</label>
                                    <div class="checkout__form-select-wrap">
                                        <select name="shipping[novaposhta][CityRef]" {{--id="city"--}}
                                                class="main-input main-input--checkout checkout__form-select js-select js-suggestSettlements"
                                                data-ajax-url="{{ route('suggest.novaposhtaSettlements') }}"
                                                data-placeholder="Виберіть населений пункт">

                                            @if($old = old_request('shipping.novaposhta.CityName'))
                                                <option value="{{ $old }}">{{ $old }}</option>
                                            @else
                                                <option value=""></option>
                                            @endif
                                        </select>
                                        @error('shipping.novaposhta.CityName') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Відділення нової пошти --}}
                                <div class="main-form__input js-shipping-block js-shipping-block-novaposhta @error('shipping.novaposhta.WarehouseName') main-form__input--error @enderror" {{--hidden--}}>
                                    <label for="department" class="checkout__form-text" >Відділення</label>
                                    <div class="checkout__form-select-wrap">
                                        <select name="shipping[novaposhta][WarehouseName]" id="department"
                                                class="main-input main-input--checkout checkout__form-select js-select js-suggestWarehouses"
                                                data-placeholder="Виберіть відділення">
                                            @if($old = old_request('shipping.novaposhta.WarehouseName', Auth::user()?->getAdded('shipping.novaposhta.WarehouseName')))
                                                <option value="{{ $old }}">{{ $old }}</option>
                                            @else
                                                <option value=""></option>
                                            @endif
                                        </select>
                                        @error('shipping.novaposhta.WarehouseName') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Курєр НП TODO --}}
                                <div class="js-shipping-block js-shipping-block-novaposhta_courier">
                                    <div class="main-form__input @error('shipping.city') main-form__input--error @enderror">
                                        <label {{--for="city"--}} class="checkout__form-text" >Ваш населений пункт:</label>
                                        <div class="checkout__form-select-wrap">
                                            <select name="shipping[novaposhta_courier][city]" {{--id="city"--}}
                                                    class="main-input main-input--checkout checkout__form-select js-select js-suggestSettlements"
                                                    data-ajax-url="{{ route('suggest.novaposhtaSettlements') }}"
                                                    data-placeholder="Виберіть населений пункт">
                                                @if($old = old_request('shipping.novaposhta.city', Auth::user()?->getAdded('shipping.novaposhta.city') ))
                                                    <option value="{{ $old }}">{{ $old }}</option>
                                                @else
                                                    <option value=""></option>
                                                @endif
                                            </select>
                                            @error('shipping.novaposhta_courier.city') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="checkout__form-inputs-wrapper">
                                        <label for="streetDelivery" class="main-form__input @error('shipping.street') main-form__input--error @enderror">
                                            Вулиця
                                            <input name="shipping[novaposhta_courier][street]"
                                                   id="streetDelivery"
                                                   type="text"
                                                   value="{{ old_request('shipping.street', Auth::user()?->getAdded('shipping.street')) }}"
                                                   class="main-input main-input--checkout checkout__form-input"
                                                   placeholder="Введіть вулицю"
                                                   autocomplete="off"
                                            >
                                            @error('shipping.street') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                        </label>
                                        <label for="houseDelivery" class="main-form__input @error('shipping.house') main-form__input--error @enderror">
                                            Будинок
                                            <input name="shipping[novaposhta_courier][house]"
                                                   id="houseDelivery"
                                                   type="text"
                                                   value="{{ old_request('shipping.house', Auth::user()?->getAdded('shipping.house')) }}"
                                                   class="main-input main-input--checkout checkout__form-input"
                                                   placeholder="Введіть номер"
                                                   autocomplete="off"
                                            >
                                            @error('shipping.house') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                        </label>
                                        <label for="apartmentDelivery" class="main-form__input @error('shipping.apartment') main-form__input--error @enderror">
                                            Квартира
                                            <input name="shipping[novaposhta_courier][apartment]"
                                                   id="apartmentDelivery"
                                                   type="text"
                                                   value="{{ old_request('shipping.apartment', Auth::user()?->getAdded('shipping.apartment')) }}"
                                                   class="main-input main-input--checkout checkout__form-input"
                                                   placeholder="Введіть номер"
                                                   autocomplete="off"
                                            >
                                            @error('shipping.apartment') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                        </label>
                                    </div>
                                </div>

                                {{-- Укрпошта --}}
                                <div class="js-shipping-block js-shipping-block-ukrposhta">
                                    <div class="checkout__form-inputs-wrapper">
                                        <label for="regionDelivery" class="main-form__input @error('shipping.ukrposhta.region') main-form__input--error @enderror">
                                            Область
                                            <input name="shipping[ukrposhta][region]"
                                                   id="regionDelivery"
                                                   type="text"
                                                   value="{{ old_request('shipping.ukrposhta.region', Auth::user()?->getAdded('shipping.region')) }}"
                                                   class="main-input main-input--checkout {{--main-input--error--}} checkout__form-input"
                                                   placeholder="Введіть область"
                                                   autocomplete="off"
                                            >
                                            @error('shipping.ukrposhta.region') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                        </label>
                                        <label for="cityDelivery" class="main-form__input @error('shipping.ukrposhta.city') main-form__input--error @enderror">
                                            Місто
                                            <input name="shipping[ukrposhta][city]"
                                                   id="cityDelivery"
                                                   type="text"
                                                   value="{{ old_request('shipping.city', Auth::user()?->getAdded('shipping.city')) }}"
                                                   class="main-input main-input--checkout checkout__form-input"
                                                   placeholder="Введіть місто"
                                                   autocomplete="off"
                                            >
                                            @error('shipping.ukrposhta.city') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                        </label>
                                        <label for="indexDelivery" class="main-form__input @error('shipping.ukrposhta.postcode') main-form__input--error @enderror">
                                            Індекс / Відділення
                                            <input name="shipping[ukrposhta][postcode]"
                                                   id="indexDelivery"
                                                   type="text"
                                                   value="{{ old_request('shipping.postcode', Auth::user()?->getAdded('shipping.postcode')) }}"
                                                   class="main-input main-input--checkout checkout__form-input"
                                                   placeholder="Введіть індекс"
                                                   autocomplete="off"
                                            >
                                            @error('shipping.ukrposhta.postcode') <span class="main-form__error-msg">{{ $message }}</span> @enderror
                                        </label>
                                    </div>
                                </div>

                                <div class="js-shipping-block js-shipping-block-pickup">
                                    <div class="checkout__form-location">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.99989 2.5C8.42307 2.5 6.90653 3.15208 5.78512 4.31983C4.66306 5.48827 4.0293 7.07717 4.0293 8.73797C4.0293 11.1648 5.5381 13.4784 7.13444 15.233C7.92355 16.1004 8.7142 16.8096 9.30801 17.3019C9.5918 17.5373 9.82975 17.7223 9.99988 17.8508C10.17 17.7223 10.408 17.5373 10.6918 17.3019C11.2856 16.8096 12.0762 16.1004 12.8653 15.233C14.4617 13.4784 15.9705 11.1648 15.9705 8.73797C15.9705 7.07717 15.3367 5.48827 14.2146 4.31983C13.0932 3.15208 11.5767 2.5 9.99989 2.5ZM9.99989 18.4706L10.285 18.8813C10.1136 19.0003 9.88621 19.0003 9.71475 18.8813L9.99989 18.4706ZM5.06385 3.62718C6.36938 2.2677 8.14449 1.5 9.99989 1.5C11.8553 1.5 13.6304 2.2677 14.9359 3.62718C16.2408 4.98597 16.9705 6.82473 16.9705 8.73797C16.9705 11.5518 15.244 14.1045 13.605 15.906C12.7765 16.8167 11.9495 17.5581 11.3301 18.0717C11.02 18.3288 10.7609 18.5297 10.5784 18.6669C10.4872 18.7356 10.415 18.7884 10.365 18.8244C10.3401 18.8424 10.3206 18.8562 10.3072 18.8658L10.2915 18.8768L10.285 18.8813C10.2849 18.8814 10.285 18.8813 9.99989 18.4706C9.71475 18.8813 9.71491 18.8814 9.71475 18.8813L9.70829 18.8768L9.6926 18.8658C9.67914 18.8562 9.65972 18.8424 9.63474 18.8244C9.58479 18.7884 9.5126 18.7356 9.42132 18.6669C9.23883 18.5297 8.97979 18.3288 8.6697 18.0717C8.05027 17.5581 7.22328 16.8167 6.39475 15.906C4.75578 14.1045 3.0293 11.5518 3.0293 8.73797C3.0293 6.82473 3.75898 4.98597 5.06385 3.62718Z" fill="#121212"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.99908 7.20605C9.3006 7.20605 8.73438 7.77228 8.73438 8.47076C8.73438 9.16924 9.3006 9.73547 9.99908 9.73547C10.6976 9.73547 11.2638 9.16924 11.2638 8.47076C11.2638 7.77228 10.6976 7.20605 9.99908 7.20605ZM7.73438 8.47076C7.73438 7.22 8.74832 6.20605 9.99908 6.20605C11.2498 6.20605 12.2638 7.22 12.2638 8.47076C12.2638 9.72152 11.2498 10.7355 9.99908 10.7355C8.74832 10.7355 7.73438 9.72152 7.73438 8.47076Z" fill="#121212"/>
                                        </svg>
                                        <div class="checkout__form-location-wrapper">
                                            <p class="checkout__form-text checkout__form-text--bold">{{ \Variable::getArray("shipping.pickup.address", 'м. Київ, вул. Світлицького 35, оф. 43/1-1') }}</p>
                                            <span class="checkout__form-location-span">
                                                {{ \Variable::getArray("shipping.pickup.schedule", 'Пн-Пт 08:30-17:30') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="rem"></div>

                        {{-- Оплата --}}
                        <div class="checkout__form-block">
                            <div class="checkout__form-block-header">
                                <div class="round">3</div>
                                <h3 class="title title--small checkout__form-title">Оплата</h3>
                            </div>
                            <fieldset class="checkout__form-radio">
                                @if(Domain::getOptIs('payments.methods.received'))
                                <input name="payment[gateway]"
                                       id="cash"
                                       value="received"
                                       type="radio"
                                       {{ old_request('payment.gateway', 'received') === 'received' ? 'checked' : '' }}
                                       autocomplete="off"
                                       class="checkout__form-radio-input js-radio-change2 js-payment">
                                <label for="cash" class="checkout__form-radio-label checkout__form-radio-label--desc">
                                    Оплата під час отримання товару
                                    <span class="checkout__form-desc">
                                        <span class="checkout__form-text checkout__form-text--medium">
                                            {{ \Variable::getArray("payments.received.info", 'Наш менеджер зв\'яжеться з вами для уточнення інформації щодо оплати') }}
                                        </span>
                                    </span>
                                </label>
                                @endif
                                @if(Domain::getOptIs('payments.methods.paycard'))
                                <input
                                    name="payment[gateway]"
                                    id="cardCash"
                                    value="paycard"
                                    type="radio"
                                    {{ old_request('payment.gateway', 'received') === 'paycard' ? 'checked' : '' }}
                                    autocomplete="off"
                                    class="checkout__form-radio-input js-radio-change2 js-payment">
                                <label for="cardCash" class="checkout__form-radio-label checkout__form-radio-label--desc">
                                    Оплатити карткою зараз
                                    <span class="checkout__form-desc">
                                        <span class="checkout__form-text checkout__form-text--medium">
                                            {{ \Variable::getArray("payments.paycard.info", 'Наш менеджер зв\'яжеться з вами для уточнення інформації щодо оплати') }}
                                        </span>
                                    </span>
                                </label>
                                @endif
                            </fieldset>

                        </div>
                    </form>

                    <div class="checkout__order js-cart-checkout">
                        @include('cart.inc.checkout')
                    </div>
                </div>
                @if($block = Block::init('contacts'))
                    @if($items = $block->getContent('copyright'))
                        <div class="footer__copy checkout__copy">
                            {!! $block->getContent('copyright', 'Інтернет-магазин насіння, саджанців, добрив і засобів захисту рослин "Plante" © 2023') !!}
                        </div>
                    @endif
                @endif
            </div>
            @else
                <div class="search-fail">
                    <div class="search-fail__logo">:(</div>
                    <div class="main-text">Кошик порожній</div>
                    <a href="{{ route('catalog.index') }}" class="main-btn main-btn--green main-btn--width100">До покупок</a>
                </div>
            @endif
        </section>
    </main>

@endsection

@include('cart.inc.js-shipping-cart')
