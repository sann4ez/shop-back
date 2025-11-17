<div class="card card-solid">
    {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'method' => 'POST']) !!}

    <div class="card-header with-border">
        <h3 class="card-title">Платіжні системи </h3>
    </div>

    <div class="card-body">
        <input type="hidden" name="_destination" value="{{ Request::fullUrl() }}">

{{--
        {!! Lte3::text("vars_array[en|payments2][tt]", \Variable::getArray("payments2.tt", null, 'en'), [
            'label' => 'en',
        ]) !!}
        {!! Lte3::text("vars_array[uk|payments2][tt]", \Variable::getArray("payments2.tt", null, 'uk'), [
              'label' => 'uk',
          ]) !!}
        {!! Lte3::text("vars[en|payments3]", \Variable::get("payments3", null, 'en'), [
            'label' => 'Tt',
        ]) !!}
        {!! Lte3::text("vars[payments4]", \Variable::get("payments4", null), [
            'label' => 'Tt2',
        ]) !!}
--}}

        <div class="row">
            @foreach (\App\Models\Payment::gatewaysList('*', 'key') as $key => $payment)

                <div class="col-md-6">
                    <div class="card card-info card-solid">
                        <div class="card-header with-border">
                            <h3 class="card-title">{{ $payment['name'] }}</h3>
                        </div>
                        <div class="card-body">
                            {!! Lte3::checkbox("vars_array[payments][$key][active]", \Variable::getArray("payments.$key.active", 0), [
                                'label' => 'Активно',
                                'class_control' => 'custom-switch',
                            ],) !!}
                            {{--
                            {!! Lte3::checkbox("vars_array[payments][$key][default]", \Variable::getArray("payments.$key.default", 0), [
                                'label' => 'По замовчуванню',
                                'class_control' => 'custom-switch',
                            ],) !!}
                            --}}
                            @foreach (\Illuminate\Support\Arr::get($payment, 'fields2', []) as $field)
                                @if (in_array($field, ['sandbox', 'fiscal']))
                                    {!! Lte3::checkbox("vars_array[payments][$key][$field]", \Variable::getArray("payments.$key.$field", 0), [
                                        'label' => Str::studly($field),
                                        'class_control' => 'custom-switch',
                                    ],) !!}
                                @else
                                    {!! Lte3::text("vars_array[payments][$key][$field]", \Variable::getArray("payments.$key.$field", null), [
                                        'label' => Str::studly($field),
                                    ]) !!}
                                @endif
                            @endforeach

                            <div class="small">
                                @if($links = $payment['links'] ?? [])
                                    @foreach($links as $link)
                                    <p><strong>{{ $link['name'] }}:</strong> <a href="{{ $link['path'] }}">{{ $link['path'] }}</a></p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

            <div class="col-md-12">
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
                                        'placeholder' => 'http://ferromi.com/payment'
                                    ]) !!}
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="text-right">
            {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
        </div>
    </div>
    {!! Lte3::formClose() !!}
</div>
