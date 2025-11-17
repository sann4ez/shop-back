<div class="modal-header"><h4 class="modal-title">Дані чеку <strong>{{ $receipt->fiscal_code }}</strong></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

<div class="modal-body">
    <table class="table table-hover">
        <tbody>
        <tr>
            <th>Тип</th>
            <td>{{ $receipt->getType() }}</td>
        </tr>
        <tr>
            <th>Статус</th>
            <td>{{ $receipt->getStatus() }}</td>
        </tr>
        <tr>
            <th style="width:50%">Створено</th>
            <td>{{ $receipt->getDatetime('created_at') }}</td>
        </tr>
        <tr>
            <th>Фіскалізовано</th>
            <td>{{ $receipt->getDatetime('fiscal_at') }}</td>
        </tr>
        <tr>
            <th>Фіскальний номер</th>
            <td>
                @if($receipt->tax_url)
                    <a href="{{ $receipt->tax_url }}" target="_blank">{{ $receipt->fiscal_code }}</a>
                @else
                    {{ $receipt->fiscal_code }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Завантажити</th>
            <td><a href="{{ route('admin.paymentreceipts.visual', [$receipt, 'png']) }}" target="_blank"><i class="fas fa-paperclip"></i> PNG</a></td>
        </tr>
        </tbody>
    </table>
</div>

<div class="modal-footer justify-content-between">
    @if($receipt->payment->canReceiptReturn() && $receipt->status === \App\Models\Extern\Paymentreceipt::STATUS_DONE && $receipt->type === \App\Models\Extern\Paymentreceipt::TYPE_SELL)
        <button href="{{ route('admin.paymentreceipts.return', ['payment' => $receipt->payment, 'related_receipt_id' => $receipt->id]) }}"
            class="btn btn-primary btn-warning js-click-submit"
            data-method="post" data-confirm="Дійсно відмінити даний чек та сформувати чек Поверення?"
            data-toggle="tooltip" title="Сформувати чек відміни для даного чеку"
        > Відмінити чек </button>
    @endif
    <div></div>
    {!! Lte3::btnModalClose('Закрити') !!}
</div>

