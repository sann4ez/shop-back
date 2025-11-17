<div class="modal-header"><h4 class="modal-title">Наділсати Email клієнту</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
{!! Lte3::formOpen(['action' => route('admin.orders.email.send', $order), 'method' => 'POST', 'files' => true]) !!}
<div class="modal-body">

    {!! Lte3::text('email', isset($order) ? $order->user?->email : null, [
        'label' => 'Email', 'required' => 1,
    ]) !!}

    {!! Lte3::text('subject', null, [
        'label' => 'Тема', 'required' => 1,
    ]) !!}

    {!! Lte3::textarea('body', null, [
        'label' => 'Контент', 'required' => 1,
        'rows' => 5,
        'class' => 'f-tinymce',
    ]) !!}

    <div class="callout callout-info">
        <h5><i class="fas fa-info"></i> Токени Замовлення:</h5>
        @foreach (\App\Models\Order::tokensList('name', 'key') as $key => $name)
            <code>{{ $name }}:</code> <a href="#" title="Копіювати" class="js-clipboard" data-text="{{ $key }}">{{ $key }}</a>
        @endforeach
    </div>

</div>

<div class="modal-footer justify-content-between">
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Відправити') !!}
</div>
{!! Lte3::formClose() !!}
