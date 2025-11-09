{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}

{!! Lte3::hidden('ids', '') !!}
@isset($block)
    @php
        $ids = $block->getIds('promotions', []);
        $posts = \App\Models\Shop\Promotion::whereIn('id', $ids)->with('translations')->get();
    @endphp
    {!! Lte3::select2('ids[promotions]', $ids, $posts->pluck('name', 'id')->toArray(), [
        'label' => 'Акції',
        'multiple' => 1,
        'selected' => $ids,
        'url_suggest' => route('admin.suggest.promotions'), /*TODO*/
    ]) !!}
@else
    {!! Lte3::select2('ids[promotions]', [], [], [
        'label' => 'Акції',
        'multiple' => 1,
        'url_suggest' => route('admin.suggest.promotions'),/*TODO*/
    ]) !!}
@endisset

{!! Lte3::select2('options[type]', isset($block) ? $block->getOptions('type') : null, \App\Models\Shop\Promotion::typesList('name', 'key'), ['label' => 'Тип акції', 'multiple' => true]) !!}
{!! Lte3::select2('options[sort]', isset($block) ? $block->getOptions('sort') : null, ['default', 'random', 'start_at', 'end_at'], ['label' => 'Впорядкувати за полем']) !!}
{!! Lte3::select2('options[order]', isset($block) ? $block->getOptions('order') : null, ['desc', 'asc'], ['label' => 'Впорядкувати в напрямку',]) !!}
{!! Lte3::number('options[limit]', isset($block) ? $block->getOptions('limit') : null, ['label' => 'Ліміт для виведення', 'default' => 12]) !!}
