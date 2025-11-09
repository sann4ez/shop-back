@extends('admin.layouts.app')

@section('content')

@include('admin.parts.content-header', [
    'page_title' => 'Сторінки',
    'small_page_title' => 'Створити',
    'url_back' => session('admin.pages.index'),
])
<section class="content">
        <div class="card">
            <div class="card-body">
            {!! Lte3::formOpen(['action' => route('admin.pages.store'), 'model' => null, 'method' => 'POST']) !!}
                @include('admin.pages.inc.form')
            {!! Lte3::formClose() !!}
            </div>

        </div>

        </section>
@endsection
