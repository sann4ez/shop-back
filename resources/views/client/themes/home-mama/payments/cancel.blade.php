@extends('layouts.app-simple')

@section('content')
    <main class="status-page container">
        <a href="{{ route('home') }}" class="checkout__logo">
            <img src="{{ Theme::url('img/logo.svg') }}" alt="logo" >
        </a>
        <div class="status-page__wrapper">
            <img class="status-page__img" src="{{ Theme::url('img/error.svg') }}" alt="success">
            <div class="status-page__info">
                <h1 class="title">Невдача</h1>
                <p class="text-mod text-gray">Оплата не успішна</p>
            </div>
            <div class="status-page__actions">
                <a href="{{ route('home') }}" class="btn--intern">
                    Спобувати ще раз
                </a>
                <a href="{{ route('pages.show', 'contacts') }}" class="btn--extern">
                    Зв’язатися з нами
                </a>
            </div>
        </div>
    </main>
@endsection
