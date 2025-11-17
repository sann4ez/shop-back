<footer class="footer">
    <div class="banner footer__subscribe">
        <div class="banner__inner">
            <div class="banner__info">
                <div class="banner__info-title">
                    <svg class="icon-svg icon-svg-mail mail">
                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#mail"></use>
                    </svg>
                    Розсилка
                </div>

                <p class="banner__info-subtitle">
                    Дізнавайтеся про новинки, знижки та найкращі пропозиції
                </p>
            </div>

            <div class="banner__actions">
                <form action="{{ route('lead') }}" class="footer__subscribe-form js-submit-ajax" method="POST"
                      data-action="form.reset">
                    @csrf
                    @honeypot
                    <input type="hidden" name="form" value="newsletter">
                    <div class="input__wrapper @error('email') error @enderror">
                        <input
                                class="input input-footer"
                                name="email"
                                type="email"
                                placeholder="Електронна пошта"
                                value="{{ old('email') }}"
                        >
                        @error('email') <p>{{ $message }}</p> @enderror
                    </div>
                    <button class="btn--intern footer__btn" type="submit">
                        Підписатися
                    </button>
                </form>
            </div>
        </div>
        <img src="{{ Theme::url('img/hand-left.svg') }}" class="footer__hand-top" alt="hand-left">
        <img
                src="{{ Theme::url('img/hand-right.svg') }}"
                class="footer__hand-bottom"
                alt="hand-right"
        >
    </div>

    <div class="footer__content container">
        <div class="footer__section">
            <a href="{{ route('home') }}" class="footer__logo">
                <img class="footer__logo-img" src="{{ Theme::url('img/footer-logo.svg') }}" alt="footer-logo">
            </a>
            @if($block = Block::init('contacts'))
                @if($items = $block->getContentSort('phones'))
                    <div class="footer__phones">
                        <div class="footer__icon">
                            <svg class="icon-svg icon-svg-phone ">
                                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#phone"></use>
                            </svg>
                        </div>

                        <ul class="footer__phones-list">
                            @foreach($items as $item)
                                <li class="footer__phones-item">
                                    <a class="link" href="tel:{{ preg_replace('/[^0-9]/', '', $item['number']) }}">
                                        {{ $item['number'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="footer__location">
                        <div class="footer__location-wrapper">
                            <div class="footer__icon">
                                <svg class="icon-svg icon-svg-location_pin ">
                                    <use xlink:href="{{ Theme::url('img/sprite.svg') }}#location_pin"></use>
                                </svg>
                            </div>

                            <a
                                    class="link"
                                    href="{{ $block->getContent('map.link', '#') }}"
                                    target="_blank"
                            >
                                {{ $block->getContent('address') }}
                            </a>
                        </div>
                        <p class="footer__location-time">{!! $block->getContent('schedule') !!}</p>
                    </div>
                @endif
            @endif

            {{--            <div class="footer__social">--}}
            {{--                @if($items = $block->getContentSort('socials'))--}}
            {{--                    @foreach($items as $item)--}}
            {{--                        <a target="_blank" href="{{ $item['url'] }}">--}}
            {{--                            <img--}}
            {{--                                class="footer__social-icon"--}}
            {{--                                src="/client/themes/home-mama/img/{{$item['name']}}.svg"--}}
            {{--                                alt="{{ $item['name'] }}"--}}
            {{--                            />--}}
            {{--                        </a>--}}
            {{--                    @endforeach--}}
            {{--                @endif--}}
            {{--            </div>--}}

            <div class="footer__corp">
                <p class="text text-gray">{!! \Variable::getArray('site.copyright', 'Всі права захищені © 2023') !!}
                    <br> Зроблено в <a href="https://itspace.company/?utm_source=referral&amp;utm_campaign=home.mama"
                                       target="_blank" class="link">ITS</a></p>
            </div>
        </div>
        <div class="footer__section catalog-nav">
            <p class="footer__title">Каталог</p>
            <nav class="footer__nav">
                <ul class="footer__nav-list footer__nav-list--catalog">
                    @foreach(\App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)
                        ->with('translations')->get()->toTree() as $term)
                        <li class="footer__nav-item">
                            <a href="{{ $term->getUrlClient() }}" class="link">{{ $term->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <div class="footer__section">
            <p class="footer__title">Меню</p>

            <!-- <div class="footer__location">

                <a href="https://goo.gl/maps/WqmTJLRCJCq4PA3v5" target="_blank">
                  вул. Геологів, 22. Ринок "Оптовик", ряд 2, місця: 1Г-2Г, 1В, м.
                  Хмельницький, Україна
                </a>
              </div> -->
            <nav class="footer__nav">
                @if(($items = \App\Models\Menuitem::getMenu('footer')) && $items->count())
                    <ul class="footer__nav-list">
                        @foreach($items as $item)
                            <li class="footer__nav-item">
                                <a target="{{ $item->target }}" href="{{ $item->getUrlClient() }}"
                                   class="link">{{ $item->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </nav>
        </div>

        <div class="footer__corp footer__corp--mobile">
            <p class="text text-gray">
                {!! Block::init('contacts')->getContent('copyright', 'Всі права захищені © 2023') !!}
                <br> Зроблено в <a href="https://itspace.company/?utm_source=referral&amp;utm_campaign=home.mama"
                                   target="_blank" class="link">ITS</a></p>
        </div>
    </div>
</footer>
