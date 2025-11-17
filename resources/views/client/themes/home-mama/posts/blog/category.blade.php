@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => 'Статті Блогу',
        'canonical' => URL::full(),
    ])
@endphp

@section('content')
    <main class="default-page">
        <div class="articles-top container">
            {{ Breadcrumbs::render('blog.category', $term) }}

            <h1 class="title">{{ $term->name }}</h1>
        </div>
        @include('posts.blog.inc.categories-swiper')
        @include('posts.blog.inc.content')
    </main>
@endsection
