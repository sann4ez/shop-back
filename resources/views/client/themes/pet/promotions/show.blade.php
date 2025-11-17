@extends('layouts.app')

@php
    Seo::setModel($post)->setDefault([
        'title' => $post->name,
        'description' => $post->getTeaser(),
        'og_image' => $post->getMyFirstMediaUrl('image', '', \Theme::url('img/blog/posts/post-1-square.jpg')),
    ]);
@endphp

@section('content')

@stop