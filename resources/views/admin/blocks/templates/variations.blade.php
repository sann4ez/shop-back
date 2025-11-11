{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}

{!! Lte3::hidden('ids', '') !!}
@isset($block)
    @php
        $ids = $block->getIds('variations', []);
        $variations = \App\Models\ProductVariation::whereIn('id', $ids)->get();
    @endphp
    {!! Lte3::select2('ids[variations]', $ids, $variations->pluck('name', 'id')->toArray(), [
        'label' => 'Варіації',
        'multiple' => 1,
        'selected' => $ids,
        'url_suggest' => route('admin.suggest.product-variations'),
    ]) !!}
@else
{!! Lte3::select2('ids[variations]', [], [], [
    'label' => 'Варіації',
    'multiple' => 1,
    'url_suggest' => route('admin.suggest.product-variations'),
]) !!}
@endisset

<div class="row">
    <div class="col-md-4">
        {!! Lte3::select2('options[sort]', isset($block) ? $block->getOptions('sort') : null, ['default', 'random', 'name', 'rating', 'price', 'created_at', 'income_at'], ['label' => 'Впорядкувати за полем']) !!}
    </div>
    <div class="col-md-4">
        {!! Lte3::select2('options[order]', isset($block) ? $block->getOptions('order') : null, ['desc' => 'По спаданню', 'asc' => 'По зростанню'], ['label' => 'Впорядкувати в напрямку',]) !!}
    </div>
    <div class="col-md-4">
        {!! Lte3::select2('options[groped_type]', isset($block) ? $block->getOptions('groped_type') : null, ['all' => 'Без групування - всі', 'main' => 'Основна варіація групи', 'attribute' => 'Групування по атрибуту',], ['label' => 'Групування варіацій',]) !!}
    </div>
    <div class="col-md-4">
        {!! Lte3::select2('options[in_stock]', isset($block) ? $block->getOptions('in_stock') : null, ['1' => 'Всі', '2' => 'Тільки наявні', '3' => 'Тільки відсутні',], ['label' => 'Наявність на складі',]) !!}
    </div>
    <div class="col-md-4">
        {!! Lte3::number('options[limit]', isset($block) ? $block->getOptions('limit') : null, ['label' => 'Ліміт для виведення', 'default' => 12]) !!}
    </div>
</div>

{!! Lte3::checkbox('options[has_discount]', null, [
    'label' => 'Має стару/нову/акційну ціну',
    'class_control' => 'custom-switch',
]) !!}

{!! Lte3::checkbox('options[has_promotion]', null, [
    'label' => 'Бере участь в акції',
    'class_control' => 'custom-switch',
]) !!}
