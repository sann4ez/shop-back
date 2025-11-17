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

<div class="checkout__order-card">
    <div class="checkout__order-header">
        <h3 class="title title--small">Ваше замовлення</h3>
        <a href="#" data-bs-toggle="modal" data-bs-target="#cart" class="main-link">Редагувати</a>
    </div>
    <div class="checkout__order-goods">
        @foreach(\Cart::purchases()->load('model.product.category', 'model.translations') as $purchase)
        <div class="checkout__order-good">
            <div class="checkout__order-good-item">
                <a href="{{ $purchase->getUrlClient() }}" class="checkout__order-good-link">
                    <img loading="lazy" src="{{ $purchase->getImage() ?: Theme::url('img/img-error.png') }}"
                         alt="{{ $purchase->getName() }}"
                         class="checkout__order-good-img">
                </a>
                <a href="{{ $purchase->getUrlClient() }}" class="checkout__order-good-name">{{ $purchase->getName() }}</a>
            </div>
            <div class="checkout__order-good-amount main-text">{{ $purchase->getQty() }}х</div>
            <div class="checkout__order-good-price main-text">{{ $purchase->totalSum() }} грн</div>
        </div>
        @endforeach
    </div>
    <div class="checkout__order-total">
        <div class="checkout__order-total-conclusion">
            <div class="checkout__order-total-prop main-text main-text--color-dark-gray">{{ trans_choice('client.Goods for the amount', \Cart::getQty(), ['count' => \Cart::getQty()]) }}:</div>
            <div class="checkout__order-total-value main-text">{{ \Cart::totalSum() }} грн</div>
        </div>

        <div class="checkout__order-total-conclusion">
            <div class="checkout__order-total-prop main-text main-text--color-dark-gray">Доставка:</div>
            <div class="checkout__order-total-value main-text">за тарифами перевізника</div>
        </div>

        <div class="devider"></div>

        <div class="checkout__order-total-conclusion">
            <div class="checkout__order-total-prop">Всього:</div>
            <div class="checkout__order-total-value checkout__order-total-value--bold">{{ \Cart::totalSum() }} грн</div>
        </div>

    </div>
</div>
<button aria-label="confirmOrder" class="checkout__order-btn main-btn main-btn--green" type="submit" form="checkout-form">Підтвердити замовлення</button>
<p class="checkout__order-agreement">“Відправляючи форму ви погоджуєтесь на <a href="{{ route('pages.show', ['page' => 'terms']) }}" class="main-link">Умови використання</a> та <a href="{{ route('pages.show', ['page' => 'policy']) }}" class="main-link">Політику конфіденційності</a>.”</p>
