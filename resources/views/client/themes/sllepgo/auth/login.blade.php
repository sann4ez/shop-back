@extends('app')

@section('content')
    <main class="main auth">
        <a href="/"> <img src="{{ Theme::url('img/logo.svg') }}" alt="Logo" class="main-logo" /></a>
        <div class="default-form__wrapper">
            <form action="{{ url('login') }}" method="post" class="default-form">
                @csrf
                @honeypot
                <div class="default-input__wrapper @error('email') error @enderror">
                    <input type="text" name="email" value="{{ old('email') }}" class="default-input" placeholder="Email" />
                    @error('email')<p>{{ $message }}</p>@enderror
                </div>
                <div class="default-input__wrapper @error('password') error @enderror">
                    <input type="text" name="password" class="default-input" type="password" placeholder="Password" />
                    @error('password')<p>{{ $message }}</p>@enderror
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} />

                    <label for="remember" class="checkbox-check">
                        <svg class="icon-svg icon-svg-check icon-white"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#check"></use></svg></label
                    >
                    <label for="remember" class="default-text_black">Remember Me</label>
                </div>
                <button class="default-btn">Log in</button>
            </form>
        </div>
        <p class="main-copyright">
            @if($var = \Variable::getArray("site.copyright", null, \Domain::getGroup()))
            <p class="main-copyright">{{ $var }}</p>
            @endif
        </p>
    </main>
@stop
