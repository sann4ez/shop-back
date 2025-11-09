<div class="card">
    <div class="card-header">
        <h3 class="card-title">Варіації</h3>
        <div class="pull-right card-tools">
            <a href="#" data-url="{{ route('admin.products.variations.create', $product) }}" data-target="#modal-lg" class="btn btn-xs btn-success js-modal-fill-html js-btn-add-variant" data-fn-inits="initSelect2"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        @include('admin.products.inc.variations-table', ['product' => $product])
    </div>
</div>
