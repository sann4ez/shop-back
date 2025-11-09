<div class="modal-header"><h4 class="modal-title">Редагувати</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
{!! Lte3::formOpen(['action' => route('admin.products.variations.update', $productVariation), 'model' => $productVariation, 'method' => 'PATCH']) !!}
<div class="modal-body">
    @include('admin.products.inc.variation-form', ['product' => $product, 'productVariation' => $productVariation])
</div>

<div class="modal-footer justify-content-between">
    {{--<button type="button" class="btn btn-default" data-dismiss="modal">Закрити</button>--}}
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>
{!! Lte3::formClose() !!}
