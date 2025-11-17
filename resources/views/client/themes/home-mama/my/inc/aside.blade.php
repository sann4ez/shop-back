<aside class="profile__menu">
    <ul class="profile__list">
        @if(auth()->user()->can('dashboard.auth'))
            <li class="profile__item ">
                <a href="/admin" class="profile__link">
                    <svg class="icon-svg icon-svg-save "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#save"></use></svg>
                    Адмінпанель</a
                >
            </li>
        @endif

        <li class="profile__item @if(Route::is('my.orders.index')) active @endif">
            <a href="{{ route('my.orders.index') }}" class="profile__link">
                <svg class="icon-svg icon-svg-save "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#save"></use></svg>
                Мої замовлення</a
            >
        </li>
        <li class="profile__item @if(Route::is('my.profile.edit')) active @endif">
            <a href="{{ route('my.profile.edit') }}" class="profile__link"
            ><svg class="icon-svg icon-svg-user "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#user"></use></svg>Мої дані</a
            >
        </li>
        <li class="profile__item @if(Route::is('my.favorites.index')) active @endif">
            <a href="{{ route('my.favorites.index') }}" class="profile__link"
            ><svg class="icon-svg icon-svg-like "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#like"></use></svg>
                Обрані</a
            >
        </li>
        <li hidden class="profile__item @if(Route::is('my.profile.comments')) active @endif">
            <a href="{{--{{ route('my.profile.comments') }}--}}" class="profile__link reviews"
            ><svg
                    width="24"
                    height="24"
                    fill="none"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M1 22L2.14634 16.561M1 22L6.43902 20.8537M1 22H13.5M2.14634 16.561L16.2931 2.41421C17.0741 1.63317 18.3405 1.63316 19.1215 2.41421L20.7927 4.08537C21.4595 4.75215 21.4595 5.83322 20.7927 6.5V6.5M2.14634 16.561L6.43902 20.8537M6.43902 20.8537L20.7927 6.5M16.561 22H21.9268M20.7927 6.5L22.2927 8L16.5 13.7927"
                        stroke="#1B1818"
                        stroke-width="1.4"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                Мої відгуки</a
            >
        </li>
        <li class="profile__item">
            <button href="#" class="profile__link profile__link--logout" data-bs-toggle="modal"
                    data-bs-target="#logoutModal">
                <svg class="icon-svg icon-svg-logout "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#logout"></use></svg>Вийти
            </button>
        </li>
    </ul>
</aside>
