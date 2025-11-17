
    <div class="cart-top">
        <div class="title">Кошик</div>
        <button class="btn" data-bs-dismiss="modal" aria-label="Close">
            <svg class="icon-svg icon-svg-exit ">js-counter-input
                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#exit"></use>
            </svg>
        </button>
    </div>
    @if(\Cart::getQty())
        <form action="{{ route('cart.sync') }}" method="post">
            @csrf
            <ul class="order-list">
                    @foreach(\Cart::purchases()->load('model.product.category', 'model.translations', 'variation.media') as $purchase)
                    <li class="order-item">
                            <a href="{{ $purchase->getUrlClient() }}" class="order-link">
                                <img src="{{ $purchase->getImageUrl() ?: Theme::url('img/nophoto.webp') }}"
                                     alt="{{ $purchase->getName() }}"
                                     class="order-img"
                                ></a>

                            <a href="{{ $purchase->getUrlClient() }}"
                               class="order-text"> {{ $purchase->getName() }}</a>

                            <div class="order-count js-count-wrap js-counter-wrapper">
                                <button class="order-btn disabled {{--js-ajax-form--}} js-counter-decrease" data-fn="variationN" type="button">
                                    <svg class="icon-svg icon-svg-minus ">
                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#minus"></use>
                                    </svg>
                                </button>
                                <input type="number"
                                       class="js-count-input js-count-input-btn js-counter-input"
                                       name="changed[{{$purchase->id}}][quantity]"
                                       value="{{ $purchase->getQty() }}"
                                       min="{{ $purchase->getMinQty() }}"
                                       max="{{ $purchase->variation?->getMaxQty() }}"
                                       step="{{ $purchase->getStep() }}"
                                       data-url="{{ route('cart.setQty', $purchase) }}"
                                       autocomplete="off"
                                >
                                <input type="hidden" value="{{ $purchase->id }}"
                                       name="changed[{{ $purchase->id }}][id]" autocomplete="off">
                                <button class="order-btn {{--jp js-ajax-form--}} js-counter-increase" {{--data-fn="variationN"--}} type="button">
                                    <svg class="icon-svg icon-svg-plus ">
                                        <use xlink:href="{{ Theme::url('img/sprite.svg') }}#plus"></use>
                                    </svg>
                                </button>
                            </div>
                            <span class="order-price">{{ $purchase->totalSum() }} грн</span>
                            <button class="order-btn">
                                <svg class="icon-svg icon-svg-delete js-click-submit"
                                     data-url="{{ route('cart.remove', [$purchase, '_modal' => '#basketModal']) }}">
                                    <use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use>
                                </svg>
                            </button>
                        </li>
                    @endforeach
            </ul>
        </form>
    @endif
        @if(\Cart::getQty() < 1)
        <div class="cart-empty">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon-svg icon-svg-cart-empty" width="200" height="201" viewBox="0 0 200 201" fill="none">
                <rect y="0.5" width="200" height="200" rx="100" fill="#F4E5E7"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M97.2587 46.6188C88.0539 46.6188 80.592 54.0808 80.592 63.2855V77.8974H73.7427V63.2855C73.7427 50.298 84.2711 39.7695 97.2587 39.7695C110.246 39.7695 120.775 50.298 120.775 63.2855V77.8974H113.925V63.2855C113.925 54.0808 106.463 46.6188 97.2587 46.6188Z" fill="#E25566"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M100.913 42.9658C91.7087 42.9658 84.2468 50.4277 84.2468 59.6324V74.2443H77.3975V59.6324C77.3975 46.6449 87.9259 36.1165 100.913 36.1165C113.901 36.1165 124.429 46.6449 124.429 59.6324V74.2443H117.58V59.6324C117.58 50.4277 110.118 42.9658 100.913 42.9658Z" fill="#EA959F"/>
                <path d="M48.2128 156.192L55.589 73.9997C55.7158 72.5872 56.8996 71.5049 58.3178 71.5049H139.855C141.274 71.5049 142.457 72.5872 142.584 73.9997L149.96 156.192C150.104 157.795 148.841 159.176 147.231 159.176H50.9416C49.3318 159.176 48.069 157.795 48.2128 156.192Z" fill="#E25566"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M62.4617 71.5049L55.5173 148.886C55.3734 150.489 56.6363 151.87 58.2461 151.87H149.573L149.961 156.192C150.105 157.795 148.842 159.176 147.232 159.176H50.9421C49.3323 159.176 48.0694 157.795 48.2133 156.192L55.5895 73.9997C55.7163 72.5872 56.9001 71.5049 58.3183 71.5049H62.4617Z" fill="#D63044"/>
                <path d="M101.808 128.674C94.2102 128.674 90.4111 123.56 90.4111 113.331C90.4111 103.103 94.2102 97.9888 101.808 97.9888C109.407 97.9888 113.206 103.103 113.206 113.331C113.206 123.56 109.407 128.674 101.808 128.674ZM101.808 126.701C103.649 126.701 104.979 125.634 105.797 123.501C106.645 121.368 107.069 117.978 107.069 113.331C107.069 108.685 106.645 105.295 105.797 103.161C104.979 101.028 103.649 99.9614 101.808 99.9614C99.9673 99.9614 98.623 101.028 97.7755 103.161C96.9573 105.295 96.5481 108.685 96.5481 113.331C96.5481 117.978 96.9573 121.368 97.7755 123.501C98.623 125.634 99.9673 126.701 101.808 126.701Z" fill="white"/>
            </svg>
            <p class="cart-text">Кошик порожній</p>
            <a href="{{ route('catalog.index') }}" class="btn--intern">До покупок</a>
        </div>
{{--                            <div class="cart__footer--error">--}}
{{--                                Необхідна кількість товарів у кошику: від {{\Variable::getArray('shop.orders.min_qty', 0, \Domain::getSelected('id'))}} шт (однакових або різних)--}}
{{--                            </div>--}}
{{--                            <div class="cart__footer--error">--}}
{{--                                Необхідна сума кошику: від {{\Variable::getArray('shop.orders.min_sum', 0, \Domain::getSelected('id'))}}грн--}}
{{--                            </div>--}}
        @else
        <div class="cart-bottom">
            <a href="{{ route('cart.checkout') }}" class="btn--intern">Оформлення замовлення ·&nbsp;<span>{{ \Cart::totalSum() }} грн</span></a>
            <a href="{{ route('catalog.index') }}" class="btn btn--extern btn--wide">Продовжити покупки</a>
        </div>
        @endif
