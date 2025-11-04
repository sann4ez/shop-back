@php
    $group = \Domain::getLocale();
    $tags = $seoable?->getRawSeoTags($group) ?: [];
    $patterns = $seoable->getSeoPatterns() ?: [];
@endphp
{!! Lte3::hidden('group', $group) !!}

{!! Lte3::text('seo[h1]', \Arr::get($tags, 'h1'), ['label' => 'Заголовок (h1)', 'placeholder' => \Arr::get($patterns, 'h1')]) !!}

<div class="row">
    <div class="col-md-6">

        {!! Lte3::text('seo[title]', \Arr::get($tags, 'title'), ['label' => 'tag (title)', 'placeholder' => \Arr::get($patterns, 'title')]) !!}
        {!! Lte3::textarea('seo[description]', \Arr::get($tags, 'description'), ['label' => 'meta (description)', 'rows' => 5, 'placeholder' => \Arr::get($patterns, 'description')]) !!}
{{--
        {!! Lte3::lfmImage('seo[og_image]', \Arr::get($tags, 'og_image'), [
            'label' => 'meta (og:image)'
        ]) !!}
--}}
        @if($slugVariation ?? false)
            {!! Lte3::text('variation[slug]', null, ['label' => 'Slug', 'help' => '* Генерується автоматично з імені варіації']) !!}
        @elseif(empty($slugHide))
            {!! Lte3::slug('slug', $seoable?->slug, ['label' => 'Slug']) !!}
        @endif

        {!! Lte3::checkbox('seo[robotses][index]', \Arr::get($tags, 'robotses.index', 1), [
            'label' => 'meta robots (index)',
            'checked_value' => 1,
            'unchecked_value' => 0,
            'class_control' => 'custom-switch'
        ]) !!}
        {!! Lte3::checkbox('seo[robotses][follow]', \Arr::get($tags, 'robotses.follow', 1), [
            'label' => 'meta robots (follow)',
            'checked_value' => 1,
            'unchecked_value' => 0,
            'class_control' => 'custom-switch'
        ]) !!}

    </div>
    <div class="col-md-6">
        {!! Lte3::textarea('seo[text]', \Arr::get($tags, 'text'), ['label' => 'Додатковий SEO-текст', 'class' => 'f-tinymce']) !!}

        {{-- MULTYITEMS: --}}
        <div class="card f-wrap f-multyblocks">
            <div class="card-header">
                <h3 class="card-title">FAQ для мікророзмітки</h3>
            </div>
            <div class="card-body">
                <div class="f-items sortable-y" data-input-weight-class="js-input-weight">

                    {{-- TEMPLATE MULTIPLE FIELDS --}}
                    <template class="f-item-template">
                        <div class="f-item">
                            <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                    class="fa fa-trash"></i></a>
                            <i class="fa fa-arrows-alt-v cursor-move"></i>
                            {!! Lte3::text('seo[faq][$i][question]', null, [
                                'label' => 'Питання',
                            ]) !!}
                            {!! Lte3::textarea('seo[faq][$i][answer]', null, [
                                'label' => 'Відповідь',
                            ]) !!}
                            {!! Lte3::hidden('seo[faq][$i][weight]', null, [
                                'class' => 'js-input-weight',
                            ]) !!}

                        </div>
                    </template>

                    {{-- ALREADY SAVED MULTIPLE FIELDS --}}
                    @if ($faq = \Arr::get($tags, 'faq'))
                        @foreach ($faq as $item)
                            <div class="f-item">
                                <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                        class="fa fa-trash"></i></a>
                                <i class="fa fa-arrows-alt-v cursor-move"></i>
                                {!! Lte3::text("seo[faq][{$loop->index}][question]", $item['question'] ?? '', ['label' => 'Питання']) !!}
                                {!! Lte3::textarea("seo[faq][{$loop->index}][answer]", $item['answer'] ?? '', ['label' => 'Відповідь',]) !!}
                                {!! Lte3::hidden("seo[faq][{$loop->index}][weight]", $item['weight'] ?? 0, ['class' => 'js-input-weight']) !!}
                            </div>
                        @endforeach
                    @else
                        <p class="js-msg-empty">Елементів не додано 😢</p>
                    @endisset
                </div>
                <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
</div>
