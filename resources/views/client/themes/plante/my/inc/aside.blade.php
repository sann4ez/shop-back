<ul class="side-menu side-menu--personal">
    <li class="side-menu__item"><a href="{{ route('my.profile.edit') }}" class="side-menu__link @if(Route::is('my.profile.edit')) side-menu__link--active @endif main-text">
            <svg class="icon-svg icon-svg-user_data color-red user user_data"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#user_data"></use></svg>
            Особисті дані</a></li>
    {{-- TODO show
    <li class="side-menu__item"><a href="{{ route('my.favorites.index') }}" class="side-menu__link @if(Route::is('my.favorites.index')) side-menu__link--active @endif main-text">
            <svg class="icon-svg icon-svg-user_like color-red user user_like"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#user_like"></use></svg>
            Вибране</a></li>
    <li class="side-menu__item"><a href="{{ route('my.orders.index') }}" class="side-menu__link @if(Route::is('my.orders.index')) side-menu__link--active @endif main-text">
            <svg class="icon-svg icon-svg-user_orders color-red user user_orders"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#user_orders"></use></svg>
            Історія замовлень</a></li>
    --}}
</ul>
