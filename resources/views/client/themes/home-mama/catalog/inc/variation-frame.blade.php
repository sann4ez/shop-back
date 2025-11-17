<div class="card">
    <div class="card-top">
        @if($percent = $variation->getPrices('discount_percent'))
            <span class="card-label">
                <svg class="icon-svg icon-svg-star "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#star"></use></svg>
                <span> -{{ $percent }}%</span>
            </span>
        @endif

        @if($variation->isFavorite())
            <button class="card-like card-like--full js-click-submit"
                    data-url="{{ route('my.favorites.store', $variation) }}">
                <svg class="icon-svg icon-svg-heart-full "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#heart-full"></use></svg>
            </button>
        @else
            <button class="card-like js-click-submit"
                    data-url="{{ route('my.favorites.store', $variation) }}">
                <svg class="icon-svg icon-svg-default "><use xlink:href="{{ Theme::url('img/sprite.svg') }}#default"></use></svg>
            </button>
        @endif
        <a href="{{ $variation->getUrlClient() }}" class="card-link--img">
            <img src="{{ $variation->getImageUrl('images', 'preview') ?: Theme::url('img/nophoto.webp') }}" width="350" height="380"
                 onerror="this.onerror=null;this.src='img/nophoto.webp';" alt="Image" class="card-img">
        </a>
    </div>
    <a href="{{ $variation->getUrlClient() }}" class="card-title">{{ $variation->getNameList() }}</a>
    <div class="card-info">
        @switch($variation->getAvailableStatus())
            @case('missing')
                <div class="card-status disabled">Немає в наявності</div>
                @break
            @case('terminate')
                <div class="card-status ending">Закінчується</div>
                @break
            @case('available')
            @case('unlimited')
                <div class="card-status">В наявності</div>
                @break
        @endswitch
        <div class="card-price__wrapper">
            @if($variation->getPriceOld())
                <div class="card-price--old">{{$variation->getPriceOld()}} грн</div>
                <div class="card-price new">{{$variation->getPrice()}} грн</div>
            @else
                <div class="card-price">{{$variation->getPrice()}} грн</div>
            @endif
        </div>
    </div>

    <button class="card-btn card-btn-mobile btn--intern js-url-ajax"
            @if($variation->inCart())
            data-bs-toggle="modal" data-bs-target="#basketModal"
            @elseif($variation->getAvailableStatus() === 'missing')
            disabled style="opacity: .5"
            @elseif(1 || $variation->countCart())
                data-url="{{ route('cart.add', [$variation, 'quantity' => $variation->getMinQty()]) }}"
        @endif
    >
        <svg class="icon-svg icon-svg-cart_add ">
            <use xlink:href="{{ Theme::url('img/sprite.svg') }}#cart_add"></use>
        </svg>
    </button>

    <div class="card-variation">
        <div class="card-variation__stats">
            @foreach(($key ?? '') === 'catalog' ? $variation->properties ?? [] : $variation->properties->take(2) ?? [] as $property)
            <div class="card-variation__stat">
                <div class="card-variation__stat-name">{{ $property?->attribute?->name }}:</div>
                <a href="" class="card-variation__stat-value">{{ $property?->value }}</a>
            </div>
            @endforeach
        </div>

        <button class="card-btn btn--intern js-url-ajax"
                @if($variation->inCart())  {{--вже в корзині--}}
                data-bs-toggle="modal" data-bs-target="#basketModal"
                @elseif($variation->getAvailableStatus() === 'missing')  {{--немає в наявності--}}
                disabled style="opacity: .5"
                @elseif(1 || $variation->countCart())
                    data-url="{{ route('cart.add', [$variation, 'quantity' => $variation->getMinQty()]) }}"  {{--можна купувати--}}
            @endif
        >
            <svg class="icon-svg icon-svg-cart_add ">
                <use xlink:href="{{ Theme::url('img/sprite.svg') }}#cart_add"></use>
            </svg>
        </button>
    </div>
</div>
