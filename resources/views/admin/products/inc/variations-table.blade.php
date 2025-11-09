<table class="table text-nowrap table-sm " style="margin-bottom: 0px">
    <thead>
    <tr {{--style="background-color: aliceblue;"--}}>
        {{--<th style="width: 1%"></th>--}}
        <th style="width: 65px"></th>
        <th style="width: 100px"></th>
        <th style="width: 140px">SKU</th>
        <th style="width: 680px">Назва</th>
        <th class="text-center">Залишок</th>
        <th class="text-center">Ціна</th>
        <th class="text-center">Основний</th>
        @if(in_array(\App\Models\ProductVariation::GROUPING_TYPE_DEFAULT, [\App\Models\ProductVariation::GROUPING_TYPE_SINGLE, \App\Models\ProductVariation::GROUPING_TYPE_ATTRIBUTE, \App\Models\ProductVariation::GROUPING_TYPE_MAIN]))
        <th class="text-center"></th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($product->variations->sortByDesc('created_at') as $variation)
            <tr {{--style="background-color: #ffffff"--}} class="va-center">
                <td>
                    <div class="btn-actions dropdown">
                        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                        <div class="dropdown-menu" role="menu" style="top: 93%;">
                            <a href="#" data-url="{{ route('admin.products.variations.edit', $variation) }}" data-target="#modal-xl" class="dropdown-item js-modal-fill-html" data-variation="{{ $variation->id }}" data-fn-inits="initTinyMce,initSelect2,initJsVerificationSlugField,initBarcode,initPopupImage,initSortableY">Редагувати</a>

                            @if(false)
                                <a href="#" data-url="{{ route('admin.products.variations.cloning', $variation) }}" data-target="#modal-xl" class="dropdown-item js-modal-fill-html" data-fn-inits="initTinyMce,initSelect2,initJsVerificationSlugField,initBarcode,initPopupImage,initSortableY">Клонувати</a>
                            @endif

                            <div class="dropdown-divider"></div>

                            <a href="{{ route('admin.products.variations.delete', $variation) }}" class="dropdown-item js-click-submit" data-method="DELETE" data-confirm="Видалити?">Видалити</a>
                            {{--<a href="#" data-url="#" class="dropdown-item js-barcode-modal-print">Друкувати штрихкод</a>--}}
                        </div>
                    </div>
                </td>
                <td>
                    @if($url = $variation->getImageUrl('images', 'thumb', '/vendor/lte3/img/no-image.png'))
                        <a href="{{ $variation->getImageUrl('images') ?: $url }}" class="js-popup-image"><img src="{{$url}}" style="height: 50px; max-width: 50px" {{--class="img-thumbnail"--}}></a>
                    @endif
                </td>
                <td>
                    @if(false)
                        <a href="{{ route('admin.products.variations.edit', $variation) }}" data-url="{{ route('admin.products.variations.edit', $variation) }}" data-target="#modal-xl" class="hover-edit js-modal-fill-html" data-fn-inits="initTinyMce,initSelect2,initJsVerificationSlugField,initBarcode,initPopupImage,initSortableY">{{ $variation->sku }}</a>
                    @else
                        {{ $variation->sku }}
                    @endif
                </td>
                <td style="white-space: normal">
                    {{ $variation->name }}
                    @if($variation->properties->count()) <br> @endif
                    <small>
                        @foreach($variation->properties as $property)
                            <strong>{{ $property->attribute?->name }}:</strong> {{ $property->value }};
                        @endforeach
                    </small>
                    @if($barcodes = $variation->getBarcodesAll())
                        <br><small class="text-secondary"><strong>Штрихкод:</strong> {{ $barcodes }}</small>
                    @endif
                </td>
                <td class="text-center">
                    <span class="@if($variation->stock_qty < 0) text-danger @endif">{{ $variation->stock_qty }}</span>
                </td>
                <td class="text-center">
                    {!! Lte3::xEditable('price', $variation->price, [
                        'type' => 'number',
                        'pk' => $variation->id,
                        'url_save' => route('admin.products.variations.editable', $variation),
                        'step' => 0.01,
                    ]) !!}
                    <br>
                    <s>
                        {!! Lte3::xEditable('price_old', $variation->price_old, [
                            'type' => 'number',
                            'pk' => $variation->id,
                            'url_save' => route('admin.products.variations.editable', $variation),
                            'step' => 0.01,
                            'required' => 1,
                        ]) !!}
                    </s>
                </td>
                <td class="text-center">
                    {!! Lte3::radiogroup("default[$variation->id]", $variation->is_default, [
                            $variation->id => ['label' => '', 'url' => route('admin.products.variations.default', $variation)],
                        ], ['label' => '', 'submit_method' => 'POST'])
                    !!}
                </td>
                @if(in_array(\App\Models\ProductVariation::GROUPING_TYPE_DEFAULT, [\App\Models\ProductVariation::GROUPING_TYPE_SINGLE, \App\Models\ProductVariation::GROUPING_TYPE_ATTRIBUTE, \App\Models\ProductVariation::GROUPING_TYPE_MAIN]))
                <td class="text-center" style="width: 20px">
                    @if($variation->status !== \App\Models\ProductVariation::STATUS_PUBLISHED)
                        <span class="badge badge-secondary ml-1" title="Приховано <br> Група {{ \Illuminate\Support\Str::substr($variation->grouped_id, -4) }} @if($variation->hasParities()) <br>Має парність @endif" data-toggle="tooltip" data-html="true"><i class="fas fa-eye-slash"></i></span>
                    @elseif($variation->is_attribute_groped)
                        <span class="badge badge-success ml-1" title="Основна варіація, відобразиться в каталозі! Автоматичне групування по атрибуту групування і наявності. <br>Група {{ \Illuminate\Support\Str::substr($variation->grouped_id, -4) }} @if($variation->hasParities()) <br>Має парність @endif" data-toggle="tooltip" data-html="true"><i class="fas fa-check-double"></i></span>
                    @else
                        <span class="badge badge-pill ml-1" title="Група {{ \Illuminate\Support\Str::substr($variation->grouped_id, -4) }} @if($variation->hasParities()) <br>Має парність @endif" data-toggle="tooltip" data-html="true"><i class="fas fa-check-double"></i></span>
                    @endif &nbsp;
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
