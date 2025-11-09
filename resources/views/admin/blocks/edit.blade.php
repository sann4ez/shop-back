@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Блоки',
        'small_page_title' => 'Редагувати ' . $type['name'],
        'url_back' => request('_back') ?: route('admin.blocks.index', request()->only('type')),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body ">
                {!! Lte3::formOpen(['action' => route('admin.blocks.update', $block), 'model' => $block, 'method' => 'PATCH']) !!}
                @include('admin.blocks.inc.form', ['block' => $block, 'type' => $type])
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@stop
