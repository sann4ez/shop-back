@extends('layouts.app')

@php
    Seo::setDefault(['title' => 'Вхід']);
@endphp

@section('content')
    <main class="container auth">
        <h1 class="title">Вхід</h1>
        @if(config('services.google.client_id'))
        <div class="auth-body__social">
            <a class="btn--extern btn--login" href="{{ route('socialite.oauth', 'facebook') }}">
                Увійти через Facebook
                <img src="{{ Theme::url('img/facebook.svg') }}" alt="facebook">
            </a>
            <a class="btn--extern btn--login" href="{{ route('socialite.oauth', 'google') }}">
                Увійти через Google
                <img src="{{ Theme::url('img/Google.svg') }}" alt="Google">
            </a>
        </div>
        <div class="auth-body__choose">
            <div class="dashed-line"></div>
            <div class="auth-body__choose-text">Або</div>
            <div class="dashed-line"></div>
        </div>
        @endif
        <form class="auth-body__form" action="{{ url('login') }}" method="POST">
            @csrf
            @honeypot
            <div class="input__wrapper @error('login') error @enderror">
                <input class="input"
                       type="email"
                       placeholder="Електронна пошта"
                       name="email"
                       value="{{ old('email') }}"
                >
                @error('login') <p>{{ $message }}</p> @enderror
            </div>
            <div class="input__wrapper @error('password') error @enderror">
                <input class="input input--password"
                       type="password"
                       placeholder="Пароль"
                       name="password"
                >
                <button type="button" class="btn__show" name="password">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M21.8461 12.8722C22.5699 12.3533 23 12 23 12C23 12 22.5699 11.6467 21.8461 11.1278C19.8471 9.69497 15.6078 7 12 7C8.3922 7 4.15289 9.69497 2.15391 11.1278C1.43008 11.6467 1 12 1 12C1 12 1.43008 12.3533 2.15391 12.8722C4.15289 14.305 8.3922 17 12 17C15.6078 17 19.8471 14.305 21.8461 12.8722ZM16.3424 14.4804C17.1302 14.1283 17.9057 13.7189 18.6356 13.2917C19.4067 12.8404 20.0956 12.3881 20.654 12C20.0956 11.6119 19.4067 11.1596 18.6356 10.7083C17.9057 10.2811 17.1302 9.87175 16.3424 9.51959C16.7608 10.2506 17 11.0974 17 12C17 12.9026 16.7608 13.7494 16.3424 14.4804ZM12 8.4C13.9882 8.4 15.6 10.0118 15.6 12C15.6 13.9882 13.9882 15.6 12 15.6C10.0118 15.6 8.4 13.9882 8.4 12C8.4 10.0118 10.0118 8.4 12 8.4ZM7.65764 9.51959C7.23919 10.2506 7 11.0974 7 12C7 12.9026 7.23919 13.7494 7.65764 14.4804C6.86978 14.1283 6.09428 13.7189 5.36436 13.2917C4.59327 12.8404 3.90441 12.3881 3.34599 12C3.90441 11.6119 4.59327 11.1596 5.36436 10.7083C6.09428 10.2811 6.86978 9.87175 7.65764 9.51959Z"
                                  fill="black" />
                        </g>
                    </svg>
                </button>
                @error('password') <p>{{ $message }}</p> @enderror
            </div>
            <div class="auth-body__reset">
                <button type="button" class="btn__auth" data-bs-toggle="modal" data-bs-target="#resetModal">Відновити
                    пароль</button>
            </div>
            <button class="btn--intern btn--wide">
                Увійти
            </button>
            <button class="btn--extern btn--wide" type="button" data-bs-toggle="modal"
                    data-bs-target="#registerModal">
                Я хочу зареєструватися
            </button>
        </form>
    </main>
@endsection
