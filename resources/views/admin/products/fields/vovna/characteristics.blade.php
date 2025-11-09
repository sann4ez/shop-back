{{--{!! Lte3::text('fields[characteristics][title]', null, ['label' => 'Заголовок']) !!}--}}

{{-- MULTYITEMS: --}}
<div class="card f-wrap f-multyblocks" data-fn-inits="initLfmBtn">
    <div class="card-body">
        <h5>Характеристики:</h5>
        <div class="f-items sortable-y" data-input-weight-class="js-input-weight">
            {{-- TEMPLATE MULTIPLE FIELDS --}}
            <template class="f-item-template">
                <div class="f-item">
                    <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i class="fa fa-trash"></i></a>
                    <i class="fa fa-arrows-alt-v cursor-move"></i>
                    <div class="row">
                        <div class="col-md">
                            {!! Lte3::text('fields[characteristics][$i][attribute]', null, ['label' => 'Атрибут', 'placeholder' => 'Струм']) !!}
                        </div>
                        <div class="col-md">
                            {!! Lte3::text('fields[characteristics][$i][property]', null, ['label' => 'Значення/Надпис', 'placeholder' => '10A']) !!}
                        </div>
                    </div>
                    {!! Lte3::hidden('fields[characteristics][$i][weight]', null, ['class' => 'js-input-weight']) !!}
                    <hr>
                </div>
            </template>

            {{-- ALREADY SAVED MULTIPLE FIELDS --}}
            @if (isset($fieldsModel) && ($items = $fieldsModel->getFieldsSort('characteristics', [])))
                @foreach ($items as $item)
                    <div class="f-item">
                        <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i class="fa fa-trash"></i></a>
                        <i class="fa fa-arrows-alt-v cursor-move"></i>
                        <div class="row">
                            <div class="col-md-6">
                                {!! Lte3::text("fields[characteristics][{$loop->index}][attribute]", $item['attribute'] ?? '', ['label' => 'Атрибут']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Lte3::text("fields[characteristics][{$loop->index}][property]", $item['property'] ?? '', ['label' => 'Значення/Надпис']) !!}
                            </div>
                        </div>

                        {!! Lte3::hidden("fields[characteristics][{$loop->index}][weight]", $item['weight'] ?? 0, ['class' => 'js-input-weight']) !!}
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
