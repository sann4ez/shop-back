@extends('admin.layouts.app')

@section('btn-content-header')
{{--    <div class="btn-actions  dropleft d-inline-flex">--}}
{{--        <button type="button" class="btn btn-flat btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>--}}
{{--        <div class="dropdown-menu" role="menu" style="top: 93%;">--}}

{{--            <a href="#" data-url="{{ route('admin.products.variations.task', ['task' => 'reindex']) }}" data-method="POST" class="dropdown-item js-click-submit" title="Запустити реіндексацію варіацій (для покращення пошуку)">--}}{{--<i class="fas fa-retweet"></i>--}}{{-- Реіндексація варіацій</a>--}}
{{--        </div>--}}
{{--    </div>--}}

    <a href="#" class="btn btn-flat btn-success mb-1" data-toggle="modal" data-target="#selectCat"><i class="fa fa-plus"></i></a>
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => "Товари: {$variations->total()}",
        'btn_search' => true,
        'btn_filter' => false,
    ])

    <section class="content">

        @if($variations->total())
        <div class="card card-widget">
            <div class="card-body table-responsive p-0">

                <table class="table table-hover ">
                    <thead>
                    <tr>
                        <th style="width: 65px;"></th>
                        <th style="width: 100px"></th>
                        <th style="width: 160px">SKU</th>
                        <th style="width: 480px">Назва</th>
                        <th style="width: 250px">Статус</th>
                        <th class="ha-center">Залишок</th>
                        <th class="ha-center">Ціна</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($variations as $variation)
                        <tr class="va-center">
                            <td>
                                <div class="btn-actions dropdown">
                                    <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                    <div class="dropdown-menu" role="menu" style="top: 93%;">
                                    <a href="{{ route('admin.products.edit', $variation->product) }}" class="dropdown-item ">Редагувати</a>

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('admin.products.destroy', $variation->product) }}" class="dropdown-item js-click-submit"
                                       data-method="DELETE"
                                       data-confirm="Видалити товар та всі його варіації?">Видалити товар</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($url = $variation->getImageUrl('images', 'thumb', '/vendor/lte3/img/no-image.png'))
                                    <a href="{{ $variation->getImageUrl('images') ?: $url }}" class="img-thumbnail js-popup-image"><img src="{{$url}}" style="width: 50px;"></a>
                                @endif
                            </td>
                            <td>
{{--                                @if(\Domain::getOpt('products.has_variations'))--}}
{{--                                    <a href="{{ route('admin.products.variations.edit', $variation) }}" data-url="{{ route('admin.products.variations.edit', $variation) }}" data-target="#modal-lg" class="hover-edit js-modal-fill-html" data-fn-inits="initTinyMce,initSelect2,initJsVerificationSlugField,initBarcode,initPopupImage,initSortableY">{{ $variation->sku }}</a>--}}
{{--                                @else--}}
                            <a href="{{ route('admin.products.edit', $variation->product) }}" class="hover-edit">{{ $variation->sku }}</a>
{{--                                @endif--}}
                            </td>
                            <td>
                                {{ $variation->getName() }}
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

                            <td >
                                {{ $variation->product->getStatus() }}
                            </td>
                            <td class="ha-center">
                                @if($variation->stock_qty < 0)
                                    <span style="color: #f4516c"><span>{{ $variation->stock_qty }}</span> </span>
                                @else
                                    {{ $variation->stock_qty }}
                                @endif
                            </td>

                            <td class="ha-center">
                                {!! Lte3::xEditable('price', $variation->price, [
                                    'type' => 'number',
                                    'pk' => $variation->id,
                                    'url_save' => route('admin.products.variations.editable', $variation),
                                    'step' => 0.01,
                                ]) !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

            <div class="card-footer clearfix">
                {!! Lte3::pagination($variations ?? null) !!}
            </div>
        </div>
        @else
            @include('admin.parts.empty-rows')
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        $('.js-mass-check-all').on('change', function () {
            if(this.checked){
                $('.js-mass-check').prop('checked', true);
            } else {
                $('.js-mass-check').prop('checked', false);
            }
        })
    </script>
@endpush

@push('modals')
    <div class="modal fade" id="selectCat">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Виберіть потрібну категорію</h4>
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Lte3::formOpen(['action' => route('admin.products.create'), 'method' => 'GET']) !!}
                <div class="modal-body">

                    {!! Lte3::select2Tree('category_id', [
                        'label' => '',
                        'multiple' => 0,
                        'method_get' => 'GET',
                        'required' => 1,
                        'url_tree' => route('admin.suggest.terms', [
                             'vocabulary' => \App\Models\Term::VOCABULARY_PRODUCT_CATEGORIES,
                             'format' => 'treeselect'
                         ]),
                     ]) !!}

                </div>
                <div class="modal-footer justify-content-between">
                    {!! Lte3::btnReset('Вийти') !!}
                    {!! Lte3::btnSubmit('Створити') !!}
                </div>
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </div>
@endpush
