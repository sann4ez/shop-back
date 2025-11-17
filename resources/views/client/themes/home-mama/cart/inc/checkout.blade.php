@foreach(session('warnings', []) as $key => $warns)
    @if($key === 'qty')
        <div class="alert alert-warning">
            <strong><i class="fas fa-warning"></i> </strong>
            <p>Недостатньо наявності товарів на складі:</p>
            @foreach($warns as $warn)
                <p>{{ $warn['name'] }} - доступно {{ $warn['value'] }}</p>
            @endforeach
        </div>
    @elseif($key === 'multiplicity')
        <div class="alert alert-warning">
            <strong><i class="fas fa-warning"></i> {{ trans('lte::alerts.warning') }}</strong>
            <p>Не доступна кратність товарів:</p>
            @foreach($warns as $warn)
                <p>{{ $warn['name'] }} - кратність {{ $warn['value'] }}</p>
            @endforeach
        </div>
    @elseif($key === 'min_qty')
        <div class="alert alert-warning">
            <strong><i class="fas fa-warning"></i> {{ trans('lte::alerts.warning') }}</strong>
            <p>Не достатня мінімальна к-сть товарів в корзині:</p>
            @foreach($warns as $warn)
                <p>{{ $warn['name'] }} - мінімально {{ $warn['value'] }}</p>
            @endforeach
        </div>
    @endif
@endforeach

<div class="checkout__order-top">
    <div class="title--small">Ваше замовлення</div>
    <button class="link link-cart" type="button" data-bs-toggle="modal" data-bs-target="#basketModal">
        Редагувати
    </button>
</div>
<ul class="order-list checkout__list">
    @php
        $purchases = \Cart::purchases();
    @endphp
    @foreach($purchases->count() ? $purchases->load('model.product.category', 'model.translations') : [] as $purchase)
        <li class="order-item">
            <a href="{{ $purchase->getUrlClient() }}" class="order-link">
                <img src="{{ $purchase->getImageUrl() ?: Theme::url('img/nophoto.webp') }}"
                     alt="{{ $purchase->getName() }}" class="order-img"
                ></a>
            <div class="order-wrapper">
                <div class="order-text--title">
                    <a href="{{ $purchase->getUrlClient() }}"
                       class="order-text">{{ $purchase->getName() }}</a>
                </div>
                <span class="order-wrapper__info">
                            <span class="order-text">{{ $purchase->getQty() }}х</span>
                            <span class="order-price">{{ $purchase->totalSum() }} грн</span></span
                >
            </div>
        </li>
    @endforeach
</ul>
<div class="dashed-line"></div>
<ul class="checkout__list checkout__list-info">
    <li class="checkout__item">Вартість товарів <span><span class="js-num-format">{{ \Cart::getTotalInfo()['purchases_all'] ?? 0 }}</span> грн</span></li>
    @php($ds = \Cart::getTotalInfo()['total_discount'] ?? 0)
    @if($ds > 0)
    <li class="checkout__item">Знижка <span><span class="js-num-format">{{ $ds }}</span> грн</span></li>
    @endif
    <li class="checkout__item">
        Доставка <span>За тарифами перевізника</span>
    </li>
</ul>
<div class="dashed-line"></div>
<div class="checkout__item total">Разом <span><span class="js-num-format">{{ \Cart::totalSum() }}</span> грн</span></div>
<p class="checkout__order-text">
    Натискаючи «Підтвердити замовлення», я погоджуюсь з
    <a href="{{ route('pages.show', ['page' => 'terms']) }}" target="_blank">Умовами використання</a>
    та
    <a href="{{ route('pages.show', ['page' => 'policy']) }}" target="_blank">Політикою конфіденційності</a>
</p>
<button class="checkout__order-btn btn--intern js-checkout__order-btn" type="submit" form="checkout-form">
    Підтвердити замовлення
</button>
