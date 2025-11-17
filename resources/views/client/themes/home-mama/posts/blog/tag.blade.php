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
            {{ Breadcrumbs::render('blog.tag', $term) }}

            <h1 class="title">{{ $term->name }}</h1>
        </div>
        <div class="categories swiper-category">
            <div class="swiper-wrapper">
                @foreach($tags as $tag)
                    <a href="{{ $tag->getUrlClient() }}" class="category swiper-slide {{ request()->is('blog/tags/' . $tag->slug) ? 'active' : '' }}">#{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
        @include('posts.blog.inc.content')
    </main>
@endsection
