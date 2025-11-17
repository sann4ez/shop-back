<?php echo '
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
'; ?>
<yml_catalog date="{{\Carbon\Carbon::now()->format('Y-m-d H:m')}}">
    <shop>
        <name>{{ \Domain::getOpt('extern.rozetka.name') ?: config('app.name') }}</name>
        <company>{{ \Domain::getOpt('extern.rozetka.company') ?: config('app.name') }}</company>
        <url>{{ \Domain::getSelected('url') }}</url>
        <currencies>
            <currency id="UAH" rate="1"/>
        </currencies>
        <categories>
            @foreach($categories as $category)
                <category id="{{ $category->id }}" @if($category->rozetka_id) rz_id="{{ $category->rozetka_id }}" @endif>{{ $category->name }}</category>
            @endforeach
        </categories>
        <offers>
            @foreach($variations as $variation)
                @php($attrs = $variation->getAllAttributesPropertiesCharacteristicsListArray())
                @if(count($attrs) < 1)
                    @php(\Illuminate\Support\Facades\Log::warning("У варіації {$variation->sku} відсутні атрибути, потрібні для експорту Rozetka."))
                    @continue
                @endif
                @php($medias = $variation->getImages('images'))
                @if($medias->count() < 1)
                    @php(\Illuminate\Support\Facades\Log::warning("У варіації {$variation->sku} відсутні зображення, потрібні для експорту Rozetka."))
                    @continue
                @endif
                <offer id="{{ $variation->getRozetkaId() }}" available=@if($variation->stock_qty > 0)"true"@else"false"@endif>
                <url>{{ $variation->getUrlClient() }}</url>
                <price>{{ ceil($variation->getPrice()) }}</price>
                @if($priceOld = ceil($variation->getPriceOld()))
                    <price_old>{{ $priceOld }}</price_old>
                @endif
                @if($pricePromotion = ceil($variation->getPrices('promotion')))
                    <price_old>{{ $pricePromotion }}</price_old>
                @endif
                <currencyId>UAH</currencyId>
                <categoryId>{{ $variation->product->category_id }}</categoryId>
                @foreach($medias as $media)
                    @if($loop->index <= 14)
                        <picture>{{ $media->getUrl('big') }}</picture>
                    @endif
                @endforeach
                <vendor>{{ $variation->product->brand?->name ?? \Domain::getOpt('extern.rezetka.vendor') ?: config('app.name') }}</vendor>
                <stock_quantity>{{ $variation->stock_qty }}</stock_quantity>

                <name>{{ $variation->getRozetkaName() }}</name>
                <article>{{ $variation->getSku() }}</article>
                <description><![CDATA[{!! url_clear($variation->getBody()) !!}]]></description>


                @foreach($attrs as $attrName => $props)
                    <param name="{{ $attrName }}">{{ implode(', ', $props) }}</param>
                @endforeach

                </offer>
            @endforeach
        </offers>
    </shop>
</yml_catalog>
