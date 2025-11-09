@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => $vocabulary['name'],
        'small_page_title' => 'Створити',
        'url_back' => route('admin.terms.index', ['vocabulary' => $vocabulary['slug']])
    ])

    <section class="content">
        <div class="card">
            <div class="card-body ">
                {!! Lte3::formOpen(['action' => route('admin.terms.store'), 'model' => null, 'method' => 'POST']) !!}
                @include('admin.terms.inc.form')
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@stop
