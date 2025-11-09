@extends('admin.layouts.app')

@section('btn-content-header')
    @include('admin.pages.parts.btn-actions', ['page' => $page, 'dropdownClass' => 'dropdown-menu-right'])
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Сторінки',
        'small_page_title' => 'Редагувати',
        'url_back' => session('admin.pages.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">

                <ul class="nav nav-tabs js-activeable-url" data-tag="a" data-class="active" style="margin-bottom: 15px">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.pages.edit', $page) }}">Дані</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.pages.edit', [$page, '_tab' => 'blocks']) }}"
                           data-pat="blocks">Блоки</a>
                    </li>
                </ul>
                @if (\Request::fullUrlIs(route('admin.pages.edit', [$page, '_tab' => 'blocks'])))
                @include('admin.pages.inc.blocks', ['entity' => $page])
                @else
                {!! Lte3::formOpen(['action' => route('admin.pages.update', $page), null , 'method' => 'PUT' ,'model' =>$page]) !!}
                @include('admin.pages.inc.form')
                {!! Lte3::formClose() !!}
            @endif
            </div>
        </div>
    </section>
@endsection
