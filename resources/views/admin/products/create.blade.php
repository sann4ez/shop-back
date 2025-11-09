@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Товари',
        'small_page_title' => 'Створити',
        'url_back' => session('admin.products.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
                {{--
                <ul class="nav nav-tabs js-activeable-url" data-tag="a" data-class="active" style="margin-bottom: 15px">
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.products.create') }}">Дані</a>
                    </li>
                </ul>
                --}}
                {!! Lte3::formOpen(['action' => route('admin.products.store'), 'model' => null, 'method' => 'POST']) !!}
                    @include('admin.products.inc.form')
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@endsection
