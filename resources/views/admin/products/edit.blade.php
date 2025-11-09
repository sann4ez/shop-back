@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Товари',
        'small_page_title' => 'Редагувати',
        'url_back' => session('admin.products.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
                {{--
                <ul class="nav nav-tabs js-activeable-url" data-tag="a" data-class="active" style="margin-bottom: 15px">
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.products.edit', $product) }}">Дані</a>
                    </li>
                </ul>
                --}}
                {!! Lte3::formOpen(['action' => route('admin.products.update', $product), null , 'method' => 'PUT' ,'model' => $product]) !!}
                    @include('admin.products.inc.form')
                {!! Lte3::formClose() !!}
            </div>
        </div>

{{--        @include('admin.shop.products.inc.variations-table-wrap', ['product' => $product])--}}

    </section>
@endsection


@push('scripts')
    @if(request('variation'))
        <script>
        setTimeout(function () {
            $("[data-variation='{{request('variation')}}']").click()
        }, 500)
    </script>
    @endif

    @if(request('action') === 'variant')
        <script>
            setTimeout(function () {
                $('.js-btn-add-variant').click()
            }, 500)
        </script>
    @endif
@endpush
