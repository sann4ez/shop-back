{!! Lte3::hidden('type', 'product_categories') !!}

{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}

{!! Lte3::hidden('ids', '') !!}

{!! Lte3::select2Tree('ids[categories]', [
  'label' => 'Категорія',
  'multiple' => 1,
  'method_get' => 'GET',
  'url_tree' => route('admin.suggest.terms', [
       'vocabulary' => \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES,
       'selected' => isset($block) ? $block->getIds('categories', []) : [],
       'format' => 'treeselect',
   ]),
]) !!}

{!! Lte3::select2('options[sort]', isset($block) ? $block->getOptions('sort') : null, ['default', 'random', 'name', 'rating', 'price'], ['label' => 'Впорядкувати за полем']) !!}
{!! Lte3::select2('options[order]', isset($block) ? $block->getOptions('order') : null, ['desc', 'asc'], ['label' => 'Впорядкувати в напрямку',]) !!}
{!! Lte3::number('options[limit]', isset($block) ? $block->getOptions('limit') : null, ['label' => 'Ліміт для виведення', 'default' => 12]) !!}
{!! Lte3::checkbox('options[is_root]', null, [
    'label' => 'Тільки головні категорії',
    'class_control' => 'custom-switch',
]) !!}
{!! Lte3::checkbox('options[with_children]', null, [
    'label' => 'Для категорій підгружати підкатегорії',
    'class_control' => 'custom-switch',
]) !!}
