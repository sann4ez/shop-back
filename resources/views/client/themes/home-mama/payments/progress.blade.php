@extends('layouts.app-simple')

@section('content')
    <main class="status-page container">
        <a href="{{ route('home') }}" class="checkout__logo">
            <img src="{{ Theme::url('img/logo.svg') }}" alt="logo" >
        </a>
        <div class="status-page__wrapper">
            <img class="status-page__img" src="{{ Theme::url('img/success.svg') }}" alt="success">
            <div class="status-page__info">
                <h1 class="title">Дякуємо!</h1>
                <p class="text-mod text-gray" style="text-align: center">Менеджер вже опрацьовує ваше замовлення.</p>
                @if($n = request('order_number'))
                <div class="status-page__order">
                    <p class="text-mod text-blue">
                        Номер замовлення
                        <span class="status-page__order-num">{{ $n }}</span>
                    </p>
                </div>
                @endif
            </div>
            <div class="status-page__actions">
                <a href="{{ route('catalog.index') }}" class="btn--intern">
                    Продовжити покупки
                </a>
            </div>
        </div>
    </main>
@endsection
