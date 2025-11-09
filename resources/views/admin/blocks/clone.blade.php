@extends('admin.layouts.app')

@section('content')

    @include('admin.parts.content-header', [
        'page_title' => 'Блоки',
        'small_page_title' => 'Клонувати ' . $type['name'],
        'url_back' => request('_back') ?: route('admin.blocks.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Клонувати</h3>
            </div>
            <div class="card-body">
            {!! Lte3::formOpen(['action' => route('admin.blocks.store'), 'model' => $block, 'method' => 'POST']) !!}
                @include('admin.blocks.inc.form')
            {!! Lte3::formClose() !!}
            </div>

        </div>
    </section>
@endsection
