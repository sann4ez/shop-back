<div class="modal-header"><h4 class="modal-title">SEO <strong>{{ $productVariation->name }}</strong></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
{!! Lte3::formOpen(['action' => route('admin.products.variations.seo.save', $productVariation), 'method' => 'POST', 'files' => true, 'model' => $productVariation]) !!}

<div class="modal-body">
    {{--
    {!! Lte3::text('name', null, ['label' => 'Назва (варіації)']) !!}
    --}}
    @include('admin.parts.seo-fields', ['seoable' => $productVariation])
</div>

<div class="modal-footer justify-content-between">
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>

{!! Lte3::formClose() !!}
