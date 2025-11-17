@extends('layouts.app')

@php
   $tags = [
        'title' => 'Статті Блогу',
        'canonical' => URL::full(),
    ];

   $sorts = [
       ['title' => 'по замовчуванню', 'sort' => 'default', 'order' => 'desc'],
       ['title' => 'за новизною', 'order' => 'desc', 'sort' => 'income_at'],
       ['title' => 'за популярністю', 'order' => 'desc', 'sort' => 'rating'],
       ['title' => 'за ціною (дешевші)', 'order' => 'asc', 'sort' => 'price'],
       ['title' => 'за ціною (дорожчі)', 'order' => 'desc', 'sort' => 'price'],
   ];

   seo_suffix($tags, $sorts);

   Seo::setDefault($tags);

   if ($page = \App\Models\Page::whereSlug('page-posts')->withoutGlobalScopes()->first()) {
        Seo::setModel($page);
    }
@endphp

@section('content')
    <main class="default-page">
        <div class="articles-top container">
            {{ Breadcrumbs::render('blog.index') }}

            <h1 class="title">Cтатті</h1>
        </div>
        @include('posts.blog.inc.categories-swiper')
        @include('posts.blog.inc.content')
    </main>
@endsection
