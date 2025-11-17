@extends('app')

@section('content')
    <div class="wrapper">
        <div id="wrapper-blur"></div>
    </div>
    <main class="main">
        <a href="/"> <img src="{{ Theme::url('img/logo.svg') }}" alt="Logo" class="main-logo" /></a>
        <div class="main-wrapper">

            {!! $page->body !!}

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