@forelse($variations as $variation)
    @include('catalog.inc.variation-frame', ['variation' => $variation])
@empty
@endforelse
