@foreach ($product_categories ?? [] as $term)
    <url>
        <loc>{{ $term->getUrlClient() }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $term->getDatetime('updated_at')->toAtomString() }}</lastmod>
        @if(($medias = $term->getMedia('image')) && count($medias))
            @foreach($medias as $media)
            <image:image>
                <image:loc>{{ $media->getUrl() }}</image:loc>
            </image:image>
            @endforeach
        @endif
    </url>
@endforeach
@foreach ($post_categories ?? [] as $term)
    <url>
        <loc>{{ $term->getUrlClient() }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $term->getDatetime('updated_at')->toAtomString() }}</lastmod>
    </url>
@endforeach
@foreach ($brands ?? [] as $term)
    <url>
        <loc>{{ $term->getUrlClient() }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $term->getDatetime('updated_at')->toAtomString() }}</lastmod>
        @if(($medias = $term->getMedia('logo')) && count($medias))
            @foreach($medias as $media)
            <image:image>
                <image:loc>{{ $media->getUrl() }}</image:loc>
            </image:image>
            @endforeach
        @endif
    </url>
@endforeach
@foreach ($pages ?? [] as $page)
    <url>
        <loc>{{ $page->getUrlClient() }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $page->getDatetime('updated_at')->toAtomString() }}</lastmod>
    </url>
@endforeach
@foreach ($posts ?? [] as $post)
    <url>
        <loc>{{ $post->getUrlClient() }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $post->getDatetime('updated_at')->toAtomString() }}</lastmod>
        @if(($medias = $post->getMedia('image')) && count($medias))
            @foreach($medias as $media)
            <image:image>
                <image:loc>{{ $media->getUrl() }}</image:loc>
            </image:image>
            @endforeach
        @endif
    </url>
@endforeach
@foreach ($variations ?? [] as $variation)
    <url>
        <loc>{{ $variation->getUrlClient() }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $variation->getDatetime('updated_at')->toAtomString() }}</lastmod>
        @if(($medias = $variation->getMedia('images')) && count($medias))
            @foreach($medias as $media)
            <image:image>
                <image:loc>{{ $media->getUrl('preview') }}</image:loc>
            </image:image>
            @endforeach
        @endif
    </url>
@endforeach
@foreach ($promotions ?? [] as $promotion)
    <url>
        <loc>{{ $promotion->getUrlClient() }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $promotion->getDatetime('updated_at')->toAtomString() }}</lastmod>
        @if(($medias = $promotion->getMedia('image')) && count($medias))
            @foreach($medias as $media)
            <image:image>
                <image:loc>{{ $media->getUrl() }}</image:loc>
            </image:image>
            @endforeach
        @endif
    </url>
@endforeach
