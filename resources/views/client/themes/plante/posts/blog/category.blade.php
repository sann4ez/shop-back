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
                {{ Breadcrumbs::render('blog.category', $term) }}

                <h1 class="title">{{ $term->name }}</h1>
            </div>

            <div class="articles__content">
                @include('posts.blog.inc.categories')

                @include('posts.blog.inc.content')
            </div>

        </section>
    </main>

@endsection
