@extends('layouts.app')

@php
    Seo::setModel($page);
@endphp

@section('content')

    <main>

        <section class="contacts container">

            <div class="header-page">
                {{ Breadcrumbs::render('pages.show', $page) }}
                <h1 class="title">{{ $page->name }}</h1>
            </div>

            <div class="contacts__content">
                <div class="contacts__wrapper">
                    @if($block = Block::init('contacts'))
                        @if($items = $block->getContentSort('phones'))
                            <div class="contacts__block">
                                <h3 class="contacts__title title title--small">
                                    <svg class="icon-svg icon-svg-call color-red call"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#call"></use></svg>
                                    Відділ продажів
                                </h3>
                                <ul class="contacts__list">
                                    @foreach($items as $item)
                                    <li class="contacts__list-item main-text">
                                        <span class="contacts__list-item-num">
                                            {{ $item['operator'] }}
                                        </span>
                                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $item['number']) }}" class="contacts__list-link main-text">{{ $item['number'] }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    @if($block = Block::init('contacts'))
                        @if($items = $block->getContent('email'))
                            <div class="contacts__block">
                                <h3 class="contacts__title title title--small">
                                    <svg class="icon-svg icon-svg-mail color-red mail"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#mail"></use></svg>
                                    Email
                                </h3>
                                <a href="mailto:{{ $block->getContent('email') }}" class="contacts__mail main-text main-text--semibold">{{ $block->getContent('email') }}</a>
                            </div>
                        @endif
                    @endif

                    @if($block = Block::init('contacts'))
                        @if($items = $block->getContent('schedule'))
                            <div class="contacts__block">
                                <h3 class="contacts__title title title--small">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 17C13.866 17 17 13.866 17 10C17 6.13401 13.866 3 10 3C6.13401 3 3 6.13401 3 10C3 13.866 6.13401 17 10 17ZM10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" fill="black"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 5.5H10.5V9.79289L13.3536 12.6464L12.6464 13.3536L9.5 10.2071V5.5Z" fill="#121212"/>
                                    </svg>
                                    Приймання замовлень
                                </h3>
                                <ul class="contacts__list">
                                    <li class="contacts__list-item">
                                        {!! $block->getContent('schedule') !!}
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>

                <form class="contacts__form main-form main-form--personal main-form--contacts js-submit-ajax" action="{{ route('lead') }}" method="POST" data-action="form.reset">
                    @csrf
                    <input type="hidden" name="form" value="contacts" autocomplete="off">
                    <div class="main-form__header main-form__header--contacts">
                        <h2 class="title title--medium">Виникли запитання?</h2>
                        <p class="main-text">Задайте їх нашим спеціалістам!</p>
                    </div>
                    <div class="main-form__block">
                        <div class="main-form__name main-form__name--contacts">
                            <div class="main-form__input main-form__input--contacts">
                                <label for="name" class="checkout__form-text">Ім'я</label>
                                <input name="name" id="name-1" type="text" value="{{ old('name') }}" class="main-input main-input--white main-input--checkout" autocomplete="off">
                                @error('name') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                            </div>
                            <div class="main-form__input main-form__input--contacts @error('phone') main-form__input--error @enderror">
                                <label for="phone" class="checkout__form-text">Телефон <span class="required">*</span></label>
                                <input name="phone" id="phone-1" type="tel" value="{{ old('phone') }}" class="main-input main-input--white main-input--checkout" autocomplete="off">
                                @error('phone') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="main-form__input main-form__input--contacts main-form__input--textarea @error('message') main-form__input--error @enderror">
                        <label for="message" class="checkout__form-text">Повідомлення <span class="required">*</span></label>
                        <textarea name="message" id="message" class="main-input main-input--white main-input--checkout main-input--textarea">{{ old('message') }}</textarea>
                        @error('message') <span class="main-form__error-msg"> {{ $message }} </span>@enderror
                    </div>
                    <button aria-label="send" class="main-btn main-btn--second-green main-btn-width100 main-btn--contacts">Надіслати</button>
                </form>
            </div>
        </section>
    </main>

@endsection
