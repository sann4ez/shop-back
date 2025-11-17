@extends('layouts.app')

@section('content')
    <div role="main" class="main">
        <div class="container">


            @yield('breadcrumb')

            <div class="row mb-5">
                <div class="col">
                    <ul class="nav nav-tabs nav-tabs-default" id="productDetailTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold @if(\Route::currentRouteName() === 'my.profile.edit') active @endif" href="{{ route('my.profile.edit') }}" >ACCOUNT</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold @if(\Route::currentRouteName() === 'my.orders.index') active @endif" href="{{ route('my.orders.index') }}" >ORDERS</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade pt-4 pb-4 show active">
                        @yield('my-content')
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

@endsection