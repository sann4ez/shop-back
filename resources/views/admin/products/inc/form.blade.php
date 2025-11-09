@if(request('variant_uuid'))
    <input type="hidden" name="variant_uuid" value="{{ request('variant_uuid') }}">
@endif

<div class="row">
    <div class="col-md-9">
{{--        @if(empty($product))--}}
            @include('admin.products.inc.variation-form-create'/*, ['category' => $category]*/)
{{--        @else--}}
{{--            {!! Lte3::text('name', isset($product) ? $product->name : null, [--}}
{{--                'label' => 'Назва Групи',--}}
{{--            ]) !!}--}}

{{--            @include('admin.products.inc.variations-table-wrap', ['product' => $product])--}}
{{--        @endif--}}

        {!! Lte3::textarea('body', isset($product) ? $product->body : null, [
            'label' => 'Опис',
            'class' => 'f-tinymce',
        ]) !!}

{{--        <div class="row">--}}
{{--            @if(Domain::getOptIs('products.fields.recommends'))--}}
{{--                <div class="col">--}}
{{--                    {!! Lte3::select2('added[recommends]', isset($product) ? $product->getRecommends()->pluck('name', 'id')->toArray() : null, [], [--}}
{{--                        'label' => 'Рекомендовані товари',--}}
{{--                        'url_suggest' => route('admin.suggest.products'),--}}
{{--                        'multiple' => true,--}}
{{--                        'help' => '* CTRL для мультивибору'--}}
{{--                    ]) !!}--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
    </div>

    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                {!! Lte3::select2('status', null, \App\Models\Product::statusesList('name', 'key'), [
                    'label' => 'Статус',
                ]) !!}
            </div>
            <div class="col-md-6">
                {!! Lte3::datetimepicker('income_at', null, [
                    'label' => 'Дата надходження',
                    'format' => 'Y-m-d H:i',
                ]) !!}
            </div>
        </div>

        @if(true)
            @if(!empty($category))
                {!! Lte3::hidden('category[id]', $category->id) !!}
                {!! Lte3::text('category_name', $category->name, ['disabled' => 1, 'label' => 'Категорія']) !!}
            @else
                {!! Lte3::select2Tree('category[id]', [
                   'label' => 'Категорія',
                   'multiple' => 0,
                   'method_get' => 'GET',
                   'required' => 1,
                   'url_tree' => route('admin.suggest.terms', [
                        'vocabulary' => \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES,
                        'selected' => isset($product) ? $product->category_id : null,
                        'format' => 'treeselect',
                    ]),
                ]) !!}
            @endif
        @endif

        @php($categories = \App\Models\Term::query()->byVocabulary(\App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES)->get()->pluck('name', 'id')->toArray())
        @if(false)
        {!! Lte3::select2('terms[product_categories]', isset($product) ? $product->categories->pluck('id')->toArray() : [], $categories, [
            'label' => 'Категорії',
            'multiple' => 1,
            'max' => 4,
            'help' => '* Не обовязково. Для виведення каталозі, фільтрування, пошуку',
        ]) !!}
        @endif

        @if(false)
        {!! Lte3::select2('brand[id]', isset($product) ? $product->brand_id : null, \App\Models\Term::byDomain()->byVocabulary(\App\Models\Term::VOCABULARY_BRANDS)->withTrans()->get()->pluck('name', 'id')->toArray(), [
             'label' => 'Бренд',
         ]) !!}
        @endif

        @if(false)
        {!! Lte3::select2('markers', isset($product) ? $product->markers->pluck('id')->toArray() : null, \App\Models\Item::withTrans()->where('type', \App\Models\Item::TYPE_PRODUCT_MARKER)->get()->pluck('name', 'id')->toArray(), [
             'label' => 'Маркери',
             'multiple' => true,
         ]) !!}
        @endif

        @if(false)
            {!! Lte3::select2('productparity[id]', isset($product) ? $product->productparity_id : null, \App\Models\Term::byDomain()->byVocabulary(\App\Models\Term::VOCABULARY_PRODUCTPARITIES)->withTrans()->get()->pluck('name', 'id')->toArray(), [
                 'label' => 'Вид парності',
                 'empty_value' => '-'
             ]) !!}
        @endif

        @if(false)
            {!! Lte3::select2('productmodel[id]', isset($product) ? $product->productmodel_id : null, \App\Models\Term::byDomain()->byVocabulary(\App\Models\Term::VOCABULARY_PRODUCTMODELS)->withTrans()->get()->pluck('name', 'id')->toArray(), [
                 'label' => 'Модель',
                 'empty_value' => '-'
             ]) !!}
        @endif

        {{-- Атрибут для групування в каталозі --}}
        @if('default' === 'attribute')
            <?php
                $attributes = collect();
                $aId = null;
                $help = '';
                if(isset($product)) {
                    $attributes = $product->category?->attrs()->where('in_variant', true)->orderBy('weight')->get();
                    $aId = $product->category?->getAdded('attribute_groped');
                } elseif(isset($category) && $category) {
                    $attributes = $category->attrs()->where('in_variant', true)->orderBy('weight')->get();
                    $aId = $category->getAdded('attribute_groped');
                }
                if ($aId && ($gAttr = $attributes->where('id', $aId)->first())) {
                    $help = "В категорії вказано атрибут {$gAttr->name}";
                }
                $attributes = $attributes ?: collect();
            ?>

            {!! Lte3::select2('added[attribute_groped]', isset($product) ? $product->getAdded('attribute_groped') : [], $attributes->pluck('name', 'id')->toArray(), [
                'label' => 'Атрибут для групування варіацій в каталозі',
                'empty_value' => '--',
                'help' => $help,
            ]) !!}

        @endif

        {{-- TODO --}}
        {{-- Атрибути самого товару --}}
        {{--
        @if(0 && Domain::getOptIs('products.fields.attributes'))
            <?php
                if(isset($product)) {
                    $attributes = $product?->category?->attrs()->with('properties.translations', 'translations')->where('in_filter', true)->where('in_variant', false)->get() ?: collect([]);
                } elseif (isset($category)) {
                    $attributes = $category?->attrs()->with('properties.translations', 'translations')->where('in_filter', true)->where('in_variant', false)->get() ?: collect([]);
                }
            ?>
            @if($attributes->count())
                <div class="card card-warning box-solid">
                    <div class="card-body">
                        <div class="row">
                            @foreach($attributes as $attribute)
                                <div class="col-md-6">
                                    {!! Lte3::select2("properties[{$attribute->id}]", isset($product) ? $product->properties->pluck('id')->toArray() : [], $attribute->properties->sortBy('weight')->pluck('value', 'id')->toArray(), [
                                        'label' => $attribute->getName().':',
                                        //'empty_value' => '--',
                                        //'max' => 1,
                                        'multiple' => 1,
                                    ]) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif
        --}}

        @if(false)
            {!! Lte3::mediaImage('image', isset($product) ? $product : null, [
                'label' => 'Основне зображення',
            ]) !!}
        @endif

        @if(true)
        {!! Lte3::mediaImage('images', isset($product) ? $product : null, [
            'label' => 'Зображення ∞',
            'multiple' => true,
        ]) !!}
        @endif

    </div>
</div>

{{-- Додаткові поля --}}
{{--@if($addFields = \Domain::getOpt('products.add_fields', []))--}}
{{--    <div class="card --}}{{--collapsed-card--}}{{--">--}}
{{--        <div class="card-header" data-card-widget="collapse">--}}
{{--            <h3 class="card-title" data-card-widget="collapse">Додаткові поля та блоки</h3>--}}
{{--            <div class="card-tools">--}}
{{--                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i--}}
{{--                        class="fas fa-minus"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            @include('admin.products.inc.add-fields', ['fields' => $addFields, 'fieldsModel' => $product ?? null])--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}

<div class="text-right">
    {!! Lte3::btnReset('Вийти', ['url' => route('admin.products.index')]) !!}
    {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
</div>
