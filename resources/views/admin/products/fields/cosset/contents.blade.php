{!! Lte3::text('fields[protein]', null, ['label' => 'Основний протеїн', 'placeholder' => 'Яловичина']) !!}

{{-- MULTYITEMS: --}}
<div class="card f-wrap f-multyblocks" data-fn-inits="initLfmBtn">
    <div class="card-body">
        <h5>Цінності:</h5>
        <div class="f-items sortable-y" data-input-weight-class="js-input-weight">
            {{-- TEMPLATE MULTIPLE FIELDS --}}
            <template class="f-item-template">
                <div class="f-item">
                    <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i class="fa fa-trash"></i></a>
                    <i class="fa fa-arrows-alt-v cursor-move"></i>
                    <div class="row">
                        <div class="col-md">
                            {!! Lte3::text('fields[contents][$i][attr]', null, ['label' => 'Атрибут', 'placeholder' => 'Сирий протеїн']) !!}
                        </div>
                        <div class="col-md">
                            {!! Lte3::text('fields[contents][$i][prop]', null, ['label' => 'Значення', 'placeholder' => '0.54%']) !!}
                        </div>
                    </div>
                    {!! Lte3::hidden('fields[contents][$i][weight]', null, ['class' => 'js-input-weight']) !!}
                    <hr>
                </div>
            </template>

            {{-- ALREADY SAVED MULTIPLE FIELDS --}}
            @if (isset($fieldsModel) && ($items = $fieldsModel->getFieldsSort('contents', [])))
                @foreach ($items as $item)
                    <div class="f-item">
                        <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i class="fa fa-trash"></i></a>
                        <i class="fa fa-arrows-alt-v cursor-move"></i>
                        <div class="row">
                            <div class="col-md-6">
                                {!! Lte3::text("fields[contents][{$loop->index}][attr]", $item['attr'] ?? '', ['label' => 'Атрибут']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Lte3::text("fields[contents][{$loop->index}][prop]", $item['prop'] ?? '', ['label' => 'Значення']) !!}
                            </div>
                        </div>

                        {!! Lte3::hidden("fields[contents][{$loop->index}][weight]", $item['weight'] ?? 0, ['class' => 'js-input-weight']) !!}
                        <hr>
                    </div>
                @endforeach
            @else
                <p class="js-msg-empty">Елементів не додано 😢</p>
            @endisset
        </div>
        <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
    </div>
</div>
