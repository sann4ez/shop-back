{{-- TODO: Deprecated --}}

<div class="modal-header">
    <h3 class="modal-title">Змінити статус</h3>
</div>
{!! Lte3::formOpen(['action' => route('admin.orders.status.send', $order), 'method' => 'POST', 'files' => true]) !!}
    <div class="modal-body">

        {!! Lte3::select2('status', $order->status, \App\Models\Shop\Order::statusesList('name', 'key', ['only' => \Domain::getOpt('orders.statuses', [])]), [
            'label' => 'Статус',
            'empty_value' => '--',
            'map' => \App\Models\Shop\Order::statusesListSelectMap(),
        ]) !!}
        {{--
        {!! Lte3::textarea('comment', null, [
            'label' => 'Коментар',
            'rows' => 2,
        ]) !!}
        --}}
        {{--
            <div class="callout callout-info">
                @foreach(\App\Models\Shop\Order::statusesList('name', 'key') as $key => $name)
                <div class="js-block-{{$key}}" style="display: none;">
                    <div class="row">
                        <div class="col-md-3">
                            {!! Lte3::checkbox("{$key}[email][send]", \Variable::getArray("notifications.order.{$key}.email.send", false, $order->getDomainGroup()), [
                                    'label' => 'Відправити лист',
                                    'class_control' => 'custom-switch',
                            ]) !!}
                        </div>
                        <div class="col-md-9">
                            {!! Lte3::checkbox("{$key}[email][force_send]", 0, [
                                    'label' => 'Відправляти, навіть якщо статус не змінено',
                                    'class_control' => 'custom-switch',
                            ]) !!}
                        </div>
                    </div>
                    {!! Lte3::text("{$key}[email][email]", isset($order) ? $order->user?->email : null, [
                        'label' => 'Email',
                        'required',
                    ]) !!}
                    {!! Lte3::text("{$key}[email][subject]", \Variable::getArray("notifications.order.{$key}.email.subject", null, $order->getDomainGroup()), [
                        'label' => 'Тема',
                        'required',
                    ]) !!}
                    {!! Lte3::textarea("{$key}[email][body]", \Variable::getArray("notifications.order.{$key}.email.body", null, $order->getDomainGroup()), [
                        'label' => 'Контент',
                        'class' => 'f-tinymce',
                    ]) !!}
                </div>
                @endforeach
            </div>
            <div class="box-footer">
                @foreach(\App\Models\Shop\Order::tokensList('name', 'key') as $key => $name)
                    <code>{{$name}}:</code> <a href="#" title="Copy" class="js-clipboard" data-text="{{ $key }}">{{ $key }}</a>
                @endforeach
            </div>
        --}}
        </div>
    </div>
<div class="modal-footer justify-content-between">
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>
{!! Lte3::formClose() !!}
