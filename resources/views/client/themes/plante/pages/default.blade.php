@extends('layouts.app')

@php
    Seo::setModel($page);
@endphp

@section('content')

    <main>
        <div class="delivery container">
            <div class="header-page">
                {{ Breadcrumbs::render('pages.show', $page) }}
            </div>
            <div class="typography">
                <h1>{{ $page->name }}</h1>
                @if(!$page->body)
                    <div class="search-fail">
                        <div class="search-fail__logo">:(</div>
                        <div class="main-text">Контент наповнюється</div>
                    </div>
                @else
                    {!! $page->body !!}
                @endif
            </div>
        </div>
    </main>

@endsection
