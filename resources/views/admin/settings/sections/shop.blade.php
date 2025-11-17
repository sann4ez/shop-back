<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Магазин </h3>
    </div>

    <div class="card-body">

        {!! Lte3::formOpen(['action' => route('admin.settings.save'), null, 'method' => 'POST',]) !!}

        <input type="hidden" name="_destination" value="{{ Request::fullUrl() }}">
        @php($group = \Domain::getSelected('id'))
        <input type="hidden" name="group" value="{{ $group }}">


        <div class="row">
            @if(\Domain::getOpt('shop.roles_qty'))
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Корзина</h3>
                    </div>
                    <div class="card-body">
                        @foreach (\App\Models\Auth\User::rolesList('name', 'key') as $key => $name)
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Lte3::number("vars_array[shop][{$key}][cart][min_qty]", \Variable::getArray("shop.{$key}.cart.min_qty", 0, $group), [
                                        'label' => "{$name}: Мінімальна к-cть в корзині", 'step' => 1, 'min' => 0,
                                    ]) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Lte3::number("vars_array[shop][{$key}][cart][min_sum]", \Variable::getArray("shop.{$key}.cart.min_sum", 0, $group), [
                                        'label' => "Мінімальна сума в корзині", 'step' => 1, 'min' => 0,
                                    ]) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
{{--
            @if(\Domain::getOpt('shop.roles_discount'))
            <div class="col-md-6" >
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Фіксована знижка</h3>
                    </div>
                    <div class="card-body">
                        @foreach (\App\Models\Auth\User::rolesList('name', 'key') as $key => $name)
                            {!! Lte3::number("vars_array[shop][{$key}][discount][value]", \Variable::getArray("shop.{$key}.discount.value", 0, $group), [
                                'label' => "{$name}: Знижка на продукцію, %",
                                'checkbox' => ['name' => "vars_array[shop][{$key}][discount][allowed]", 'title' => 'Застосовувати', 'value' => \Variable::getArray("shop.{$key}.discount.allowed", 0, $group), ]
                            ]) !!}
                        @endforeach
                        <p class="help-block small">* Значення по замовчуванню встановлюється при реєстрації користувача. Можна індивідуально змінити</p>
                    </div>
                </div>
            </div>
            @endif
            --}}
        </div>


        <div class="text-right">
            {!! Lte3::btnReset('Вийти') !!}
            {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
        </div>
    </div>
</div>

{!! Lte3::formClose() !!}
