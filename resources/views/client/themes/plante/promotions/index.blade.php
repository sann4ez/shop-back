@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => trans('client.Promotions'),
    ]);
@endphp

@section('content')
    <main>

        <section class="shares-articles container">

            <div class="header-page">
                <nav class="header-page__breadcrumbs">

                    {{ Breadcrumbs::render('promotions.index') }}

                </nav>
                <h1 class="title">Акції</h1>
            </div>

            <div class="shares-articles__content">
                <div class="shares-articles__grid js-perpage-source">
                    @include('promotions.inc.promotions-list')
                </div>
                @if($promotions->isEmpty())
                    <div class="search-fail">
                        <div class="search-fail__logo">:(</div>
                        <div class="main-text">Акцій поки-що немає</div>
                    </div>
                @endif
                @include('parts.pagination-navigation', ['items' => $promotions])
            </div>
        </section>

    </main>
@endsection
