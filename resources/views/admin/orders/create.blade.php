@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Замовлення',
        'url_back' => session('admin.orders.index'),
    ])

    <section class="content">
        {!! Lte3::formOpen(['action' => route('admin.orders.store'), 'model' => null, 'method' => 'POST']) !!}
            @include('admin.orders.inc.form')
        {!! Lte3::formClose() !!}
    </section>
@stop
