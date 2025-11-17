@php
    $services = \App\Models\Service::query()->where('type', \App\Models\Service::TYPE_PAYMENT)->get();
    $gatewaysList = \App\Models\Extern\Payment::gatewaysList();
@endphp

<div class="card card-solid">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Платіжні системи </h3>
        <ul class="nav nav-pills ml-auto p-2">
            @foreach($gatewaysList as  $key => $payment)
                @if(in_array($payment['key'], \Domain::getOpt('payments.methods', [])))
                    <li class="nav-lead"><a class="nav-link @if($payment['key'] === \Arr::first($gatewaysList)['key']) active @endif" href="#tab_{{ $payment['key'] }}" data-toggle="tab">{{ $payment['name'] }}</a></li>
                @endif
            @endforeach
            @can('settings.system')
                <li class="nav-lead"><a class="nav-link" href="#tab_settings" data-toggle="tab">Налаштування</a></li>
            @endcan
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">
            @foreach (\App\Models\Extern\Payment::gatewaysList('*', 'key') as $key => $payment)
                <div class="tab-pane @if($payment['key'] === \Arr::first($gatewaysList)['key']) active @endif" id="tab_{{ $payment['key'] }}">
                    @if($payment['online'] ?? false)
                        <div class="text-right">
                            <a href="#" class="btn btn-flat btn-success mb-1" data-toggle="modal" data-target="#modal{{ $payment['key'] }}"><i class="fa fa-plus"></i></a>
                        </div>
                    @endif
                    <div class="row">
                        @if($payment['online'] ?? false)
                            @forelse($services->where('service', $payment['key']) as $service)
                                @php
                                    $idPrefix = "{$payment['key']}_{$loop->index}";
                                @endphp
                                <div class="col-md-6">
                                    {!! Lte3::formOpen(['action' => route('admin.services.update', $service), 'method' => 'PUT']) !!}
                                        <div class="card card-info card-solid">
                                            <div class="card-header with-border p-1">
                                            </div>

                                            <div class="card-body">
                                                {!! Lte3::checkbox('options[active]', $service->getOpt('active'), [
                                                    'field_id_prefix' => $idPrefix,
                                                    'label' => 'Активно',
                                                    'class_control' => 'custom-switch',
                                                ]) !!}
                                                @foreach (\Illuminate\Support\Arr::get($payment, 'fields', []) as $field)
                                                    {!! Lte3::field( [
                                                        'value' => $service->getOpt($field['name']),
                                                        'name' => "options[{$field['name']}]",
                                                        'field_id_prefix' => $idPrefix,
                                                    ]+ $field) !!}
                                                @endforeach
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-right">
                                                    <a href="{{ route('admin.services.destroy', $service) }}"
                                                       class="btn btn-outline-danger js-click-submit" data-method="delete"
                                                       data-confirm="Видалити?">Видалити</a>
                                                    {!! Lte3::btnSubmit('Зберегти', null, null) !!}
                                                </div>
                                            </div>
                                        </div>
                                    {!! Lte3::formClose() !!}
                                </div>
                            @empty
                                <div class="col-md-12">
                                    @include('admin2.parts.empty-rows')
                                </div>
                            @endforelse
                        @else
                            @php
                                $service = \App\Models\Service::firstOrCreate([
                                    'type' => \App\Models\Service::TYPE_PAYMENT,
                                    'service' => $payment['key'],
                                ], [
                                    'type' => \App\Models\Service::TYPE_PAYMENT,
                                    'service' => $payment['key'],
                                ]);
                            @endphp
                            <div class="col-md-6">
                                {!! Lte3::formOpen(['action' => route('admin.services.update', $service), 'method' => 'PUT']) !!}
                                    <div class="card card-info card-solid">
                                        <div class="card-header with-border p-1">
                                        </div>
                                        <div class="card-body">
                                            {!! Lte3::checkbox('options[active]', $service->getOpt('active'), [
                                                'id' => \Illuminate\Support\Str::orderedUuid(),
                                                'label' => 'Активно',
                                                'class_control' => 'custom-switch',
                                            ]) !!}
                                            @foreach (\Illuminate\Support\Arr::get($payment, 'fields', []) as $field)
                                                {!! Lte3::field( [
                                                    'value' => $service->getOpt($field['name']),
                                                    'name' => "options[{$field['name']}]",
                                                ] + $field) !!}
                                            @endforeach
                                        </div>

                                        <div class="card-footer">
                                            <div class="text-right">
                                                {!! Lte3::btnSubmit('Зберегти', null, null) !!}
                                            </div>
                                        </div>
                                    </div>
                                {!! Lte3::formClose() !!}
                            </div>
                        @endif

                        @if($links = $payment['links'] ?? [])
                            <div class="col-md-12">
                                <div class="callout callout-info">
                                    @foreach($links as $link)
                                    <p><strong>{{ $link['name'] }}:</strong> <a href="{{ $link['path'] }}" class="text-blue">{{ $link['path'] }}</a></p>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            @can('settings.system')
                <div class="tab-pane" id="tab_settings">
                    <div class="row">
                        <div class="col-md-12">
                            {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'method' => 'POST']) !!}
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Сторінки для повернення з платіжної <small>(frontend)</small></h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach (['progress', 'cancel'] as $key)
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        {!! Lte3::url("vars_array[payments][pages][$key]", \Variable::getArray("payments.pages.$key", null), [
                                                            'label' => Str::studly($key) . ':',
                                                            'placeholder' => \Domain::getSelected('url') . '/payment/' . $key
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-right">
                                            {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
                                        </div>
                                    </div>
                                </div>
                            {!! Lte3::formClose() !!}
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>

@push('modals')
    @foreach(\App\Models\Extern\Payment::gatewaysList() as  $key => $payment)
    <div class="modal fade" id="modal{{ $payment['key'] }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Додати мерчант для {{ $payment['name'] }}</h4>
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Lte3::formOpen(['action' => route('admin.services.store'), 'method' => 'POST']) !!}
                    <div class="modal-body">
                        {!! Lte3::hidden('type', \App\Models\Service::TYPE_PAYMENT) !!}
                        {!! Lte3::hidden('service', $payment['key']) !!}

                        {!! Lte3::checkbox('options[active]', null, [
                            'field_id_prefix' => $payment['key'],
                            'label' => 'Активно',
                            'class_control' => 'custom-switch',
                        ]) !!}
                        @foreach (\Illuminate\Support\Arr::get($payment, 'fields', []) as $field)
                            {!! Lte3::field([
                                'name' => "options[{$field['name']}]",
                            ] + $field) !!}
                        @endforeach
                    </div>
                    <div class="modal-footer text-right">
                        {!! Lte3::btnSubmit('Створити') !!}
                    </div>
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </div>
    @endforeach
@endpush
