@php
    $services = \App\Models\Service::query()->where('type', \App\Models\Service::TYPE_SHIPPING)->get();
    $servicesList = \App\Models\Shop\Ordersending::servicesList();
@endphp

<div class="card card-solid">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Доставка</h3>
        <ul class="nav nav-pills ml-auto p-2">
            @foreach($servicesList as  $key => $shipping)
                @if(in_array($shipping['key'], \Domain::getOpt('shippings.methods', [])))
                    <li class="nav-lead"><a class="nav-link @if($shipping['key'] === \Arr::first($servicesList)['key']) active @endif" href="#tab_{{ $shipping['key'] }}" data-toggle="tab">{{ $shipping['name'] }}</a></li>
                @endif
            @endforeach
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">
{{--            @dd(\App\Models\Shop\Ordersending::servicesList('*', 'key'))--}}
            @foreach (\App\Models\Shop\Ordersending::servicesList('*', 'key') as $key => $shipping)
{{--                @dump("tab_{$shipping['key']}")--}}
                <div class="tab-pane @if($shipping['key'] === \Arr::first($servicesList)['key']) active @endif" id="tab_{{ $shipping['key'] }}">
                    <div class="text-right">
                        <a href="#" class="btn btn-flat btn-success mb-1" data-toggle="modal" data-target="#modal{{ $shipping['key'] }}"><i class="fa fa-plus"></i></a>
                    </div>
                    <div class="row">
                        @forelse($services->where('service', $shipping['key']) as $service)
                            @php
                                $idPrefix = "{$shipping['key']}_{$loop->index}";
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
                                        @foreach (\Illuminate\Support\Arr::get($shipping, 'fields', []) as $field)
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

                        @if($links = $shipping['links'] ?? [])
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
        </div>
    </div>
</div>

@push('modals')
    @foreach(\App\Models\Shop\Ordersending::servicesList() as  $key => $shipping)
        <div class="modal fade" id="modal{{ $shipping['key'] }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Додати мерчант для {{ $shipping['name'] }}</h4>
                        <button type="button" class="close"
                                data-dismiss="modal"
                                aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {!! Lte3::formOpen(['action' => route('admin.services.store'), 'method' => 'POST']) !!}
                    <div class="modal-body">
                        {!! Lte3::hidden('type', \App\Models\Service::TYPE_SHIPPING) !!}
                        {!! Lte3::hidden('service', $shipping['key']) !!}

                        {!! Lte3::checkbox('options[active]', null, [
                            'field_id_prefix' => $shipping['key'],
                            'label' => 'Активно',
                            'class_control' => 'custom-switch',
                        ]) !!}
                        @foreach (\Illuminate\Support\Arr::get($shipping, 'fields', []) as $field)
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
