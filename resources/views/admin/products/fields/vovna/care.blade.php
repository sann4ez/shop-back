{{--{!! Lte3::textarea('fields[care]', isset($model) ? $model->getFields('care') : null, ['label' => 'Догляд', 'class' => 'f-tinymce',]) !!}--}}

{{-- MULTYITEMS: --}}
<div class="card f-wrap f-multyblocks" data-fn-inits="initLfmBtn">
    <div class="card-body">
        <h5>Догляд:</h5>
        <div class="f-items sortable-y" data-input-weight-class="js-input-weight">
            {{-- TEMPLATE MULTIPLE FIELDS --}}
            <template class="f-item-template">
                <div class="f-item">
                    <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i class="fa fa-trash"></i></a>
                    <i class="fa fa-arrows-alt-v cursor-move"></i>
                    <div class="row">
                        <div class="col-md-4">
                            {!! Lte3::lfmImage('fields[cares][$i][img]', null, ['label' => 'Іконка']) !!}
                        </div>
                        <div class="col-md-8">
                            {!! Lte3::text('fields[cares][$i][text]', null, ['label' => 'Опис', 'placeholder' => '']) !!}
                        </div>
                    </div>
                    {!! Lte3::hidden('fields[characteristics][$i][weight]', null, ['class' => 'js-input-weight']) !!}
                    <hr>
                </div>
            </template>

            {{-- ALREADY SAVED MULTIPLE FIELDS --}}
            @if (isset($fieldsModel) && ($items = $fieldsModel->getFieldsSort('cares', [])))
                @foreach ($items as $item)
                    <div class="f-item">
                        <a href="#" class="btn btn-xs btn-danger float-right js-btn-delete"><i class="fa fa-trash"></i></a>
                        <i class="fa fa-arrows-alt-v cursor-move"></i>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Lte3::lfmImage("fields[cares][{$loop->index}][img]", $item['img'] ?? '', ['label' => 'Іконка'] ) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Lte3::text("fields[cares][{$loop->index}][text]", $item['text'] ?? '', ['label' => 'Опис']) !!}
                            </div>
                        </div>

                        {!! Lte3::hidden("fields[cares][{$loop->index}][weight]", $item['weight'] ?? 0, ['class' => 'js-input-weight']) !!}
                        <hr>
                    </div>
                @endforeach
            @else
                <p class="js-msg-empty">Догляд не додано 😢</p>
            @endisset
        </div>
        <a href="" class="btn btn-info btn-xs float-right js-btn-add"><i class="fa fa-plus"></i></a>
    </div>
</div>
