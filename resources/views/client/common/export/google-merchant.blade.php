@foreach($variations ?? [] as $variation)
    <entry>
        <g:id>{{ $variation->getSku() }}</g:id>
        <g:title>{{ $variation->getName() }}</g:title>
        @if($body = $variation->getBody())
        <g:description>{{ $body }}</g:description>
        @endif
        <g:link>{{ $variation->getUrlClient() }}</g:link>
        @if($mediaUrl = ($variation->getFirstMediaUrl('images', 'google_merchant') ?: $variation->product->getFirstMediaUrl('images', 'google_merchant')))
        <g:image_link>{{ $mediaUrl }}</g:image_link>
        @endif
        <g:availability>{{ $variation->getAvailabilityForFeed() }}</g:availability>
        <g:price>{{ $variation->getPrice() }} UAH</g:price>
        @if($googleCategoryMerchantId = $variation->product?->category?->google_merchant_id)
        <g:google_product_category>{{ $googleCategoryMerchantId }}</g:google_product_category>
        @endif
        <g:identifier_exists>no</g:identifier_exists>
    </entry>
@endforeach
