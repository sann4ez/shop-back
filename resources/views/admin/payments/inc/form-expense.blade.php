<div class="row">
    <div class="col-md-6">
        {!! Lte3::number('amount', abs(isset($payment) ? $payment->amount : 0), [
            'label' => 'Сума',
            'class' => 'js-input-calc',
            'step' => 0.01,
            'required' => true,
        ]) !!}
    </div>
    <div class="col-md-6">
        {!! Lte3::datetimepicker('paid_at', null, [
            'label' => 'Дата операції',
            'format' => 'Y-m-d H:i:s',
        ]) !!}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        {!! Lte3::select2('source', null, \App\Models\Extern\Payment::sourcesList('name', 'key'), ['label' => 'Джерело', 'empty_value' => '--']) !!}
    </div>
    <div class="col-md-6">
        {!! Lte3::select2('category', null, \App\Models\Extern\Payment::categoriesList('name', 'key'), ['label' => 'Категорія', 'empty_value' => '--']) !!}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        {!! Lte3::select2('status', isset($payment) ? $payment->status : \App\Models\Extern\Payment::STATUS_PAID, \App\Models\Extern\Payment::statusesList('name', 'key'), ['label' => 'Статус']) !!}
    </div>
    <div class="col-md-6">
        {!! Lte3::select2('gateway', isset($payment) ? $payment->gateway : \App\Models\Extern\Payment::GATEWAY_CASH, \App\Models\Extern\Payment::gatewaysList('name', 'key', ['only' => [\App\Models\Extern\Payment::GATEWAY_CASH, \App\Models\Extern\Payment::GATEWAY_REQUISITE]]), ['label' => 'Шлюз']) !!}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        {!! Lte3::select2('user_id', isset($payment) && $payment->user ? [$payment->user_id => $payment->user?->getTitleStr()] : null, null, [
            'label' => 'Платник',
            'url_suggest' => route('admin.suggest.users'),
            'class' => 'js-user-select',
        ]) !!}
    </div>
</div>

{!! Lte3::textarea('comment', null, [
    'label' => 'Коментар',
    'placeholder' => 'Коментар відсутній',
    'rows' => 3,
]) !!}

{!! Lte3::hidden('operation', \App\Models\Extern\Payment::OPERATION_EXPENSE) !!}
