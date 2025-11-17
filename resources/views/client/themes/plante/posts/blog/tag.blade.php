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
                {{ Breadcrumbs::render('blog.tag', $term) }}

                <h1 class="title">{{ $term->name }}</h1>
            </div>

            <div class="articles__content">
                <ul class="side-menu" aria-label="sideMenuList">
                    @foreach($tags as $tag)
                        <li class="side-menu__item"><a href="{{ $tag->getUrlClient() }}" class="side-menu__link main-text {{ request()->is('blog/tags/' . $tag->slug) ? 'side-menu__link--active' : '' }}">#{{ $tag->name }}</a></li>
                    @endforeach
                </ul>

                @include('posts.blog.inc.content')
            </div>

        </section>
    </main>

@endsection
