@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => 'Catalog',
    ]);
@endphp

@section('content')
    <div role="main" class="main">
        <div class="container">

            {{ Breadcrumbs::render('catalog.index') }}

            @include('client.themes.pet.catalog.inc.categories-list', ['categories' => $categories])

        </div>

    </div>
@endsection
