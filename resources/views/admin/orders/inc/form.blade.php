@isset($order)
    {!! Lte3::hidden('type', $order->type) !!}
@endisset

<div class="row">
    <div class="col-md-8">

        {{-- ПОЗИЦІЇ/ТОВАРИ ЗАМОВЛЕННЯ --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="far fa-futbol"></i>
                    Позиції: {{ isset($order) ? $order->purchases->count() : 0 }}
                </h3>
                <div class="card-tools">
                @if($order->isPerformed())
                        <a href="#" class="btn btn-xs btn-success disabled" data-toggle="tooltip"
                           title="Для підтверджених/виконанах замовлення операція не доступна!" disabled><i
                                    class="fas fa-plus-circle"></i> Додати</a>
                    @else
                        <a href="#" data-toggle="modal" data-target="#purchases-add" class="btn btn-xs btn-success"><i
                                    class="fas fa-plus-circle"></i> Додати</a>
                    @endif
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                @if(isset($order) && $order->purchases->count())
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 65px"></th>
                            <th style="width: 100px"></th>
                            <th style="width: 160px">SKU</th>
                            <th style="width: 30%">Назва</th>
                            <th class="ha-center">Ціна</th>
                            <th class="ha-center">Кількість</th>
                            <th class="ha-center">Знижка</th>
                            <th class="ha-center">Сума</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($totals = ['price' => 0, 'quantity' => 0, 'discount' => 0, 'sum' => 0])
                        @foreach($order->purchases as $purchase)
                            @php($totals['price'] += $purchase->price)
                            @php($totals['quantity'] += $purchase->quantity)
                            @php($totals['discount'] += $purchase->discount)
                            @php($totals['sum'] += $purchase->quantity * $purchase->price - $purchase->discount)
                            <tr class="va-center">
                                <td>
                                    <div class="btn-actions dropdown">
                                        @if(!$order->isPerformed())
                                            <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <div class="dropdown-menu" role="menu" style="top: 93%;">
                                                <a href="{{ route('admin.orders.purchases.delete', $purchase) }}"
                                                   class="dropdown-item js-click-submit" data-method="delete"
                                                   data-confirm="Видалити?">Видалити</a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($img = $purchase->getImageUrl())
                                        <a href="{{ $img }}" class="js-popup-image"><img src="{{$img}}" style="width: 50px;"></a>
                                    @endif
                                </td>
                                <td>
                                    @if($variation = $purchase->variation)
                                        <a href="{{ route('admin.products.edit', [$variation->product, 'variation' => $variation->id]) }}"
                                           target="_blank" title="{{ $purchase->id }}">
                                            {{ $purchase->getSku() }}
                                        </a>
                                    @else
                                        {{ $purchase->getSku() }}
                                    @endif
                                </td>
                                <td>
                                    {{ $purchase->getName() }}<br>
                                    <small>
                                        @foreach($purchase->variation?->properties ?? [] as $property)
                                            <strong>{{ $property->attribute?->name }}:</strong> {{ $property->value }};
                                        @endforeach
                                    </small>
                                </td>
                                <td class="ha-center">
                                    {!! Lte3::xEditable('price', $purchase->price, [
                                        'type' => 'text',
                                        'pk' => $purchase->id,
                                        'url_save' => route('admin.orders.purchases.editable', $purchase),
                                    ]) !!}
                                </td>
                                <td class="ha-center">
                                    {!! Lte3::xEditable('quantity', $purchase->quantity, [
                                        'type' => 'text',
                                        'pk' => $purchase->id,
                                        'url_save' => route('admin.orders.purchases.editable', $purchase),
                                    ]) !!}
                                </td>
                                <td class="ha-center">
                                    {!! Lte3::xEditable('discount', $purchase->discount, [
                                        'type' => 'text',
                                        'pk' => $purchase->id,
                                        'url_save' => route('admin.orders.purchases.editable', $purchase),
                                    ]) !!}
                                </td>
                                <td class="ha-center">{{ $purchase->getTotalSum() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot hidden>
                        <tr style="font-weight: bold">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="ha-center">{{ $totals['quantity'] }}</td>
                            <td class="ha-center">{{ $totals['discount'] }}</td>
                            <td class="ha-center">{{ round($totals['sum'], 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="callout callout-warning m-3">
                        <h5>Позиції відстутні!</h5>
                        <p>Додайте товари та офрміть замовлення!</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ДАНІ ДОСТАВКИ (якщо відправлення не ВКЛ.) --}}
        {{-- DEPRECATED! --}}
        @if(false)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-map-marked-alt"></i> Дані доставки</h3>
                </div>
                <div class="card-body">
                    {{ isset($order) ? $order->ordersendings()->latest()->first()?->getRecipientStr() : '' }}
                </div>
            </div>
        @endif

        {{-- ПЛАТЕЖІ --}}
        @if(true)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="far fa-credit-card"></i>
                        Платежі: {{ isset($order) ? $order->payments->where('operation', \App\Models\Payment::OPERATION_INCOME)->count() : 0 }}
                    </h3>
                    @isset($order)
                        <div class="card-tools">

                            <div class="btn-group">
                                @if($order->isOrdered())
                                    <button type="button" class="btn btn-success btn-xs dropdown-toggle"
                                            data-toggle="dropdown"><i class="fas fa-plus-circle"></i>
                                        Додати
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        @foreach(\App\Models\Payment::gatewaysList('*', 'key', ['added' => [\App\Models\Payment::GATEWAY_REQUISITE, \App\Models\Payment::GATEWAY_CASH,]]) as $key => $val)
                                            <a href="{{ route("admin.orders.payments.create", [$order->id, 'gateway' => $key]) }}"
                                               data-target="#modal-xl"
                                               class="dropdown-item js-modal-fill-html"
                                               data-fn-inits="initSelect2,initCheckbox"
                                               style="text-transform: capitalize">{{Arr::get($val, 'name')}}</a>
                                        @endforeach
                                    </div>
                                @else
                                    <button type="button" disabled class="btn btn-success btn-xs disabled"
                                            title="Для додавання платежів оформіть замовлення" data-toggle="tooltip"><i
                                                class="fas fa-plus-circle"></i>
                                        Додати
                                    </button>
                                @endif
                            </div>

                        </div>
                    @endisset
                </div>
                <div class="card-body table-responsive p-0">
                    @if(isset($order) && $order->payments->where('operation', \App\Models\Payment::OPERATION_INCOME)->count())
                        @include('admin.payments.table', ['payments' => $order->payments->where('operation', \App\Models\Payment::OPERATION_INCOME)])
                    @else
                        <div class="callout callout-warning m-3">
                            <h5>Платежі відсутні!</h5>
                            <p>Для додавання платежів замовлення має бути оформлено!</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ВІДПРАВЛЕННЯ --}}
        @if(false)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-shipping-fast"></i>
                        Відправлення</h3>
                    @isset($order)
                        <div class="card-tools">

                            <div class="btn-group">
                                @if(isset($order) && $order->isOrdered())
                                    <button type="button" class="btn btn-success btn-xs dropdown-toggle"
                                            data-toggle="dropdown"><i class="fas fa-plus-circle"></i> Додати
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        @foreach(\App\Models\Shop\Ordersending::servicesList('*', 'key') as $key => $val)
                                            @if(Arr::get($val, 'ordersendings'))
                                                <a href="{{ route("admin.ordersendings.create", ['service' => $key, 'order_id' => $order->id]) }}"
                                                   class="dropdown-item"
                                                   style="text-transform: capitalize">{{Arr::get($val, 'name')}}</a>
                                            @endif
                                        @endforeach

                                        @if(\Domain::getOpt('shippings.trackers', []))
                                            <div class="dropdown-divider"></div>
                                            @foreach(\App\Models\Shop\Ordersending::servicesList('*', 'key', ['only' => \Domain::getOpt('shippings.trackers', [])]) as $key => $val)
                                                <a href="{{ route("admin.ordersendings-ttn.create", ['order_id' => $order->id, 'service' => $key]) }}"
                                                   data-target="#modal-lg"
                                                   class="dropdown-item js-modal-fill-html"
                                                   data-fn-inits="initSelect2,initCheckbox"
                                                   style="text-transform: capitalize">{{Arr::get($val, 'name')}}</a>
                                            @endforeach
                                        @endif
                                    </div>
                                @else
                                    <button type="button" disabled class="btn btn-success btn-xs disabled" title="Для довавання відправлень оформіть замовлення">
                                        <i class="fas fa-plus-circle"></i>Додати
                                    </button>
                                @endif
                            </div>

                        </div>
                    @endisset
                </div>
                <div class="card-body table-responsive p-0">
                    @if(isset($order) && $order->ordersendings->count())
                        @include('admin.ordersendings.table', ['ordersendings' => $order->ordersendings])
                    @else
                        <div class="callout callout-warning m-3">
                            <h5>Відправлення відcутні!</h5>
                            <p>Для додавання відправлень замовлення має бути оформлено!</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ВИТРАТИ --}}
        @if(false)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-sign-out-alt"></i>
                        Витрати: {{ isset($order) ? $order->payments->where('operation', \App\Models\Payment::OPERATION_EXPENSE)->count() : 0 }}
                    </h3>
                    @isset($order)
                        <div class="card-tools">
                            <div class="btn-group">
                                @if(in_array($order->perform, [\App\Models\Order::PERFORM_CONFIRMED, \App\Models\Order::PERFORM_DONE]))
                                    <button type="button" disabled class="btn btn-success btn-xs disabled"
                                            data-toggle="tooltip"
                                            title="Для підтверджених/виконанах замовлення операція не доступна!"><i
                                            class="fas fa-plus-circle"></i>
                                        Додати
                                    </button>
                                @else
                                    <a href="{{ route('admin.orders.payments.create-expense', $order->id) }}"
                                       data-target="#modal-lg"
                                       class="btn btn-success btn-xs js-modal-fill-html"
                                       data-fn-inits="initSelect2,initCheckbox,initDatetimepickerOptions"
                                       style="text-transform: capitalize"><i class="fas fa-plus-circle"></i> Додати</a>
                                @endif
                            </div>
                        </div>
                    @endisset
                </div>
                <div class="card-body table-responsive p-0">
                    @if(isset($order) && $order->payments->where('operation', \App\Models\Extern\Payment::OPERATION_EXPENSE)->count())
                        @include('admin.payments.table-outgoing', ['payments' => $order->payments->where('operation', \App\Models\Extern\Payment::OPERATION_EXPENSE)])
                    @else
                        <div class="callout callout-warning m-3">
                            <h5>Витрати відсутні!</h5>
                            <p>Для додавання витрат замовлення не повинно бути підтверджене чи виконане!</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">

        {{-- ВИКОНАННЯ --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="far fa-calendar-check"></i> Виконання</h3> &nbsp;
                <i class="fas fa-circle" style="color: orange"></i>
                <i class="fas fa-circle" style="color: green"></i>
                <i class="fas fa-circle" style="color: red"></i>
            </div>

            <div class="card-body">
                {!! Lte3::select2('perform', null, \App\Models\Order::performsList('name', 'key'), [
                    'label' => '',
                    'disableds' => \App\Models\Order::performsList('disableds', 'key')[(isset($order) ? $order->perform : null) ?: 'pending'],
                    'help' => '* Після вибору - натисніть внизу кнопку "Оновити замовлення"',
                    'map' => [
                            \App\Models\Order::PERFORM_PENDING => ['.block-perform-pending'],
                            \App\Models\Order::PERFORM_PENDING_RESERVED => ['.block-perform-pending_reserved'],
                            \App\Models\Order::PERFORM_CONFIRMED => ['.block-perform-confirmed'],
                            \App\Models\Order::PERFORM_DONE => ['.block-perform-done'],
                            \App\Models\Order::PERFORM_CANCELLED => ['.block-perform-cancelled'],
                        ],
                ]) !!}

                @foreach(\App\Models\Order::performsList('*', 'key') as $key => $val)
                    <div class="callout callout-{{ $val['callout'] }} block-perform-{{$key}}" style="display:none">
                        <p class=""><i class="fas fa-info-circle"></i> {{$val['desc']}}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ЗАГАЛЬНЕ --}}
        <div class="card {{--collapsed-card--}}">
            <div class="card-header" data-card-widget="collapse">
                <h3 class="card-title"><i class="fas fa-inbox"></i> Загальне</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        {!! Lte3::select2('source', null, \App\Models\Order::sourcesList(), [
                            'label' => 'Джерело',
                            'disabled' => isset($order) && $order->isPerformed() ? 1 : 0
                        ]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        {!! Lte3::select2('status', (isset($order) ? $order->status : null), \App\Models\Order::statusesList('name', 'key'), [
                            'label' => 'Статус замовлення',
                            'id' => 'order_status',
                        ]) !!}
                    </div>
                    {{--<div class="col">
                        {!! Lte3::select2('payment_status', isset($order) ? $order->payment_status : \App\Models\Shop\Order::PAYMENT_STATUS_NOT_PAID, \App\Models\Shop\Order::paymentStatusesList('name', 'key'), [
                            'label' => 'Статус оплати',
                        ]) !!}
                    </div>--}}
                    <div class="col">
                        {!! Lte3::number('discount_sum', isset($order) ? $order->discount_sum : 0, [
                            'label' => 'Знижка на замовлення',
                            'type' => 'number',
                            'step' => 0.01,
                        ]) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        {!! Lte3::number('delivery_sum', isset($order) ? $order->delivery_sum : 0, [
                            'label' => 'Вартість доставки',
                            'step' => 0.01,
                        ]) !!}
                    </div>
                    <div class="col">
                        {!! Lte3::number('delivery_discount_sum', isset($order) ? $order->delivery_discount_sum : 0, [
                            'label' => 'Знижка на доставку',
                            'step' => 0.01,
                        ]) !!}
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        {!! Lte3::textarea('manager_comment', isset($order) ? $order->manager_comment : '', [
                            'label' => 'Коментар менеджера',
                            'placeholder' => 'Коментар відсутній',
                            'rows' => 3,
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Lte3::textarea('client_comment', isset($order) ? ($order->client_comment ?: $order->getAdded('recipient.comment')) : '', [
                            'label' => 'Коментар від отримувача',
                            'placeholder' => 'Коментар відсутній',
                            'readonly' => 1,
                            'rows' => 3,
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- ОТРИМУВАЧ --}}
        <div class="card collapsed-card">
            <div class="card-header" data-card-widget="collapse">
                <h3 class="card-title"><i class="fas fa-id-badge"></i> Отримувач</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! Lte3::radiogroup('recipient[type]', (isset($order) ? $order->getAdded('recipient.type') : null) ?? 'i', ['i' => 'Поточний', 'other' => 'Інший отримувач',], ['label' => '']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Lte3::checkbox('recipient[callme]', (isset($order) ? $order->getAdded('recipient.callme') : null) ?? 0, [
                            'label' => 'Перетелефонувати', 'class_control' => 'custom-switch'
                        ]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! Lte3::text('recipient[lastname]', isset($order) ? ($order->getAdded('recipient.lastname') ?? $order->getAdded('user.lastname')) : null, [
                            'label' => 'Прізвище',
                        ]) !!}
                        {!! Lte3::text('recipient[middlename]', isset($order) ? ($order->getAdded('recipient.middlename') ?? $order->getAdded('user.middlename')) : null, [
                            'label' => 'По батькові',
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Lte3::text('recipient[name]', isset($order) ? ($order->getAdded('recipient.name') ?? $order->getAdded('user.name')) : null, [
                             'label' => 'Ім\'я',
                         ]) !!}
                        {!! Lte3::text('recipient[phone]', isset($order) ? ($order->getAdded('recipient.phone') ?? $order->getAdded('user.phone')) : null, [
                            'label' => 'Телефон',
                            'placeholder' => '380969999999'
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Lte3::text('recipient[email]', isset($order) ? ($order->getAdded('recipient.email') ?? $order->getAdded('user.email')) : null, [
                            'label' => 'Email',
                        ]) !!}
                    </div>
                </div>

            </div>
        </div>

        <!-- КОРИСТУВАЧ -->
        @if(true)
            <div class="card collapsed-card">
                <div class="card-header" data-card-widget="collapse">
                    <h3 class="card-title"><i class="fas fa-user"></i> Користувач</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            {!! Lte3::select2('user_id', isset($order) && $order->user ? [$order->user_id => $order->user->getTitleStr()] : null, null, [
                                'label' => '',
                                'url_suggest' => route('admin.suggest.users'),
                                'class' => 'js-user-select',
                            ]) !!}
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="js-user-btn-show">
                                @if(isset($order) && ($user = $order->user))
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="btn btn-outline-secondary  js-modal-fill-html" data-target='#modal-xl'>Перегляд</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @push('scripts')
                <script>
                    $('.js-user-select').on('select2:select', function (e) {
                        var id = e.params.data.id;
                        if (id) {
                            $('.js-user-btn-show').html(`<a href="/admin/users/${id}" class="btn btn-outline-secondary btn-sm js-modal-fill-html" data-target='#modal-xl'>Перегляд</a>`);
                        } else {
                            $('.js-user-btn-show').html('');
                        }
                    });
                </script>
            @endpush
        @endif

        {{-- ІСТОРІЯ --}}
        @if(auth()->user()->can('dev'))
            <div class="card collapsed-card" hidden>
                <div class="card-header" data-card-widget="collapse">
                    <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Історія</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-inverse">

                        <div class="time-label">
                        <span class="bg-danger">
                        10 Березня 2024
                        </span>
                        </div>

                        <div>
                            <i class="fas fa-envelope bg-primary"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i> 12:05</span>
                                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>
                            </div>
                        </div>


                        <div>
                            <i class="fas fa-user bg-info"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>
                                <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your friend
                                    request
                                </h3>
                            </div>
                        </div>

                        <div>
                            <i class="fas fa-comments bg-warning"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>
                                <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
                            </div>
                        </div>

                        <div class="time-label">
                        <span class="bg-success">
                            3 Березня 2024
                        </span>
                        </div>

                        <div>
                            <i class="fas fa-camera bg-purple"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i> 7 days ago</span>
                                <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>
                            </div>
                        </div>

                        <div>
                            <i class="far fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ВІЗИТ --}}
        @if(false)
            @if(isset($order) && ($visit = $order->visit))

                <div class="card collapsed-card">
                    <div class="card-header" data-card-widget="collapse">
                        <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> Візит</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Action:</th>
                                <td>{{ $visit->action }}</td>
                            </tr>
                            <tr>
                                <th style="width:50%">IP:</th>
                                <td>{{ $visit->ip }}</td>
                            </tr>
                            @if($visit->referer_host)
                                <tr>
                                    <th>Referer:</th>
                                    <td>{{ $visit->referer_host }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Device:</th>
                                <td>{{ $visit->device_type }} {{ $visit->device_family }} {{ $visit->device_model }}</td>
                            </tr>
                            <tr>
                                <th>OS:</th>
                                <td>{{ $visit->platform }}</td>
                            </tr>
                            <tr>
                                <th>Browser:</th>
                                <td>{{ $visit->browser }}</td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td>{{ $visit->country_code }} @if($visit->city)
                                        , {{ $visit->city }}
                                    @endif @if($visit->lat)
                                        <small>({{ $visit->lat }}, {{ $visit->lng }})</small>
                                    @endif</td>
                            </tr>
                            <tr>
                                <th>Locale:</th>
                                <td>{{ $visit->locale_code }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        @endif

        {{-- СУМАРНО --}}
        @isset($order)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-receipt"></i> Сумарно</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
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
                        @if($val = $order->getRemainedToPaidSum())
                            @if($val > 0)
                                <tr style="background: lightsalmon" title="Лишилося оплатити покупцю">
                                    <th>Борг:</th>
                                    <td>{{ $val }} {{ $order->currency_code }}</td>
                                </tr>
                            @else
                                <tr style="background: #3ce0af" title="Переплата покупця">
                                    <th>Переплата:</th>
                                    <td>{{ abs($val) }} {{ $order->currency_code }}</td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
            </div>
        @endisset

    </div>
</div>

<div class="card-footer text-right">
    {!! Lte3::btnReset('Вийти', ['url' => route('admin.orders.index')]) !!}

    @if(isset($order) && $order->type === \App\Models\Order::TYPE_CART)
        {!! Lte3::btnSubmit('Оформити замовлення', 'action', 'ordered', ['before_title' => '<i class="fa fa-check"></i>', 'title' => 'Оформити і створити номер замовлення']) !!}
        {{--{!! Lte3::btnSubmit('Зберегти як корзина', null, null, ['before_title' => '<i class="fas fa-shopping-cart"></i>', 'title' => 'Зберегти зміни як корзину для подальшого доповнення', 'class' => 'btn-success']) !!}--}}
        {{--        <button type="submit" class="btn btn-info" name="action" value="ordered"><i class="fa fa-check"></i> Оформити замовлення</button>--}}
    @elseif(isset($order))
        {!! Lte3::btnSubmit('Оновити замовлення', null, null, ['add' => 'fixed', 'before_title' => '<i class="fa fa-check"></i>']) !!}
    @else
        {!! Lte3::btnSubmit('Створити замовлення', null, null, ['add' => 'fixed', 'before_title' => '<i class="fa fa-check"></i>']) !!}
    @endif
</div>
