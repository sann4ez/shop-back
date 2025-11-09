{!! Lte3::hidden('product_id', isset($product) ? $product->id : null, null) !!}

<div class="row">
    @if(\Domain::getOptIs('products.fields.name') && \Domain::getOpt('products.has_variations'))
    <div class="col-md-4">
        {!! Lte3::text('prodname', $product->name, [
            'label' => 'Назва Групи',
            'disabled' => 1,
            'placeholder' => 'Футболка',
        ]) !!}
    </div>
    @endif
    <div class="col-md">
        {!! Lte3::text('name', isset($productVariation) ? $productVariation->name : '', [
            'label' => 'Назва Варіації',
            'placeholder' => 'Футболка Червона ХL',
        ]) !!}
    </div>
</div>

{{-- Атрибути --}}

{{-- TODO: рефакторити --}}

@if(\Domain::getOpt('products.has_variations'))
    @php($attributes = $product?->category?->attrs()->with('properties.translations', 'translations')->where('in_variant', true)->get() ?: collect())
    @if($attributes->where('in_variant', true)->count())
        <div class="card card-warning box-solid" title="атрибути для формування варіцій" data-toggle="tooltip">
            <div class="card-body">
                <div class="row">
                    @foreach($attributes as $attribute)
                        <div class="col-md-6">
                            {!! Lte3::select2("properties[{$attribute->id}]", isset($productVariation) ? $productVariation->properties->pluck('id')->toArray() : [], $attribute->properties->sortBy('weight')->pluck('value', 'id')->toArray(), [
                                'label' => $attribute->getName().':',
                                'empty_value' => '--',
                                'max' => 1,
                            ]) !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endif

@php($attributes = $product?->category?->attrs()->with('properties.translations', 'translations')->where('in_filter', true)->where('in_variant', false)->get() ?: collect())
@if($attributes->where('in_filter', true)->where('in_variant', false)->count())
    <div class="card card-warning box-solid" title="атрибути для фільтрування, можна задавати по кілька штук">
        <div class="card-body">
            <div class="row">
                @foreach($attributes as $attribute)
                    <div class="col-md-6">
                        {!! Lte3::select2("properties[{$attribute->id}]", isset($productVariation) ? $productVariation->properties->pluck('id')->toArray() : [], $attribute->properties->sortBy('weight')->pluck('value', 'id')->toArray(), [
                            'label' => $attribute->getName().':',
                            'multiple' => 1,
                        ]) !!}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif


<div class="row">
    <div class="col-md-6">
        {!! Lte3::text('sku', isset($productVariation) ? $productVariation->sku : '', [
            'label' => 'SKU',
            'required' => 1,
            'default' => \App\Models\Shop\ProductVariation::generateValue('sku'),
            'append' => ['<i class="fas fa-recycle js-sku-make"></i>'],
        ]) !!}
    </div>
    @if(Domain::getOptIs('variations.fields.sku_extern'))
    <div class="col-md-6">
        {!! Lte3::text('sku_extern', isset($productVariation) ? $productVariation->sku_extern : '', [
             'label' => 'Артикул виробника',
         ]) !!}
    </div>
    @endif
</div>

@if(Domain::getOptIs('variations.fields.barcode'))
<div class="row">
    <div class="col-md-6">
        {!! Lte3::text('barcode', isset($productVariation) ? $productVariation->barcode : '', [
            'label' => 'Штрихкод',
            'class_wrap' => 'js-barcode-scan-field',
            'default' => \App\Models\Shop\ProductVariation::generateValue('barcode'),
            'append' => ['<button type="button" class="fas fa-print js-barcode-modal-print" title="Друкувати"></button>', '<i class="fas fa-recycle js-barcode-make" title="Генерувати"></i>', '<button type="button" class="fas fa-barcode js-barcode-scan" title="Сканувати"></button>'],
        ]) !!}
    </div>
    @isset($productVariation)
    <div class="col-md-6">
        <div class="text-center">
            <img class="js-barcode-show" data-barcode="{{ $productVariation->getBarcode() }}" style="width: 200px; max-height: 100px">
        </div>
    </div>
    @endisset
</div>
@endif

@if(Domain::getOptIs('variations.fields.barcodes'))
<div class="row">
    <div class="col-md-12">
        {!! Lte3::text('barcodes', isset($productVariation) ? $productVariation->barcodes : '', [
             'label' => 'Додаткові Штрихкоди',
             'class_wrap' => 'js-barcode-scan-field',
             'append' => ['<button type="button" class="fas fa-barcode js-barcode-scan"></button>'],
             'help' => '* Можна вказати декілька штрихкодів через знак ","'
         ]) !!}
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-6">
        {!! Lte3::number('price', isset($productVariation) ? $productVariation->price : 0, [
            'label' => 'Ціна',
            'step' => 0.01,
            'pattern' => "[0-9]*"
        ]) !!}
    </div>
    @if(Domain::getOptIs('variations.fields.price_old'))
    <div class="col-md-6">
        {!! Lte3::number('price_old', isset($productVariation) ? $productVariation->price_old : 0, [
            'label' => 'Стара ціна',
            'step' => 0.01,
            'required' => 1,
            'pattern' => "[0-9]*"
        ]) !!}
    </div>
    @endif

    @if(Domain::getOptIs('variations.fields.price_cost'))
    <div class="col-md-6">
        {!! Lte3::number('price_cost', null, [
            'label' => 'Ціна закупки',
            'help' => '* Для статистики, аналітики',
            'pattern' => "[0-9]*"
        ] + (\Domain::getOpt('warehouses.on') ? ['disabled' => 1] : [])) !!}
    </div>
    @endif

    @if(Domain::getOptIs('variations.fields.stock_qty'))
    <div class="col-md-6">
        {!! Lte3::number('stock_qty', null, [
            'label' => 'Залишок',
            'help' => '* К-сть доступних на складі',
            'pattern' => "[0-9]*"
        ] + (\Domain::getOpt('warehouses.on') ? ['disabled' => 1] : [])) !!}
    </div>
    @endif
    @if(Domain::getOptIs('variations.fields.limit_qty'))
    <div class="col-md-6">
        {!! Lte3::number('limit_qty', isset($productVariation) ? $productVariation->limit_qty : 1, [
            'label' => 'Лімітована к-сть',
            'help' => '* К-сть, менше якої вважати критичні запаси варіації',
            'pattern' => "[0-9]*",
        ]) !!}
    </div>
    @endif
    @if(Domain::getOptIs('variations.fields.multiplicity'))
    <div class="col-md-6">
        {!! Lte3::number('multiplicity', isset($productVariation) ? $productVariation->multiplicity : 1, [
            'label' => 'Кратність',
            'help' => '* Кратність для покупки',
            'pattern' => "[0-9]*",
        ]) !!}
    </div>
    @endif
    @if(Domain::getOptIs('variations.fields.min_qty'))
    <div class="col-md-6">
        {!! Lte3::number('min_qty', isset($productVariation) ? $productVariation->min_qty : 1, [
            'label' => 'Мінімальна к-сть',
            'help' => '* Мінімальна к-сть для покупки',
            'pattern' => "[0-9]*",
        ]) !!}
    </div>
    @endif
</div>

@if(Domain::getOptIs('variations.fields.body'))
    {!! Lte3::textarea('body', isset($productVariation) ? $productVariation->body : null, [
        'label' => 'Опис',
        'class' => 'f-tinymce',
    ]) !!}
@endif

@if(\Domain::getOpt('products.has_variations'))
    @if(Domain::getOptIs('variations.fields.images'))
    @isset($cloningProductVariation)
    {!! Lte3::checkbox('_cloning_images', null, [
        'label' => 'Клонувати також зображення',
        'class_control' => 'custom-switch',
    ]) !!}
    @endisset
    {!! Lte3::mediaImage('images', isset($productVariation) ? $productVariation : null, [
        'label' => 'Зображення ∞',
        'multiple' => true,
    ]) !!}
    @endif

    {!! Lte3::select2('status', null, \App\Models\Shop\ProductVariation::statusesList('name', 'key'), [
        'label' => 'Статус',
    ]) !!}
@endif

<div class="row">
@if(Domain::getOptIs('variations.fields.relateds'))
    <div class="col-md-12">
        {!! Lte3::select2('added[relateds]', isset($productVariation) ? $productVariation->getRelateds()->pluck('name', 'id')->toArray() : null, [], [
            'label' => 'Супутні / Зв\'язані варіації',
            'url_suggest' => route('admin.suggest.product-variations'),
            'multiple' => true,
            'close_on_select' => 0,
            'help' => '* CTRL для мультивибору'
        ]) !!}
    </div>
@endif
@if(Domain::getOptIs('variations.fields.recommends'))
    <div class="col-md-12">
        {!! Lte3::select2('added[recommends]', isset($productVariation) ? $productVariation->getRecommends()->pluck('name', 'id')->toArray() : null, [], [
            'label' => 'Рекомендовані / Подібні варіації',
            'url_suggest' => route('admin.suggest.product-variations'),
            'multiple' => true,
            'close_on_select' => 0,
            'help' => '* CTRL для мультивибору'
        ]) !!}
    </div>
@endif
</div>

{{-- Додаткові поля --}}
@if($addFields = \Domain::getOpt('variations.add_fields', []))
    {!! Lte3::hidden('fields', '') !!}
    @include('admin.products.inc.add-fields', ['fields' => $addFields, 'fieldsModel' => $productVariation ?? null])
@endif


@if(Domain::getOptIs('products.fields.productparity') && isset($productVariation))
    @php($paritiesVariations = $productVariation->getParities())
    @if($paritiesVariations->count())
    <div class="callout callout-info">
        <h5>Автоматично визначені парні варіації:</h5>
        <ul>
        @foreach($paritiesVariations as $pVariation)
            <li><a href="{{ route('admin.products.edit', [$pVariation->product, 'variation' => $pVariation->id]) }}" target="_blank" title="{{ $pVariation->id }}">
                {{ $pVariation->getName() }}
            </a></li>
        @endforeach
        </ul>
    </div>
    @endif
@endif

{{--
{!! Lte3::slug('slug', null, ['label' => 'Slug', 'help' => '* Генерується автоматично з імені варіації']) !!}
--}}


@if(\Domain::getOpt('extern.rozetka'))
    <div class="row">
        <div class="col-md-6">
            {!! Lte3::checkbox('rozetka_send', null, [
                'label' => 'Передавати в Rozetka / Prom',
                'class_control' => 'custom-switch',
                'help' => '* якщо статус Опубліковано'
            ]) !!}
        </div>
        <div class="col-md-6">
            {!! Lte3::text('rozetka_id', null, ['label' => 'RozetkaID', 'help' => '', 'disabled' => true]) !!}
        </div>
    </div>
@endif

@can('dev')
{!! Lte3::select2('product_id', [$product->id => $product->name ?: $product->id], [], [
    'label' => 'Група',
    'url_suggest' => route('admin.suggest.products'),
]) !!}
@endcan
