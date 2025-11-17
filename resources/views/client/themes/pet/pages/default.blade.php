@extends('layouts.app')

@php
    Seo::setModel($page);
@endphp

@section('content')

    <div role="main" class="main">

        <div class="container">

            {{ Breadcrumbs::render('pages.show', $page) }}

            <div class="row">
                <div class="col">
                    <h1 class="font-weight-bold">{{ $page->name }}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    {!! $page->body !!}
                </div>

            </div>
        </div>

    </div>
@endsection