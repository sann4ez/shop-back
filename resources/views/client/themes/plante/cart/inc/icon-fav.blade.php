<svg class="icon-svg icon-svg-like color-red user-like"><use xlink:href="{{ Theme::url('img/sprite.svg') }}#like"></use></svg>
@auth
@if($count = \App\Models\Favorite::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->count())
    <span class="header__user-cart-count">{{ $count }}</span>
@endif
@endauth
