<div class="modal-header"><h4 class="modal-title">Клонувати варіацію</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
{!! Lte3::formOpen(['action' => route('admin.products.variations.store', [$product, 'cloningProductVariation' => $productVariation]), 'files' => true]) !!}
<div class="modal-body">
    @include('admin.products.inc.variation-form', ['product' => $product, 'productVariation' => $productVariation, 'cloningProductVariation' => $productVariation])
</div>

<div class="modal-footer justify-content-between">
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>
{!! Lte3::formClose() !!}
