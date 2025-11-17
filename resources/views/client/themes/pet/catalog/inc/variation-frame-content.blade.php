<div class="image-frame image-frame-style-1 image-frame-effect-2 mb-3">
    <span class="image-frame-wrapper image-frame-wrapper-overlay-bottom image-frame-wrapper-overlay-light image-frame-wrapper-align-end">
        <div class="badges-wrapper">
            @if($variation->getDiscountVal() > 0)
            <span class="badge badge-danger">SALE</span>
            <span class="badge badge-success">{{$variation->getDiscountVal()}}%</span>
            @endif
            {{--
            @if($product->type === \App\Models\Store\Product::TYPE_COLLECTION)
                <span class="badge badge-info">Collection</span>
            @endif
            --}}
        </div>
        <a href="{{ $variation->getUrlClient() }}">
            <img src="{{ $variation->getImageUrl() ?: Theme::url('img/products/product-1.jpg') }}" class="img-fluid" alt="">
        </a>
        <span class="image-frame-action">
            @if($variation->getAvailable() === 0)
                <a href="#" disabled="" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2">ADD TO CART</a>
            @else
                <a href="#" data-url="{{ route('cart.add', $variation) }}" class="btn btn-primary btn-rounded font-weight-semibold btn-v-3 btn-fs-2 js-action-form">ADD TO CART</a>
            @endif
        </span>
    </span>
</div>
<div class="product-info d-flex flex-column flex-lg-row justify-content-between">
    <div class="product-info-title">
        <h3 class="text-color-default text-2 line-height-1 mb-1"><a href="{{ $variation->getUrlClient() }}">{{ $variation->getName() }}</a></h3>
        <span class="price font-primary text-4"><strong class="text-color-dark" data-currency="{{$variation->currency_code}}">{{$variation->getPrice()}}</strong></span>
        @if($variation->getPriceOld() > 0)
            <span class="old-price font-primary text-line-trough text-1"><strong class="text-color-default" data-currency="{{$variation->currency_code}}">{{$variation->getPriceOld()}}</strong></span>
        @endif
    </div>
    <div class="product-info-rate d-flex">
        @include('catalog.inc.rating-stars-list', ['rating' => $variation->product->getRating()])
    </div>
</div>