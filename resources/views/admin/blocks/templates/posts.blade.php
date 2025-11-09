{!! Lte3::hidden('type', 'posts') !!}

{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}

{!! Lte3::hidden('ids', '') !!}
@isset($block)
    @php
        $ids = $block->getIds('posts', []);
        $posts = \App\Models\Post::whereIn('id', $ids)->with('translations')->get();
    @endphp
    {!! Lte3::select2('ids[posts]', $ids, $posts->pluck('name', 'id')->toArray(), [
        'label' => 'Статті',
        'multiple' => 1,
        'selected' => $ids,
        'url_suggest' => route('admin.suggest.posts', ['type' => \App\Models\Post::TYPE_ARTICLE]),
    ]) !!}
@else
    {!! Lte3::select2('ids[posts]', [], [], [
        'label' => 'Статті',
        'multiple' => 1,
        'url_suggest' => route('admin.suggest.posts', ['type' => \App\Models\Post::TYPE_ARTICLE]),
    ]) !!}
@endisset

{!! Lte3::select2('options[sort]', isset($block) ? $block->getOptions('sort') : null, ['default', 'random', 'name', 'published_at'], ['label' => 'Впорядкувати за полем']) !!}
{!! Lte3::select2('options[order]', isset($block) ? $block->getOptions('order') : null, ['desc', 'asc'], ['label' => 'Впорядкувати в напрямку',]) !!}
{!! Lte3::number('options[limit]', isset($block) ? $block->getOptions('limit') : null, ['label' => 'Ліміт для виведення', 'default' => 12]) !!}
