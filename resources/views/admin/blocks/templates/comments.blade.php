{!! Lte3::hidden('type', 'comments') !!}

{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}

{!! Lte3::hidden('ids', '') !!}
@isset($block)
    @php
        $ids = $block->getIds('comments', []);
        $comments = \App\Models\Extern\Comment::whereIn('id', $ids)->get();
    @endphp
    {!! Lte3::select2('ids[comments]', $ids, $comments->map(fn($c) => ['name' => $c->getTitle(), 'id' => $c->id])->pluck('name', 'id')->toArray(), [
        'label' => 'Відгуки',
        'multiple' => 1,
        'selected' => $ids,
        'url_suggest' => route('admin.suggest.comments'),
    ]) !!}
@else
    {!! Lte3::select2('ids[comments]', [], [], [
        'label' => 'Відгуки',
        'multiple' => 1,
        'url_suggest' => route('admin.suggest.comments'),
    ]) !!}
@endisset

{!! Lte3::select2('options[model_type]', isset($block) ? $block->getOptions('model_type') : null, \App\Models\Extern\Comment::forList('name', 'model'), ['label' => 'Типи', 'multiple' => true]) !!}
{!! Lte3::select2('options[sort]', isset($block) ? $block->getOptions('sort') : null, ['default', 'random', 'rating', 'created_at'], ['label' => 'Впорядкувати за полем']) !!}
{!! Lte3::select2('options[order]', isset($block) ? $block->getOptions('order') : null, ['desc', 'asc'], ['label' => 'Впорядкувати в напрямку',]) !!}
{!! Lte3::number('options[limit]', isset($block) ? $block->getOptions('limit') : null, ['label' => 'Ліміт для виведення', 'default' => 12]) !!}
