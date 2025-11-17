<div class="modal-header"><h4 class="modal-title">Редагувати Платіж <strong>{{ $gateway['name'] }}</strong></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

{!! Lte3::formOpen(['action' => route('admin.orders.payments.update', $payment), 'method' => 'patch', 'model' => $payment]) !!}
<div class="modal-body">
    @include('admin2.shop.payments.inc.form')
</div>

<div class="modal-footer justify-content-between">
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>
{!! Lte3::formClose() !!}
