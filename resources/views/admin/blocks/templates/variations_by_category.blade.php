{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}

{!! Lte3::hidden('ids', '') !!}
{!! Lte3::select2Tree('ids[category]', [
  'label' => 'Категорія',
  'multiple' => 0,
  'method_get' => 'GET',
  'url_tree' => route('admin.suggest.terms', [
       'vocabulary' => \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES,
       'selected' => isset($block) ? $block->getIds('category', []) : [],
       'format' => 'treeselect',
   ]),
]) !!}

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
