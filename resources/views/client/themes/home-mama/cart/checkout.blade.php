@extends('layouts.app-simple')

@php
    Seo::setDefault(['title' => 'Оформлення замовлення']);
@endphp

@section('content')
    @include('parts.modals')
    <main class="checkout container">
        <div class="checkout__logo">
            <img src="{{ Theme::url('img/logo.svg') }}" alt="logo">
        </div>
        <div class="checkout__wrapper">
            <div class="checkout__content">
                <h1 class="title--small">Особисті дані</h1>
                @guest
                    <div class="checkout__auth">
                        <p class="checkout__auth-text">Маєте обліковий запис?</p>
                        <a href="#" class="btn--extern" data-bs-toggle="modal" data-bs-target="#loginModal">Увійти</a>
                    </div>
                @endguest
                <form class="form checkout__form" method="POST" action="{{ route('cart.checkout.save') }}"
                      id="checkout-form">
                    @csrf
                    <div class="form__wrapper">
                        <div class="input__wrapper @error('user.name') error @enderror">
                            <label class="label">Ім’я</label>
                            <input class="input"
                                   type="text"
                                   placeholder="Введіть ім’я"
                                   name="user[name]"
                                   value="{{ old_request('user.name', Auth::user()?->name) }}">
                            @error('user.name') <p>{{ $message }}</p> @enderror
                        </div>
                        <div class="input__wrapper @error('user.lastname') error @enderror">
                            <label class="label">Прізвище</label>
                            <input class="input"
                                   type="text"
                                   placeholder="Введіть прізвище"
                                   name="user[lastname]"
                                   value="{{ old_request('user.lastname', Auth::user()?->lastname) }}">
                            @error('user.lastname') <p>{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="form__wrapper">
                        <div class="input__wrapper @error('user.email') error @enderror">
                            <label class="label">E-mail</label>
                            <input class="input"
                                   name="user[email]"
                                   value="{{ old_request('user.email', Auth::user()?->email) }}"
                                   type="email"
                                   placeholder="Введіть e-mail">
                            @error('user.email') <p>{{ $message }}</p> @enderror
                        </div>
                        <div class="input__wrapper @error('user.phone') error @enderror">
                            <label class="label">Телефон</label>
                            <input
                                class="input phone"
                                type="text"
                                name="user[phone]"
                                value="+{{ old_request('user.phone', Auth::user()?->phone) }}"
                                placeholder="+38 ___ ___ __ __"
                                data-inputmask="'mask': '+38 (099)-999-99-99'"
                            >
                            @error('user.phone') <p>{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="radio__wrapper">
                        <input type="radio" id="recipient-i" name="recipient[type]" value="i" class="js-radio-change2 js-recipient"
                               @if(old_request('recipient.type', 'i') === 'i') checked @endif
                        >
                        <label for="recipient-i">Я отримувач</label>
                    </div>
                    <div class="radio__wrapper">
                        <input type="radio" id="recipient-other" name="recipient[type]" value="other" class="js-radio-change2 js-recipient"
                               @if(old_request('recipient.type', 'i') === 'other') checked @endif
                        >
                        <label for="recipient-other">Інший отримувач</label>
                    </div>

                    <div class="js-recipient-block js-recipient-block-other">
                        <div class="form__wrapper">
                            <div class="input__wrapper @error('recipient.name') error @enderror">
                                <label class="label">Ім’я отримувача</label>
                                <input class="input" type="text" name="recipient[name]" value="{{ old_request('recipient.name') }}" placeholder="Введіть ім’я">
                                @error('recipient.name') <p>{{ $message }}</p> @enderror
                            </div>
                            <div class="input__wrapper @error('recipient.lastname') error @enderror">
                                <label class="label">Прізвище отримувача</label>
                                <input type="text" name="recipient[lastname]" value="{{ old_request('recipient.lastname') }}" placeholder="Введіть прізвище" class="input">
                                @error('recipient.lastname') <p>{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="input__wrapper @error('recipient.phone') error @enderror">
                            <label class="label">Телефон отримувача</label>
                            <input
                                type="text"
                                name="recipient[phone]"
                                value="+{{ old_request('recipient.phone') }}"
                                placeholder="+38 ___ ___ __ __"
                                data-inputmask="'mask': '+38 (099)-999-99-99'"
                                class="phone input"
                            >
                            @error('recipient.phone') <p>{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="dashed-line"></div>

                    <div class="title--small">Доставка</div>
                    <div class="checkout__select select">
                        <label class="label">Спосіб доставки</label>
                        <select class="js-select js-select-change2 js-shipping"
                                name="shipping[method]"
                        >
                            @foreach([
                                'novaposhta' => 'Відділення Нова Пошта',
                                'novaposhta_locker' => 'Поштомат Нова Пошта',
                                'novaposhta_courier' => 'Кур’єр Нова Пошта',
                                'ukrposhta' => 'Відділення УкрПошта',
                                'pickup' => 'Самовивіз (Луцьк)'
                            ] as $key => $val)
                                <option value="{{ $key }}" @if(old_request('shipping.method', 'novaposhta') === $key) selected @endif>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Місто Відділення НП--}}
                    <input name="shipping[novaposhta][CityName]" type="hidden" value="{{ old_request('shipping.novaposhta.CityName') }}">
                    <input name="shipping[novaposhta][WarehouseName]" type="hidden" value="{{ old_request('shipping.novaposhta.WarehouseName') }}">
                    <div class="checkout__select select js-shipping-block js-shipping-block-novaposhta @error('shipping.novaposhta.CityRef') error @enderror">
                        <label class="label">Місто</label>
                        <select class="js-select js-suggestSettlements"
                                data-ajax-url="{{ route('suggest.novaposhtaSettlements') }}"
                                name="shipping[novaposhta][CityRef]"
                                data-placeholder="Виберіть місто"
                                data-method="novaposhta"
                        >
                            @if($old = old_request('shipping.novaposhta.CityRef', Auth::user()?->getAdded('shipping.novaposhta.CityRef') ))
                                <option value="{{ $old }}">{{ old_request('shipping.novaposhta.CityName') }}</option>
                            @else
                                <option value=""></option>
                            @endif
                        </select>
                        @error('shipping.novaposhta.CityRef') <p>{{ $message }}</p> @enderror
                    </div>
                    {{-- Відділення нової пошти --}}
                    <div class="checkout__select select js-shipping-block js-shipping-block-novaposhta @error('shipping.novaposhta.WarehouseRef') error @enderror" {{--hidden--}}>
                        <label class="label">Відділення</label>
                        <select class="js-select js-suggestWarehouses"
                                name="shipping[novaposhta][WarehouseRef]"
                                data-placeholder="Виберіть відділення"
                                data-method="novaposhta"
                        >
                            @if($old = old_request('shipping.novaposhta.WarehouseRef', Auth::user()?->getAdded('shipping.novaposhta.WarehouseRef')))
                                <option value="{{ $old }}">{{ old_request('shipping.novaposhta.WarehouseName') }}</option>
                            @else
                                <option value="">&nbsp;</option>
                            @endif
                        </select>
                        @error('shipping.novaposhta.WarehouseRef') <p>{{ $message }}</p> @enderror
                    </div>



                    {{-- Місто Поштомат НП--}}
                    <input name="shipping[novaposhta_locker][CityName]" type="hidden" value="{{ old_request('shipping.novaposhta_locker.CityName') }}">
                    <input name="shipping[novaposhta_locker][WarehouseName]" type="hidden" value="{{ old_request('shipping.novaposhta_locker.WarehouseName') }}">
                    <div class="checkout__select select js-shipping-block js-shipping-block-novaposhta_locker @error('shipping.novaposhta_locker.CityRef') error @enderror">
                        <label class="label">Місто</label>
                        <select class="js-select js-suggestSettlements"
                                data-ajax-url="{{ route('suggest.novaposhtaSettlements') }}"
                                name="shipping[novaposhta_locker][CityRef]"
                                data-placeholder="Виберіть місто"
                                data-method="novaposhta_locker"
                        >
                            @if($old = old_request('shipping.novaposhta_locker.CityRef', Auth::user()?->getAdded('shipping.novaposhta_locker.CityRef') ))
                                <option value="{{ $old }}">{{ old_request('shipping.novaposhta_locker.CityName') }}</option>
                            @else
                                <option value=""></option>
                            @endif
                        </select>
                        @error('shipping.novaposhta_locker.CityRef') <p>{{ $message }}</p> @enderror
                    </div>
                    {{-- Поштомат НП --}}
                    <div class="checkout__select select js-shipping-block js-shipping-block-novaposhta_locker @error('shipping.novaposhta_locker.WarehouseRef') error @enderror" {{--hidden--}}>
                        <label class="label">Поштомат</label>
                        <select class="js-select js-suggestWarehouses"
                                name="shipping[novaposhta_locker][WarehouseRef]"
                                data-placeholder="Виберіть поштомат"
                                data-method="novaposhta_locker"
                        >
                            @if($old = old_request('shipping.novaposhta_locker.WarehouseRef', Auth::user()?->getAdded('shipping.novaposhta_locker.WarehouseRef')))
                                <option value="{{ $old }}">{{ old_request('shipping.novaposhta_locker.WarehouseName') }}</option>
                            @else
                                <option value="">&nbsp;</option>
                            @endif
                        </select>
                        @error('shipping.novaposhta_locker.WarehouseRef') <p>{{ $message }}</p> @enderror
                    </div>



                    {{-- Курєр НП --}}
                    <input name="shipping[novaposhta_courier][RecipientCityName]" type="hidden" value="{{ old_request('shipping.novaposhta_courier.RecipientCityName') }}">
                    <input name="shipping[novaposhta_courier][SettlementTypeCode]" type="hidden" value="{{ old_request('shipping.novaposhta_courier.SettlementTypeCode') }}">
                    <input name="shipping[novaposhta_courier][RecipientArea]" type="hidden" value="{{ old_request('shipping.novaposhta_courier.RecipientArea') }}">
                    <input name="shipping[novaposhta_courier][RecipientAreaRegions]" type="hidden" value="{{ old_request('shipping.novaposhta_courier.RecipientAreaRegions') }}">
                    <input name="shipping[novaposhta_courier][MainDescription]" type="hidden" value="{{ old_request('shipping.novaposhta_courier.MainDescription') }}">
                    <div class="js-shipping-block js-shipping-block-novaposhta_courier">
                        {{-- Місто курєр НП--}}
                        <div class="checkout__select select @error('shipping.novaposhta_courier.RecipientCityName') error @enderror">
                            <label class="label">Місто</label>
                            <select class="js-select js-suggestSettlements"
                                    data-ajax-url="{{ route('suggest.novaposhtaSettlements') }}"
                                    name="shipping[novaposhta_courier][CityRef]"
                                    data-placeholder="Виберіть місто"
                                    data-method="novaposhta_courier"
                            >
                                @if($old = old_request('shipping.novaposhta_courier.CityRef', Auth::user()?->getAdded('shipping.CityRef')))
                                    <option value="{{ $old }}">{{ old_request('shipping.novaposhta_courier.RecipientCityName') }}</option>
                                @else
                                    <option value=""></option>
                                @endif
                            </select>
                            @error('shipping.novaposhta_courier.RecipientCityName') <p>{{ $message }}</p> @enderror
                        </div>

                        <div class="input__wrapper shipping-method-block @error('shipping.novaposhta_courier.RecipientAddressName') error @enderror">
                            <label class="label">Вулиця</label>
                            <input class="input"
                                   type="text"
                                   name="shipping[novaposhta_courier][RecipientAddressName]"
                                   value="{{ old_request('shipping.novaposhta_courier.RecipientAddressName', Auth::user()?->getAdded('shipping.street')) }}"
                                   placeholder="Введіть вулицю">
                            @error('shipping.novaposhta_courier.RecipientAddressName') <p>{{ $message }}</p> @enderror
                        </div>
                        <div class="form__wrapper">
                            <div class="input__wrapper @error('shipping.novaposhta_courier.RecipientHouse') error @enderror">
                                <label class="label">Номер будинку</label>
                                <input class="input"
                                       type="text"
                                       name="shipping[novaposhta_courier][RecipientHouse]"
                                       value="{{ old_request('shipping.novaposhta_courier.RecipientHouse', Auth::user()?->getAdded('shipping.house')) }}"
                                       placeholder="Введіть номер">
                                @error('shipping.novaposhta_courier.RecipientHouse') <p>{{ $message }}</p> @enderror
                            </div>
                            <div class="input__wrapper @error('shipping.novaposhta_courier.RecipientFlat') error @enderror">
                                <label class="label">Номер квартири</label>
                                <input class="input"
                                       type="text"
                                       name="shipping[novaposhta_courier][RecipientFlat]"
                                       value="{{ old_request('shipping.novaposhta_courier.RecipientFlat', Auth::user()?->getAdded('shipping.apartment')) }}"
                                       placeholder="Введіть номер">
                                @error('shipping.novaposhta_courier.RecipientFlat') <p>{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Укрпошта --}}
                    <input name="shipping[ukrposhta][city]" class="js-city-name" type="hidden" value="{{ old_request('shipping.ukrposhta.city') }}">
                    <input name="shipping[ukrposhta][region]" class="js-region-name" type="hidden" value="{{ old_request('shipping.ukrposhta.region') }}">
                    <input name="shipping[ukrposhta][warehouse]" class="js-warehouse-name" type="hidden" value="{{ old_request('shipping.ukrposhta.warehouse') }}">
                    <input name="shipping[ukrposhta][postcode]" class="js-warehouse-zipcode" type="hidden" value="{{ old_request('shipping.ukrposhta.postcode') }}">
                    <div class="js-shipping-block js-shipping-block-ukrposhta">
                        <div class="input__wrapper checkout__select select @error('shipping.ukrposhta.region') error @enderror">
                            <label class="label">Область</label>
                            <select class="js-region input"
                                    name="shipping[ukrposhta][region_id]"
                                    data-placeholder="Виберіть область"
                            >
                                @if($old = old_request('shipping.ukrposhta.region_id'))
                                    <option value="{{ $old }}">{{ old_request('shipping.ukrposhta.region') }}</option>
                                @endif
                            </select>
                            @error('shipping.ukrposhta.region') <p>{{ $message }}</p> @enderror
                        </div>
                        <div class="form__wrapper">
                            <div class="input__wrapper checkout__select select @error('shipping.ukrposhta.city') error @enderror">
                                <label class="label">Місто</label>
                                <select class="js-city input"
                                        name="shipping[ukrposhta][city_id]"
                                        data-placeholder="Виберіть місто"
                                >
                                    @if($old = old_request('shipping.ukrposhta.city_id'))
                                        <option value="{{ $old }}">{{ old_request('shipping.ukrposhta.city') }}</option>
                                    @endif
                                </select>
                                @error('shipping.ukrposhta.city') <p>{{ $message }}</p> @enderror
                            </div>
                            <div class="input__wrapper checkout__select select @error('shipping.ukrposhta.warehouse') error @enderror">
                                <label class="label">Індекс / Відділення</label>
                                <select class="js-example-data-array js-warehouse input"
                                        name="shipping[ukrposhta][warehouse_id]"
                                        data-placeholder="Виберіть відділення"
                                >
                                    @if($old = old_request('shipping.ukrposhta.warehouse_id'))
                                        <option value="{{ $old }}">{{ old_request('shipping.ukrposhta.warehouse') }}</option>
                                    @endif
                                </select>
                                @error('shipping.ukrposhta.warehouse') <p>{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>


                    {{-- Самовивіз --}}
                    <div class="checkout__locale js-shipping-block js-shipping-block-pickup">
                        <svg class="icon-svg icon-svg-location_pin ">
                            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#location_pin"></use>
                        </svg>
                        <div class="checkout__locale-wrapper">
                            {!! Block::init('contacts')->getContent('checkout.pickup', '<p class="text">Луцьк, Винниченка 4, ТЦ Буратіно</p><p class="text-gray">З 10.00 до 18.00</p>') !!}
                        </div>
                    </div>

                    {{-- Оплата --}}
                    <div class="dashed-line"></div>
                    <div class="title--small">Оплата</div>

                    @if(Domain::getOptIs('payments.methods.liqpay'))
                    <div class="radio__wrapper" id="payment-liqpay-wrapper">
                        <input
                            class="js-radio-change2 js-payment"
                            type="radio"
                            id="payment-liqpay"
                            name="payment[gateway]"
                            {{ old_request('payment.gateway', 'received') === 'liqpay' ? 'checked' : '' }}
                            value="liqpay"
                        >
                        <label for="payment-liqpay">Онлайн оплата LiqPay</label>
                    </div>
                    @endif
                    @if(Domain::getOptIs('payments.methods.requisite'))
                    <div class="radio__wrapper" id="payment-requisite-wrapper">
                        <input
                            class="js-radio-change2 js-payment"
                            type="radio"
                            id="payment-requisite"
                            name="payment[gateway]"
                            {{ old_request('payment.gateway', 'received') === 'requisite' ? 'checked' : '' }}
                            value="requisite"
                        >
                        <label for="payment-requisite">Оплата за реквізитами</label>
                    </div>
                    @endif

                    @if(Domain::getOptIs('payments.methods.received'))
                    <div class="radio__wrapper" id="payment-received-wrapper">
                        <input
                            class="js-radio-change2 js-payment"
                            type="radio"
                            id="payment-received"
                            name="payment[gateway]"
                            value="received"
                            {{ old_request('payment.gateway', 'received') === 'received' ? 'checked' : '' }}
                        >
                        <label for="payment-received">Оплата при отриманні</label>
                    </div>
                    @endif
                    <p class="text-gray js-payment-block js-payment-block-received">
                        {!! Block::init('contacts')->getContent('checkout.postpaid', 'Стягується комісія “Нової Пошти” 2% від суми замовлення, плюс 20 грн за переказ грошових коштів.') !!}
                    </p>
                    <p class="text-gray js-payment-block js-payment-block-requisite">
                        {!! Block::init('contacts')->getContent('checkout.requisite', "Менеджер зв'яжеться з вами і надасть реквізити для оплати.") !!}
                    </p>
                    <p class="text-gray js-payment-block js-payment-block-liqpay">
                        {!! Block::init('contacts')->getContent('checkout.onlinepaid', 'Здійснюється оплата за доставку при отриманні згідно тарифів поштової служби.') !!}
                    </p>

                    <div class="dashed-line"></div>
                    <div class="textarea__wrapper checkout__textarea">
                        <label class="label">Коментар (необов'язково)</label>
                        <textarea class="textarea" name="client_comment" placeholder="Введіть коментар">{{ old_request('client_comment') }}</textarea>
                        @error('client_comment') <p>{{ $message }}</p> @enderror
                    </div>
                </form>
                <p class="checkout__copyright">{!! \Variable::getArray('site.copyright', 'Всі права захищені © 2024') !!}</p>
            </div>
            <div class="checkout__order js-cart-checkout">
                @include('cart.inc.checkout')
            </div>
        </div>
        <p class="checkout__copyright checkout__copyright--media">
            {!! \Variable::getArray('site.copyright', 'Всі права захищені © 2023') !!}
        </p>
    </main>
@endsection

@include('cart.inc.js-shipping-cart')
