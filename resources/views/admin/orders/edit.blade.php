@extends('admin.layouts.app')

@section('btn-content-header')
    @if($order->number)
    <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="btn btn-flat btn-default mb-1" title="Друкувати накладну" data-toggle="tooltip"><i class="fa fa-print"></i></a>
    <a href="{{ route('admin.orders.print', [$order, '_format' => 'pdf']) }}" target="_blank" class="btn btn-flat btn-default mb-1" title="Скачати накладну" data-toggle="tooltip"><i class="far fa-file-pdf"></i></a>
    @endif
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => "Замовлення " . ($order->type === \App\Models\Order::TYPE_CART  ? "ще формується {$order->getSource()} <i class='fas fa-shopping-cart'></i>" : "#<strong class='js-clipboard'>{$order->number}</strong>" . " - {$order->getSource()} <small>від {$order->getDatetime('ordered_at')}</small>"),
        'url_back' => session('admin.orders.index'),
    ])

    <section class="content">
        {!! Lte3::formOpen(['action' => route('admin.orders.update', $order), 'model' => $order, 'method' => 'PATCH']) !!}
            @include('admin.orders.inc.form', ['order' => $order])
        {!! Lte3::formClose() !!}
    </section>
@endsection

@push('modals')
    {{-- Модалка додавання позиції в замовлення --}}
    <div class="modal fade" id="purchases-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Додавання позицій</h4>
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Lte3::formOpen(['action' => route('admin.orders.purchases.add', $order)]) !!}
                <div class="modal-body">

                    {!! Lte3::select2('product_variation_id', null, [], [
                         'label' => 'Найменування товару',
                         'url_suggest' => route('admin.suggest.product-variations'),
                         'class' => 'js-purchases-add',
                         'help' => '* CTRL для мультивибору',
                         'id' => 'wer'
                    ]) !!}

                    <table class="table js-t-table">
                        <tbody class="js-tr-variations">
                        <tr>
                            <th>SKU</th>
                            <th>Назва</th>
                            <th style="width: 150px">Ціна</th>
                            <th style="width: 150px">Кількість</th>
                            <th style="width: 150px">Знижка</th>
                            <th></th>
                        </tr>
                        {{-- Append JS --}}
                        </tbody>
                        <tfoot>
                        <tr style="font-weight: bold">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="t-qty-total">0</td>
                            <td class="t-discount-total">0</td>
                            <td class="t-sum-total">0</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    {!! Lte3::btnModalClose('Закрити') !!}
                    {!! Lte3::btnSubmit('Додати') !!}
                </div>
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </div>

    <!-- Модалка додавання знижки TODO -->
    <div class="modal fade" id="modal-discount">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    {{--<h4 class="modal-title">Default Modal</h4>--}}
                </div>
                {!! Lte3::formOpen(['action' => route('admin.orders.discounts.add', $order)]) !!}
                <div class="modal-body">
                    {{--
                    @include('lte::fields.field-select2-ajax-autocomplete', [
                        'label' => trans('lte::main.Promotion'),
                        'data_url' => route('admin.suggest.product-variations'),
                        'field_name' => 'promotion_id',
                        //'multiple' => 0,
                        //'selected' => isset($product) ? $product->products->pluck('name', 'id')->toArray() : [],
                    ])
                    --}}
                    {!! Lte3::text('promocode', null, [
                        'label' => 'Промокод'
                    ]) !!}
                </div>

                <div class="text-right">
                    {!! Lte3::btnReset('Вийти') !!}
                    {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
                </div>
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        $('.f-select2.js-purchases-add').on('select2:select', function (e) {
            var data = e.params.data;

            if($(`.js-tr-variations [data-id="${data.id}"]`).length) {
                toastr.warning(`Already exists: ${data.name}`)
                return true;
            }

            $('.js-tr-variations').append(`<tr data-id="${data.id}">
                <td>${data.sku}</td>
                <td>${data.name}</td>
                <td><input class="form-control t-price" type="number" min="0" name="variations[${data.id}][price]" value="${data.price}"></td>
                <td><input class="form-control t-qty" type="number" min="0" name="variations[${data.id}][qty]" value="1"></td>
                <td><input class="form-control t-discount" type="number" min="0" name="variations[${data.id}][discount]" value="0"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger btn-flat js-btn-delete">
                        <i class="fas fa-minus js-btn-delete"></i>
                    </button>
                </td>
            </tr>`)
        });

        /**
         * Видалити позицію.
         */
        $(document).on('click', '.js-tr-variations .js-btn-delete', function (e) {
            $(this).closest('tr').remove();
            calcTablePurchaseTotals();
        })

        /**
         * Порахувати суми в таблиці
         */
        var calcTablePurchaseTotals = function() {
            var $table = $('.js-t-table'),
                totalQuantity = 0,
                totalDiscount = 0,
                totalPrice = 0;

            $table.find('tr').each(function() {
                var quantity = parseInt($(this).find('.t-qty').val()),
                    discount = parseFloat($(this).find('.t-discount').val())
                    price = parseFloat($(this).find('.t-price').val());
                if (quantity >=0 && price >=0) {
                    totalQuantity += quantity;
                    totalPrice += quantity * price;
                    totalDiscount += discount;
                }
            });

            $table.find('.t-qty-total').text(totalQuantity);
            $table.find('.t-discount-total').text(totalDiscount);
            $table.find('.t-sum-total').text(totalPrice - totalDiscount);
        }

        /**
         * Перерахувати суми в таблиці при введенні, вставленні, зміні
         */
        $(document).on('change keyup paste', '.js-t-table input', function () {
            calcTablePurchaseTotals();
        });
    </script>
@endpush
