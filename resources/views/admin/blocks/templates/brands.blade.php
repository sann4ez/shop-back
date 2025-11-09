{!! Lte3::hidden('type', 'brands') !!}

{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}

{!! Lte3::hidden('ids', '') !!}
@isset($block)
    @php
        $ids = $block->getIds('brands', []);
        $terms = \App\Models\Term::whereIn('id', $ids)->with('translations')->get();
    @endphp
    {!! Lte3::select2('ids[brands]', $ids, $terms->pluck('name', 'id')->toArray(), [
        'label' => 'Бренди',
        'multiple' => 1,
        'url_suggest' => route('admin.suggest.terms', ['vocabulary' => \App\Models\Term::VOCABULARY_BRANDS]),
    ]) !!}
@else
    {!! Lte3::select2('ids[brands]', [], [], [
        'label' => 'Бренди',
        'multiple' => 1,
        'url_suggest' => route('admin.suggest.terms', ['vocabulary' => \App\Models\Term::VOCABULARY_BRANDS]),
    ]) !!}
@endisset

{!! Lte3::select2('options[sort]', isset($block) ? $block->getOptions('sort') : null, ['default', 'random', 'name', 'rating', 'price'], ['label' => 'Впорядкувати за полем']) !!}
{!! Lte3::select2('options[order]', isset($block) ? $block->getOptions('order') : null, ['desc', 'asc'], ['label' => 'Впорядкувати в напрямку',]) !!}
{!! Lte3::number('options[limit]', isset($block) ? $block->getOptions('limit') : null, ['label' => 'Ліміт для виведення', 'default' => 12]) !!}
