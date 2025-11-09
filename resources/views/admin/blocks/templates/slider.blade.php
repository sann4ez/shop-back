{!! Lte3::hidden('type', 'slider') !!}
{{--{!! Lte3::hidden('content', null) !!}--}}

{{-- MULTYITEMS: --}}
<div class="card f-wrap f-multyblocks" data-fn-inits="initLfmBtn">
    <div class="card-body">
        <div class="f-items sortable-y" data-input-weight-class="js-input-weight">

            {{-- TEMPLATE MULTIPLE FIELDS --}}
            <template class="f-item-template">
                <div class="f-item">
                    <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                            class="fa fa-trash"></i></a>
                    <i class="fa fa-arrows-alt-v cursor-move"></i>
                    {!! Lte3::text('content[items][$i][title]', null, [
                        'label' => 'Надпис',
                    ]) !!}
                    {!! Lte3::url('content[items][$i][url]', null, [
                        'label' => 'Посилання',
                    ]) !!}
                    {!! Lte3::lfmImage('content[items][$i][img]', null, [
                        'label' => 'Зображення',
                    ]) !!}
                    {!! Lte3::hidden('content[items][$i][weight]', null, [
                        'class' => 'js-input-weight',
                    ]) !!}

                </div>
            </template>

            {{-- ALREADY SAVED MULTIPLE FIELDS --}}
            @if (isset($block) && ($items = $block->getContentSort('items', [])))
                @foreach ($items as $item)
                    <div class="f-item">
                        <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i
                                class="fa fa-trash"></i></a>
                        <i class="fa fa-arrows-alt-v cursor-move"></i>
                        {!! Lte3::text("content[items][{$loop->index}][title]", $item['title'] ?? '', ['label' => 'Надпис',]) !!}
                        {!! Lte3::url("content[items][{$loop->index}][url]", $item['url'] ?? '', ['label' => 'Посилання',]) !!}
                        {!! Lte3::lfmImage("content[items][{$loop->index}][img]", $item['img'] ?? '',['label' => 'Зображення'] ) !!}
                        {!! Lte3::hidden("content[items][{$loop->index}][weight]", $item['weight'] ?? 0, ['class' => 'js-input-weight']) !!}
                    </div>
                @endforeach
            @else
                <p class="js-msg-empty">Елементів не додано 😢</p>
            @endisset
        </div>
        <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
    </div>
</div>
