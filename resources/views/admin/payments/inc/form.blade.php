{!! Lte3::hidden('gateway', $gateway['key']) !!}

<div class="row">
    <div class="col-md">
        {!! Lte3::number('amount', isset($payment) ? $payment->amount : $order->totalSum(), [
            'label' => 'Сума',
        ]) !!}
    </div>
    <div class="col-md">
        @php
            $status = match (true) {
                isset($payment) => $payment->status,
                $gateway['key'] === \App\Models\Payment::GATEWAY_CASH => \App\Models\Payment::STATUS_PAID,
                default => null,
            }
        @endphp
        {!! Lte3::select2('status', $status, \App\Models\Payment::statusesList('name', 'key'), [
            'label' => 'Статус',
        ]) !!}
    </div>
</div>

{{--{!! Lte3::textarea('comment', null, ['label' => 'Коментар']) !!}--}}

<div class="row">
    <div class="col-md-6">
        {!! Lte3::checkbox('is_guarantee', null, [
            'label' => "Гарантнійний платіж",
            'class_control' => 'custom-switch',
            'help' => '* не потрібно повертати кошти при скасуванні замовлення',
        ]) !!}
    </div>
    @if(\Illuminate\Support\Arr::get($gateway, 'online'))
    <div class="col-md-6">
        {!! Lte3::checkbox('_make_payment_url', 1, [
            'label' => "Генерувати посилання",
            'class_control' => 'custom-switch',
            'help' => '* для онлайн платежів',
        ]) !!}
    </div>
    @endif
</div>

{!! Lte3::textarea('comment', null, [
    'label' => 'Коментар',
    'placeholder' => 'Коментар відсутній',
    'rows' => 3,
]) !!}
