@extends('app')

@section('content')
    <div class="wrapper">
        <div id="wrapper-blur"></div>
    </div>
    <main class="main">
        <a href="/"> <img src="{{ Theme::url('img/logo.svg') }}" alt="Logo" class="main-logo" /></a>
        <div class="main-wrapper">
            @foreach(\App\Models\Domain::where('is_active', true)
                ->where('added->showing', '1')
                ->with('media')->get() as $domain)

                <a href="{{ $domain->getUrlWithHost() }}" target="_blank"  class="main-item" data-color="#211A29">
                    <div class="main-item__top">
                        <div class="main-item__info">
                            <h3 class="main-item__title">{{ $domain->name }}</h3>
                            <div href="paw.com" class="main-item__link">{{ $domain->host }}</div>
                        </div>
                        <div class="main-item__link">
                            <img
                                    src="{{ $domain->getMyFirstMediaUrl('logo', '', Theme::url('img/logo-paw.svg')) }}"
                                    alt="Logo"
                                    class="main-item__link-logo"
                            ></div>
                        <div> <img src="{{ Theme::url('img/arrow.svg') }}" class="main-arrow" /></div>
                    </div>

                    <img src="{{ $domain->getMyFirstMediaUrl('image', '', Theme::url('img/image1.png')) }}" alt="Image" class="main-item__img" />
                </a>
            @endforeach
        </div>
        @if($var = \Variable::getArray("site.email", null, \Domain::getGroup()))
        <a href="mailto:{{ $var }}" class="main-link">
            <img src="{{ Theme::url('img/email.svg') }}" alt="Email" /> {{ $var }}
        </a>
        @endif
        @if($var = \Variable::getArray("site.copyright", null, \Domain::getGroup()))
        <p class="main-copyright">{{ $var }}</p>
        @endif
    </main>
@stop
