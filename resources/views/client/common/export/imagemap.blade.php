@foreach ($variations ?? [] as $variation)
    @if(($medias = $variation->getMedia('images')) && count($medias))
    <url>
        <loc>{{ $variation->getUrlClient() }}</loc>
        @foreach($medias as $media)
        <image:image>
            <image:loc>{{ $media->getUrl('preview') }}</image:loc>
        </image:image>
        @endforeach
    </url>
    @endif
@endforeach
@foreach ($product_categories ?? [] as $term)
    @if(($medias = $term->getMedia('image')) && count($medias))
    <url>
        <loc>{{ $term->getUrlClient() }}</loc>
        @foreach($medias as $media)
        <image:image>
            <image:loc>{{ $media->getUrl() }}</image:loc>
        </image:image>
        @endforeach
    </url>
    @endif
@endforeach
@foreach ($brands ?? [] as $term)
    @if(($medias = $term->getMedia('logo')) && count($medias))
    <url>
        <loc>{{ $term->getUrlClient() }}</loc>
        @foreach($medias as $media)
        <image:image>
            <image:loc>{{ $media->getUrl() }}</image:loc>
        </image:image>
        @endforeach
    </url>
    @endif
@endforeach
@foreach ($posts ?? [] as $term)
    @if(($medias = $term->getMedia('image')) && count($medias))
    <url>
        <loc>{{ $term->getUrlClient() }}</loc>
        @foreach($medias as $media)
        <image:image>
            <image:loc>{{ $media->getUrl() }}</image:loc>
        </image:image>
        @endforeach
    </url>
    @endif
@endforeach
@foreach ($promotions ?? [] as $promotion)
    @if(($medias = $promotion->getMedia('image')) && count($medias))
        <url>
            <loc>{{ $promotion->getUrlClient() }}</loc>
            @foreach($medias as $media)
                <image:image>
                    <image:loc>{{ $media->getUrl() }}</image:loc>
                </image:image>
            @endforeach
        </url>
    @endif
@endforeach
