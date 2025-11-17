@forelse($variations->load('properties', 'properties.translation', 'properties.attribute.translation') as $variation)
    @include('catalog.inc.variation-frame', ['variation' => $variation, 'key' => 'catalog'])
@empty
@endforelse
