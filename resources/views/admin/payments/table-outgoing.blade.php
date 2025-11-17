<table class="table ">
    <thead>
    <tr>
        @can('dev')
        <th style="width: 65px"></th>
        @endcan
        <th>Платіжна</th>
        <th>Статус</th>
        <th>Сума</th>
        <th>Джерело</th>
        <th>Категорія</th>
        <th>Користувач</th>
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
                        <a href="{{ route('admin.orders.payments.destroy', $payment) }}"
                           class="dropdown-item js-click-submit" data-method="delete"
                           data-confirm="Видалити?">Видалити</a>
                    </div>
                </div>
            </td>
            <td style="width: 220px">
                {{ $payment->getGateway() }}
                @if($payment->is_guarantee) <i class="fas fa-check-double" data-toggle="tooltip" title="Гарантійний платіж" style="color: green"></i> @endif
                @if($payment->payment_url)
                    <a href="#" class="js-clipboard"
                       data-text="{{ $payment->payment_url }}"
                    ><i class="fas fa-link"></i></a>
                @endif
                @if($payment->status === \App\Models\Extern\Payment::STATUS_PAID)
                    <br>
                    <small data-toggle="tooltip" title="Дата оплати"> {{ $payment->getDatetime('paid_at') }}</small>
                @endif
            </td>
            <td style="width: 160px">
                @if($payment->canStatusChanged())
                    {!! Lte3::select2('status', $payment->status, \App\Models\Extern\Payment::statusesList('name', 'key'), [
                        'label' => '',
                        'url_save' => route('admin.orders.payments.editable', [$payment, '_operation' => 'reload']),
                        'id' => 'status-' . $payment->id
                    ]) !!}
                @else
                    {{ $payment->getStatus() }}
                @endif
            </td>
            <td>{{ $payment->amount }} {{ $payment->currency_code }}</td>
            <td>{{ $payment->getSource() }}</td>
            <td>{{ $payment->getCategory() }}</td>
            <td>
                @isset($payment->user)
                <a href="{{ route('admin.users.show', $payment->user) }}" data-target="#modal-lg" class="js-modal-fill-html">{{ $payment->user->fullname }}</a>
                @endisset
            </td>
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
