@extends('admin.layouts.app')

@section('btn-content-header')
    <a href="{{ route('admin.orders.create') }}" class="btn btn-flat btn-success mb-1"><i class="fa fa-plus"></i></a>
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => "Замовлення: {$orders->total()}",
        'btn_filter' => true,
        'btn_search' => true,
    ])

    <section class="content">

{{--        @include('admin.orders.inc.filter')--}}

        @if($orders->total())
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th style="width: 65px"></th>
                        <th style="width: 45px">{!! \Sort::getSortLink('number', '#') !!}</th>
                        <th style="width: 130px">{!! \Sort::getSortLink('source', 'Джерело') !!}</th>
                        <th style="width: 120px;">{!! \Sort::getSortLink('perform', 'Виконання') !!}</th>
                        <th style="width: 180px">{!! \Sort::getSortLink('ordered_at', 'Оформлено') !!}</th>
                        <th style="width: 120px">{!! \Sort::getSortLink('sum', 'Сума') !!}</th>
                        <th  style="width: 120px" data-toggle="tooltip" title=" = Сума всіх цін закупки варіацій в замовленні">{!! \Sort::getSortLink('sum_cost', 'Закупка') !!}</th>
                        <th  style="width: 120px" data-toggle="tooltip" title="% = Дохід * 100 / Закупка">{!! \Sort::getSortLink('profit', 'Прибуток') !!}</th>
                        <th>Отримувач</th>
                        <th style="width: 350px;">Коментар</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="va-center">
                            <td>
                                <div class="btn-actions dropdown">
                                    <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                    <div class="dropdown-menu" role="menu" style="top: 93%;">
                                        <a href="{{ route('admin.orders.edit', $order) }}" class="dropdown-item">Редагувати</a>
                                        @if($order->number)
                                        <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="dropdown-item">Друкувати</a>
                                        <a href="{{ route('admin.orders.print', [$order, '_format' => 'pdf']) }}" target="_blank" class="dropdown-item">Скачати</a>
                                        @endif
                                        <a href="{{ route('admin.orders.email', $order) }}" data-target="#modal-lg" class="dropdown-item js-modal-fill-html" data-fn-inits="initTinyMce">Надіслати Email</a>
                                        @if(in_array($order->perform, [\App\Models\Order::PERFORM_CANCELLED, \App\Models\Order::PERFORM_PENDING]) && auth()->user()->can('dev'))
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('admin.orders.destroy', $order) }}"
                                           class="dropdown-item js-click-submit" data-method="delete"
                                           data-confirm="Видалити?">Видалити</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a class="hover-edit" href="{{ route('admin.orders.edit', $order) }}"><strong>{{ $order->number }}</strong></a>
                            </td>
                            <td>{!! $order->getSource() !!}</td>
                            <td >
                                {!! $order->getPerformLte() !!}
                                @if($order->type == \App\Models\Order::TYPE_CART)
                                    <i class="fa fa-shopping-cart"></i>
                                @endif
                            </td>

                            <td>{{ $order->getDatetime('ordered_at') }}</td>
                            <td class="js-num-format">{{ $order->sum }}</td>
                            <td class="js-num-format">{{ $order->sum_cost }}</td>
                            <td>
                                @if($order->profit != 0)
                                <span style="color: {{ $order->getProfitColor() }}"><span class="js-num-format">{{ $order->profit }}</span> <small class="text-gray">{{ $order->getProfitPercent() }}%</small></span>
                                @else
                                    <span class="js-num-format">{{ $order->profit }}</span>
                                @endif
                            </td>
                            <td>
                                {!! $order->getRecipientLte() !!}
                            </td>
                            <td class="text-sm">
                                <div>{{ $order->client_comment }}</div>

                                {!! Lte3::xEditable('manager_comment', $order->manager_comment, [
                                    'type' => 'textarea',
                                    'pk' => $order->id,
                                    'url_save' => route('admin.orders.editable', $order),
                                ]) !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {!! Lte3::pagination($orders ?? null) !!}
            </div>
        </div>
        @else
            @include('admin.parts.empty-rows')
        @endif
    </section>
@endsection
