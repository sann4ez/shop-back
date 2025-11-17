<div class="modal-header"><h4 class="modal-title">Додати операцію <strong>Витрату</strong></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
{!! Lte3::formOpen(['action' => route('admin.orders.payments.store', $order), 'model' => null, 'method' => 'POST']) !!}
<div class="modal-body">
    @include('admin2.shop.payments.inc.form-expense')
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрити</button>
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>
{!! Lte3::formClose() !!}
