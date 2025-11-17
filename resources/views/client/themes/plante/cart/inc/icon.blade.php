<svg class="icon-svg icon-svg-cart color-red user-cart"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#cart"></use></svg>
@if(\Cart::getQty())
    <span class="header__user-cart-count">@if(\Cart::getQty() >= 100) 99+ @else {{ \Cart::getQty() }} @endif</span>
@endif
