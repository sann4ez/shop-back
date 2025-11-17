<div class="modal-header"><h4 class="modal-title">Замовлення {!! $order->type === \App\Models\Order::TYPE_CART  ? 'формується' : "#<strong>{$order->number}</strong>" . " <small>від {$order->getDatetime('ordered_at')}</small>" !!}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

<div class="modal-body">

    <div class="row">
        <div class="col">
            <h5>Позиції</h5>
            <table class="table table-hover table-bordered table-sm">
                <thead>
                <tr>
                    <th style="width: 160px">SKU</th>
                    <th style="width: 30%">Назва</th>
                    <th class="ha-center">Ціна</th>
                    <th class="ha-center">Кількість</th>
                    <th class="ha-center">Знижка</th>
                    <th class="ha-center">Сума</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->purchases as $purchase)
                    <tr class="va-center">
                    <td>
                        @if($variation = $purchase->variation)
                            <a href="{{ route('admin.products.edit', [$variation->product, 'variation' => $variation->id]) }}" target="_blank" title="{{ $purchase->id }}">
                                {{ $purchase->getSku() }}
                            </a>
                        @else
                            {{ $purchase->getSku() }}
                        @endif
                    </td>
                    <td>
                        {{ $purchase->getName() }}<br>
                        <small>
                            @foreach($purchase->variation->properties as $property)
                                <strong>{{ $property->attribute?->name }}:</strong> {{ $property->value }};
                            @endforeach
                        </small>
                    </td>
                    <td class="ha-center">
                        {{ $purchase->price }}
                    </td>
                    <td class="ha-center">
                        {{ $purchase->quantity }}
                    </td>
                    <td class="ha-center">
                        {{ $purchase->discount }}
                    </td>
                    <td class="ha-center">{{ $purchase->price * $purchase->quantity - $purchase->discount }} {{ $purchase->currency_code }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col" hidden> {{-- TODO --}}
            <h5>Дані доставки</h5>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tbody>
                    {{--
                    <tr style="width:50%">
                        <th>Спосіб</th>
                        <td>{{ $order->getShippingMethod() }}</td>
                    </tr>
                    --}}
                    <tr>
                        <th>Відділення / Склад / Поштомат</th>
                        <td>{{ $order->getAdded('shipping.warehouse') }}</td>
                    </tr>
                    <tr>
                        <th>Адреса</th>
                        <td>{{ $order->getShippingAddressStr() }}</td>
                    </tr>
                    @if($c = $order->getAdded('shipping.comment'))
                    <tr>
                        <th>Коментар</th>
                        <td>{{ $c }}</td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col">
            <h5>Отримувач</h5>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tbody>
                    <tr style="width:50%">
                        <th>Тип</th>
                        <td>{{ $order->getAdded('recipient.type') === 'i' ? 'Поточний' : 'Інший отримувач' }}</td>
                    </tr>
                    <tr>
                        <th>ПІБ</th>
                        <td>{{  $order->getAdded('recipient.lastname') }} {{  $order->getAdded('recipient.name') }} {{  $order->getAdded('recipient.middlename') }}</td>
                    </tr>
                    <tr>
                        <th>Телефон</th>
                        <td>{{  $order->getAdded('recipient.phone') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <h5>Платежі</h5>
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>Спосіб оплати</th>
                    <th>Сума</th>
                    <th>Статус</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->payments as $purchase)
                    <tr>
                        <td>{{ $purchase->getGateway() }}</td>
                        <td>{{ $purchase->amount }} {{ $purchase->currency_code }}</td>
                        <td>{{ $purchase->getStatus() }}</td>
                        <td>{{ $purchase->getDatetime('paid_at') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <h5>Загальне</h5>
            <table class="table table-hover table-bordered">
                <tr>
                    <th style="width:50%">Статус замовлення:</th>
                    <td>{{ $order->getStatus() }}</td>
                </tr>
                <tr>
                    <th>Статус оплати:</th>
                    <td>{{ $order->getPaymentStatus() }}</td>
                </tr>
                <tr>
                    <th>Знижка на замовлення:</th>
                    <td>{{ $order->discount_sum }} {{ $order->currency_code }}</td>
                </tr>
                <tr>
                    <th>Доставка:</th>
                    <td>{{ $order->delivery_sum }} {{ $order->currency_code }}</td>
                </tr>
                <tr>
                    <th>Знижка на доставку:</th>
                    <td>{{ $order->delivery_discount_sum }} {{ $order->currency_code }}</td>
                </tr>
            </table>
        </div>
        <div class="col">
            <h5>Сумарно</h5>
            <table class="table table-hover table-bordered">
                <tr>
                    <th style="width:50%">Товари:</th>
                    <td>{{ $order->allPurchasesSum() }} {{ $order->currency_code }}</td>
                </tr>
                <tr>
                    <th>Доставка:</th>
                    <td>{{ $order->deliverySum() }} {{ $order->currency_code }}</td>
                </tr>
                <tr>
                    <th>Знижки:</th>
                    <td>{{ $order->discount_sum + $order->purchasesDiscountSum() }} {{ $order->currency_code }}</td>
                </tr>
                <tr>
                    <th>Всього:</th>
                    <td>{{ $order->totalSum() }} {{ $order->currency_code }}</td>
                </tr>
            </table>
        </div>
    </div>

</div>
