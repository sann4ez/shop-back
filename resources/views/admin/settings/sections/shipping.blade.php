<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Доставка </h3>
    </div>

    {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'method' => 'POST']) !!}
    <div class="card-body">
        <input type="hidden" name="_destination" value="{{ Request::fullUrl() }}">

        {{--@php($varGroup = \Domain::getGroup())--}}
        @php($varGroup = \Domain::getId())
        @php(\Variable::setGroup($varGroup))
        <input type="hidden" name="group" value="{{ $varGroup }}">

        <div class="row">
            @foreach (\App\Models\Shop\Ordersending::servicesList('*', 'key') as $key => $shipping)
                <div class="col-md-6">
                    <div class="card card-info card-solid">
                        <div class="card-header with-border">
                            <h3 class="card-title">{{ $shipping['name'] }}</h3>
                        </div>
                        <div class="card-body">

                            {!! Lte3::checkbox("vars_array[shipping][$key][active]", \Variable::getArray("shipping.$key.active", 0), [
                                'label' => 'Активно',
                                'class_control' => 'custom-switch',
                            ]) !!}

                            {{--
                            {!! Lte3::checkbox("vars_array[shipping][$key][default]", \Variable::getArray("shipping.$key.default", 0), [
                                'label' => 'По замовчуванню',
                                'class_control' => 'custom-switch',
                            ],) !!}
--}}
                            @foreach (\Illuminate\Support\Arr::get($shipping, 'fields2', []) as $field)
                                @if (in_array($field, ['sandbox', 'api_tracking']))
                                    {!! Lte3::checkbox("vars_array[shipping][$key][$field]", \Variable::getArray("shipping.$key.$field", 0), [
                                        'label' => Str::studly($field),
                                        'class_control' => 'custom-switch',
                                    ]) !!}
                                @else
                                <div class="form-group">
                                    {!! Lte3::text("vars_array[shipping][$key][$field]", \Variable::getArray("shipping.$key.$field", ''), [
                                        'label' => Str::studly($field),
                                        //'class' => 'form-control-sm'
                                    ]) !!}
                                </div>
                                @endif
                            @endforeach
                            @if($key === 'ukrposhta')
                                {!! Lte3::select2("vars_array[shipping][$key][type]", \Variable::getArray("shipping.$key.type", 'INDIVIDUAL'), [
                                    'INDIVIDUAL' => 'Фізична особа',
                                    'COMPANY' => 'Юридична особа (компанія)',
                                    'PRIVATE_ENTREPRENEUR' => 'ФОП'
                                ], [
                                    'label' => 'Тип договору',
                                ]) !!}
                            @endif
                            <div class="small">
                                @if($links = $shipping['links'] ?? [])
                                    @foreach($links as $link)
                                        <p><strong>{{ $link['name'] }}:</strong> <a href="{{ $link['path'] }}">{{ $link['path'] }}</a></p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
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
    {!! Lte3::formClose() !!}
</div>
