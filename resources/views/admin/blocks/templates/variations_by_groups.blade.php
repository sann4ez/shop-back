{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}
{!! Lte3::text('content[subtitle]', null, ['label' => 'Підзаголовок']) !!}


{{-- MULTYITEMS: --}}
<div class="card f-wrap f-multyblocks" data-fn-inits="initSelect2">
    <div class="card-body">
        <div class="f-items sortable-y" data-input-weight-class="js-input-weight" >
            {{-- TEMPLATE MULTIPLE FIELDS --}}
            <template class="f-item-template">
                <div class="f-item">
                    <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                class="fa fa-trash"></i></a>
                    <i class="fa fa-arrows-alt-v cursor-move"></i>
                    {!! Lte3::text('content[groups][$i][title]', null, ['label' => 'Заголовок групи']) !!}
                    {!! Lte3::select2('content[groups][$i][ids][variations]', [], [], [
                         'label' => 'Варіації',
                         'multiple' => 1,
                         'url_suggest' => route('admin.suggest.product-variations'),
                     ]) !!}
                    {!! Lte3::hidden('content[groups][$i][weight]', null, ['class' => 'js-input-weight']) !!}

                </div>
            </template>

            {{-- ALREADY SAVED MULTIPLE FIELDS --}}
            @if (isset($block) && ($items = $block->getContentSort('groups', [])))
                @foreach ($items as $item)
                    @php
                        $ids = $item['ids']['variations'] ?? [];
                        $variations = \App\Models\Shop\ProductVariation::whereIn('id', $ids)->with('translations')->get();
                    @endphp

                    <div class="f-item">
                        <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                    class="fa fa-trash"></i></a>
                        <i class="fa fa-arrows-alt-v cursor-move"></i>
                        {!! Lte3::text("content[groups][{$loop->index}][title]", $item['title'] ?? '', ['label' => 'Заголовок групи']) !!}
                        {!! Lte3::select2("content[groups][{$loop->index}][ids][variations]", $ids, $variations->pluck('name', 'id')->toArray(), [
                            'label' => 'Варіації',
                            'multiple' => 1,
                            'url_suggest' => route('admin.suggest.product-variations'),
                        ]) !!}
                        {!! Lte3::hidden("content[groups][{$loop->index}][weight]", $item['weight'] ?? 0, ['class' => 'js-input-weight']) !!}
                    </div>
                @endforeach
            @else
                <p class="js-msg-empty">Елементів не додано 😢</p>
            @endisset
        </div>
        <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
    </div>
</div>
