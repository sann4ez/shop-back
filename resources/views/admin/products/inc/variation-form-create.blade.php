@isset($productVariation)
{!! Lte3::hidden('variation_id', $productVariation->id) !!}
@endisset
<div class="card">
    {{--
    <div class="card-header">
        <h3 class="card-title">Варіація</h3>
    </div>
    --}}
    <div class="card-body">

        <div class="row">
            <div class="col-md">
                {!! Lte3::text('variation[name]', null, [
                    'label' => 'Назва Варіації',
                    'placeholder' => 'Футболка Червона ХL',
                ]) !!}
            </div>
        </div>

        {{-- Атрибути --}}

        {{-- TODO: рефакторити --}}
        @php($attributes = collect())
        @isset($product)
            @php($attributes = $product->category?->attrs()->with('properties')->where('in_filter', true)->where('in_variant', false)->get())
            @php($productVariation = $product->variation)
        @elseif(isset($category) && $category)
            @php($attributes = $category->attrs()->with('properties')->where('in_filter', true)->where('in_variant', false)->get())
        @endif
        @if($attributes && $attributes->count())
            <div class="card card-warning card-solid" title="атрибути для фільтрування, можна задавати по кілька штук">
                <div class="card-body">
                    <div class="row">
                        @foreach($attributes as $attribute)
                            <div class="col-md-6">
                                {!! Lte3::select2("variation[properties]['.$attribute->id.']",
                                    isset($productVariation) ? $productVariation->properties->pluck('id')->toArray() : [],
                                    $attribute->properties->pluck('value', 'id')->sortBy('weight')->toArray(), [
                                        'label' => $attribute->getName() . ':',
                                        'empty_value' => '--',
                                        'multiple' => true,
                                ]) !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        <div class="row">
            @php($code = \App\Models\ProductVariation::generateValue('sku'))
            <div class="col-md-6">
                {!! Lte3::text('variation[sku]', null, [
                    'label' => 'SKU',
                    'append' => ['<i class="fas fa-recycle js-sku-make"></i>'],
                    'default' => $code,
                    'required' => 1,
                ]) !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                {!! Lte3::number('variation[price]', null, [
                    'label' => 'Ціна',
                    'step' => 0.01, 'min' => 0,
                    'pattern' => "[0-9]*"
                ]) !!}
            </div>

            <div class="col-md-6">
                {!! Lte3::number('variation[price_old]', null, [
                    'label' => 'Стара ціна',
                    'step' => 0.01, 'min' => 0,
                    'required' => 1,
                    'pattern' => "[0-9]*"
                ]) !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                {!! Lte3::select2('variation[added][recommends]', isset($productVariation) ? $productVariation->getRecommends()->pluck('name', 'id')->toArray() : null, [], [
                    'label' => 'Рекомендовані варіації',
                    'url_suggest' => route('admin.suggest.product-variations'),
                    'multiple' => true,
                    'close_on_select' => 0,
                    'help' => '* CTRL для мультивибору. Якщо не вказано - будуть з тої ж категорії'
                ]) !!}
            </div>
        </div>

        {{-- Додаткові поля --}}
{{--        @if($addFields = \Domain::getOpt('variations.add_fields', []))--}}
{{--            @if(\Domain::getOpt('products.has_variations'))--}}
{{--                <div class="callout callout-success">--}}
{{--                    <p>Додаткові поля варіації [{{ implode(', ', $addFields) }}] можна задати після збереження внесених тут даних 😉</p>--}}
{{--                </div>--}}
{{--            @else--}}
{{--                {!! Lte3::hidden('fields', '') !!}--}}
{{--                @include('admin.products.inc.add-fields', ['fields' => $addFields, 'fieldsModel' => $productVariation ?? null])--}}
{{--            @endif--}}
{{--        @endif--}}

        {{--{!! Lte3::text('variation[slug]', null, ['label' => 'Slug', 'help' => '* Генерується автоматично з імені варіації']) !!}--}}

        {!! Lte3::hidden('variation[id]') !!}

    </div>
</div>

