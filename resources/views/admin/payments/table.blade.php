<table class="table ">
    <thead>
    <tr>
        <th style="width: 65px"></th>
        <th>Платіжна</th>
        <th>Статус</th>
        <th>Сума</th>
        <th>Коментар</th>
    </tr>
    </thead>
    <tbody>
    @foreach($payments->sortBy('created_at') as $payment)
        <tr>
            <td title="{{ $payment->id }}">
                <div class="btn-actions dropdown">
                    <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i></button>

                    <div class="dropdown-menu" role="menu" style="top: 93%;">
                        @if($payment->canStatusChanged() && $payment->number)
                        <a href="#" class="dropdown-item js-clipboard" data-text="{{ route('payment.redirect', $payment) }}">Копіювати посилання</a>
                        @endif
                        @if(method_exists($payment->model, 'isPerformed') && !$payment->model?->isPerformed() && $payment->canStatusChanged())
                            <a href="{{ route('admin.payments.relink', $payment) }}"
                               class="js-click-submit dropdown-item"
                               data-method="post" type="submit"
                            >Перегенерувати посилання</a>
                        @endif
                        @if($payment->canDeleted())
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.orders.payments.destroy', $payment) }}"
                           class="dropdown-item js-click-submit" data-method="delete"
                           data-confirm="Видалити?">Видалити</a>
                        @else
                            <a href="#" class="dropdown-item disabled">Видалити</a>
                        @endif
                    </div>
                </div>
            </td>
            <td style="width: 220px">
                {{ $payment->getGateway() }}
                @if($payment->is_guarantee) <i class="fas fa-check-double" data-toggle="tooltip" title="Гарантійний платіж" style="color: green"></i> @endif
                @if($payment->canStatusChanged() && $payment->number)
                    <a href="#" class="js-clipboard"
                       data-text="{{ route('payment.redirect', $payment) }}"
                    ><i class="fas fa-link"></i></a>
                @endif
                @if($payment->status === \App\Models\Payment::STATUS_PAID)
                    <br>
                    <small data-toggle="tooltip" title="Дата оплати"> {{ $payment->getDatetime('paid_at') }}</small>
                @endif
            </td>
            <td style="width: 160px">
                @if($payment->canStatusChanged())
                    {!! Lte3::select2('status', $payment->status, \App\Models\Payment::statusesList('name', 'key'), [
                        'label' => '',
                        'url_save' => route('admin.orders.payments.editable', [$payment, '_operation' => 'reload']),
                        'id' => 'status-' . $payment->id
                    ]) !!}
               @else
                    {{ $payment->getStatus() }}
               @endif
            </td>
            <td>{{ $payment->amount }} {{ $payment->currency_code }}</td>
            <td style="max-width: 200px;" class="small">
                {!! Lte3::xEditable('comment', $payment->comment, [
                    'type' => 'textarea',
                    'pk' => $payment->id,
                    'url_save' => route('admin.orders.payments.editable', $payment),
                ]) !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
