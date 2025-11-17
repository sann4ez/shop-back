<div class="modal-header">
    <h2 class="title title--medium">Корзина</h2>
    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            viewBox="0 0 16 16"
            fill="none"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M9.41804 8.00001L15.2109 2.20712L13.7967 0.792908L8.00383 6.5858L2.21094 0.792908L0.796724 2.20712L6.58962 8.00002L0.796724 13.7929L2.21094 15.2071L8.00383 9.41423L13.7967 15.2071L15.2109 13.7929L9.41804 8.00001Z"
                fill="black"
            />
        </svg>
    </button>
</div>

@if(\Cart::getQty())
<div class="modal__form-content">
    <form action="{{ route('cart.sync') }}" method="post">
        @csrf
        <div class="modal__form-wrapper">
            @foreach(\Cart::purchases()->load('model.product.category', 'model.translations', 'variation.media') as $purchase)
            <div class="modal__form-item">
                <a href="{{ $purchase->getUrlClient() }}" class="modal__form-img-link">
                    <img loading="lazy" src="{{ $purchase->getImage() ?: Theme::url('img/img-error.png') }}" alt="{{ $purchase->getName() }}" class="modal__form-item-img">
                </a>
                <div class="modal__form-item-desc">
                    <a href="{{ $purchase->getUrlClient() }}" class="modal__form-item-name">{{ $purchase->getName() }}</a>
                    <div class="modal__form-item-wrapper">
                        <div class="price__counter js-counter-wrapper">
                            <button aria-label="decreaseGoodsCount" type="button" class="main-btn main-btn--counter main-btn--counter-locked js-counter-decrease">-</button>

                            <input type="number"
                                   class="price__amount main-input main-input--add js-counter-input js-cart-input"
                                   name="changed[{{$purchase->id}}][quantity]"
                                   value="{{ $purchase->getQty() }}"
                                   min="{{ $purchase->getMinQty() }}"
                                   max="{{ $purchase->variation?->getMaxQty() }}"
                                   step="{{ $purchase->getStep() }}"
                                   data-url="{{ route('cart.setQty', $purchase) }}"
                                   autocomplete="off"
                            >
                            <input type="hidden" value="{{ $purchase->id }}"
                                   name="changed[{{ $purchase->id }}][id]"
                                   autocomplete="off"
                            >

                            <button aria-label="increaseGoodsCount" type="button" class="main-btn main-btn--counter js-counter-increase">+</button>
                        </div>
                        <div class="modal__form-item-price">{{ $purchase->totalSum() }} грн</div>
                    </div>
                </div>
                <button class="modal__form-edit js-click-submit" data-url="{{ route('cart.remove', [$purchase, '_modal' => '#cart']) }}">
                    <svg class="icon-svg icon-svg-delete delete"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#delete"></use></svg>
                </button>
            </div>
            @endforeach
        </div>
    </form>
</div>

<div class="modal__form-buttons">
    <div class="modal__form-summary">
        <h3 class="title title--small">Всього</h3>
        <div class="devider devider--cart"></div>
        <div class="modal__form-price title title--small">{{ \Cart::totalSum() }} грн</div>
    </div>

    <a aria-label="confirmOrder" href="{{ route('cart.checkout') }}" class="main-btn main-btn--modal main-btn--width100 main-btn--second-green main-text main-text--semibold">
        Оформити замовлення
    </a>
    <a aria-label="toCatalog" href="{{ route('catalog.index') }}" class="main-btn main-btn--modal main-btn--light-gray main-btn--width100 main-text main-text--semibold">
        Продовжити покупки
    </a>
</div>

@else
    <div class="search-fail search-fail--cart">
        <div class="search-fail__logo">:(</div>
        <div class="search-fail__msg">Кошик порожній</div>
        <a aria-label="toCatalog" href="{{ route('catalog.index') }}" class="main-btn main-btn--green main-btn--width100">До покупок</a>
    </div>
@endif
