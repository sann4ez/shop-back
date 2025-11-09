@extends('admin.layouts.app')

@section('content')

    @include('admin.parts.content-header', [
        'page_title' => 'Блоки',
        'small_page_title' => 'Створити ' . $type['name'],
        'url_back' => request('_back') ?: route('admin.blocks.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
            {!! Lte3::formOpen(['action' => route('admin.blocks.store'), 'model' => null, 'method' => 'POST']) !!}
                @include('admin.blocks.inc.form')
            {!! Lte3::formClose() !!}
            </div>

        </div>
    </section>
@endsection
