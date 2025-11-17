<svg class="icon-svg icon-svg-cart header__icon">
    <use xlink:href="{{ Theme::url('img/sprite.svg') }}#cart"></use>
</svg>
@if(\Cart::getQty() > 0)
    <span class="header__cart-quantity">{{ \Cart::getQty() }}</span>
@else
    <span class="header__favorite-quantity">0</span>
@endif
