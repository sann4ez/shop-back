@extends('my.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('my', 'Orders') }}
@stop

@section('my-content')
    <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Sum</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Ordered</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
            <tr>
                <th scope="row">{{ $order->number }}</th>
                <td>{{ $order->totalSum() }}{{ $order->currency_code }}</td>
                <td>{{ $order->getStatus() }}</td>
                <td>{{ $order->getPaymentStatus() }}</td>
                <td>{{ $order->getDatetime('ordered_at') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
@endsection