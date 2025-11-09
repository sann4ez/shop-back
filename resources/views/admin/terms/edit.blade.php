@extends('admin.layouts.app')

@section('btn-content-header')
    <div class="btn-actions dropdown">
        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
        <div class="dropdown-menu dropdown-menu-right" role="menu" style="top: 93%;">
            <a href="{{ route('admin.terms.destroy', $term) }}"
               class="dropdown-item js-click-submit" data-method="delete"
               data-confirm="Видалити?">Видалити</a>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => $vocabulary['name'],
        'small_page_title' => 'Редагувати',
        'url_back' => route('admin.terms.index', ['vocabulary' => $vocabulary['slug']])
    ])

    <section class="content">
        <div class="card">
            <div class="card-body ">
                {!! Lte3::formOpen(['action' => route('admin.terms.update', $term), 'model' => $term, 'method' => 'PATCH']) !!}
                {!! Lte3::hidden('_destination', Request::fullUrl()) !!}
                @include('admin.terms.inc.form', ['post' => $term])
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@stop
