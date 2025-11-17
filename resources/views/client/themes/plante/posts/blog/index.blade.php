@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => 'Статті Блогу',
        'canonical' => URL::full(),
    ])
@endphp

@section('content')

    <main>
        <section class="articles container">

            <div class="header-page">
                {{ Breadcrumbs::render('blog.index') }}

                <h1 class="title">Статті</h1>
            </div>

            <div class="articles__content">
                @include('posts.blog.inc.categories')

                @if($posts->isEmpty())
                    <div class="search-fail">
                        <div class="search-fail__logo">:(</div>
                        <div class="main-text">Контент наповнюється</div>
                    </div>
                @else
                    @include('posts.blog.inc.content')
                @endif
            </div>

        </section>
    </main>

@endsection
