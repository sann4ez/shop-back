@extends('admin2.parts.filter-wrap2')

@section('body')
    <div class="row">
        <div class="col-md-3">
            {!! Lte3::text('number', request('number'), [
                'label' => 'Номер',
                'append' => ['<i class="fas fa-hashtag"></i>'],
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::select2('source', request('source'), \App\Models\Shop\Order::sourcesList('name', 'key'), [
                'label' => 'Джерело',
                'multiple' => true,
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::select2('perform', request('perform'), \App\Models\Shop\Order::performsList('name', 'key'), [
                'label' => 'Виконання',
                'multiple' => true,
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::select2('status', request('status'), \App\Models\Shop\Order::statusesList('name', 'key'), [
                'label' => 'Статус',
                'multiple' => true,
            ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            {!! Lte3::select2('status_ordersending', request('status_ordersending'), \App\Models\Shop\Ordersending::statusesList('name', 'key'), [
                'label' => 'Статус відправлення',
                'multiple' => true,
            ]) !!}
        </div>

        @if(\Domain::getOptIs('shippings.ordersendings'))
        <div class="col-md-3">
            {!! Lte3::text('ttn_number', request('ttn_number'), [
                'label' => 'ТТН Номер',
                'append' => ['<i class="fas fa-shipping-fast"></i>'],
            ]) !!}
        </div>
        @endif
        <div class="col-md-3">
            {!! Lte3::datepicker('ordered_at_from', request('ordered_at_from'), [
                'label' => 'Оформлено, від',
                'format' => 'Y-m-d',
                'default' => '',
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::datepicker('ordered_at_to', request('ordered_at_to'), [
                'label' => 'Оформлено, до',
                'format' => 'Y-m-d',
                'default' => '',
            ]) !!}
            {{--<a href="">Сьогодні</a> &nbsp; <a href="">Поточний місяць</a> &nbsp; <a href="">Поточний місяць</a>--}}
        </div>
        {{--
        <div class="col-md-3">
            {!! Lte3::datepicker('performed_at_from', request('performed_at_from'), [
                'label' => 'Підтверджено, від',
                'format' => 'Y-m-d',
                'default' => '',
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::datepicker('performed_at_to', request('performed_at_to'), [
                'label' => 'Підтверджено, до',
                'format' => 'Y-m-d',
                'default' => '',
            ]) !!}
        </div>
        --}}
    </div>

    <div class="row">
        <div class="col-md-3">
            {!! Lte3::select2('type', request('type', 'order'), \App\Models\Shop\Order::typesList('name', 'key') + ['all' => 'Всі'], [
                'label' => 'Тип',
            ]) !!}
        </div>

        @if(\Domain::getOpt('promotions.on'))
        <div class="col-md-3 mt-4">
            {!! Lte3::checkbox('has_promotion', request('has_promotion'), [
                'label' => 'Має акцію/промокод',
                'class_control' => 'custom-switch',
            ]) !!}
        </div>
        @endif
    </div>
@stop
