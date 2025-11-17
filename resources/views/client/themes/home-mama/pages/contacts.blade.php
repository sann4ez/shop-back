@extends('layouts.app')

@php
    Seo::setModel($page);
@endphp

@section('content')
    <main class="default-page">
        <div class="contacts container">
            <div class="contacts-top">

                {{ Breadcrumbs::render('pages.show', $page) }}

                <h1 class="title"> {{ $page->name }} </h1>
            </div>
            <div class="contacts__wrapper">
                <div class="contacts__content">
                    <div class="contacts__content-left">
                    @if($block = Block::init('contacts'))
                        @if($items = $block->getContentSort('phones'))
                            <div class="title title-phone">
                                {{ \Illuminate\Support\Arr::first($items)['number'] }}
                            </div>

                            <div class="title title--small">
                                {{ $block->getContent('email') }}
                            </div>
                            <div class="contacts__schedule">
                                <div class="contacts__schedule-adress">
                                    <svg class="icon-svg icon-svg-location_pin "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#location_pin"></use></svg>
                                    <p class="text text-mod">{{ $block->getContent('address') }}</p>
                                </div>
                                {!! $block->getContent('schedule') !!}
                            </div>
                        @endif

                        @if($items = $block->getContentSort('socials'))
                        <ul class="article__interaction-social">
                            @foreach($items as $item)
                            <li>
                                <a class="social-item" href="{{ $item['url'] ?? '#' }}" rel="nofollow">
                                    <img src="{{ Theme::url("img/{$item['name']}.svg") }}" alt="{{ $item['name'] }}">
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    @endif
                    </div>
                    <div class="contacts__content-right">
                        <div class="contacts__content-head">
                            <div class="title title--small">
                                Зворотній зв’язок
                            </div>
                            <div class="contacts__content-description">
                                <p class="text text-gray">Ми зв’яжемось з вами протягом 5хв ❤️</p>
                            </div>
                        </div>
                        <form class="contacts__form js-submit-ajax" action="{{ route('lead') }}" method="POST" data-action="form.reset">
                            @csrf
{{--                            @honeypot--}}
                            <input type="hidden" name="form" value="contacts">
                            <div class="input__wrapper">
                                <input
                                    class="input phone"
                                    placeholder="+38 ___ ___ __ __"
                                    data-inputmask="'mask': '+38 (099)-999-99-99'"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                >
                            </div>
                            <div class="textarea__wrapper input__wrapper">
                                <textarea class="textarea" type="text" name="message" placeholder="Повідомлення">{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class=" btn--intern">
                                Зв’язатися
                            </button>
                        </form>
                    </div>
                </div>
                <div class="contacts__map">
                    @if($block = Block::init('contacts'))
                        {!! $block->getContent('map.iframe') !!}
                    @else
                        <iframe class="contacts__map-redirect" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d157.77844731776884!2d25.32773451059736!3d50.74859348546583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4725994de40004a7%3A0x23a27b792bf265e8!2sHome.mama.ua!5e0!3m2!1sru!2sua!4v1695400929205!5m2!1sru!2sua" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
