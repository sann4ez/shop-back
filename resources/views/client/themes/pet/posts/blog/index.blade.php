@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => 'Blog',
    ]);
@endphp

@section('content')
    <div role="main" class="main">

        <div class="container">

            {{ Breadcrumbs::render('blog.index') }}

            <div class="row">
                <div class="col">
                    <h1 class="font-weight-bold">Blog</h1>
                </div>
            </div>

            <div class="row">
                <aside class="sidebar col-md-4 col-lg-3 order-2">
                    <div class="accordion accordion-default accordion-toggle accordion-style-1" role="tablist">

                        <div class="card">
                            <div id="toggleSidebarSearch" class="accordion-body accordion-body-show-border-top collapse show" role="tabpanel" aria-labelledby="sidebarSearchForm">
                                <div class="card-body pt-4">
                                    <form id="sidebarSearchForm" class="sidebar-search" action="page-search-results.html" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control line-height-1 bg-light-5" name="s" id="s" placeholder="Search..." required="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-light" type="submit"><i class="fas fa-search text-color-primary"></i></button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="categories">
                                <h3 class="text-3 mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#toggleCategories" aria-expanded="false" aria-controls="toggleCategories">CATEGORIES</a>
                                </h3>
                            </div>
                            <div id="toggleCategories" class="accordion-body collapse show" aria-labelledby="categories">
                                <div class="card-body">
                                    <ul class="list list-unstyled">
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold"><i class="fas fa-angle-right ml-1 mr-1"></i> Design</a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold text-color-primary"><i class="fas fa-angle-right ml-1 mr-1" id="photos" data-toggle="collapse" data-target="#submenuPhotos" aria-expanded="true" aria-controls="submenuPhotos" role="list" onclick="return false;"></i> Photos (3)</a>
                                            <ul class="list list-unstyled collapse show" id="submenuPhotos" aria-labelledby="photos">
                                                <li>
                                                    <a href="#">Animals</a>
                                                </li>
                                                <li>
                                                    <a href="#">Business (4)</a>
                                                </li>
                                                <li>
                                                    <a href="#">Sports</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold"><i class="fas fa-angle-right ml-1 mr-1"></i> Videos</a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold"><i class="fas fa-angle-right ml-1 mr-1"></i> Lifestyle</a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="font-weight-semibold"><i class="fas fa-angle-right ml-1 mr-1"></i> Technology</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header accordion-header" role="tab" id="tags">
                                <h3 class="text-3 mb-0">
                                    <a href="#" data-toggle="collapse" data-target="#toggleTags" aria-expanded="false" aria-controls="toggleTags">TAGS</a>
                                </h3>
                            </div>
                            <div id="toggleTags" class="accordion-body collapse show" role="tabpanel" aria-labelledby="tags">
                                <div class="card-body">
                                    <ul class="list-inline">
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">NEWS</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">JOBS</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">POST</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">PHOTOS</a></li>
                                        <li class="list-inline-item"><a href="#" class="badge badge-dark badge-sm badge-pill px-3 py-2 mb-2">INNOVATION</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
                <div class="col-md-8 col-lg-9 order-1 mb-5 pb-2 mb-md-0">
                    <div class="row">

                        @foreach($posts as $post)
                        <div class="col-lg-6 mb-4">
                            <a href="{{ $post->getUrlClient() }}">
                                <div class="card card-style-5 bg-light-5 rounded border-0 p-3 mb-2" data-plugin-image-background data-plugin-options="{'imageUrl': '{{ $post->getMyFirstMediaUrl('image', '', \Theme::url('img/blog/posts/post-1-square.jpg')) }}'}">
                                    <div class="card-body p-4">
                                        <h3 class="font-weight-bold text-4 mb-1">{{ $post->name }}</h3>
                                        <p>
                                            <i class="far fa-clock mt-1 text-color-primary"></i>
                                            <time class="font-tertiary text-1" datetime="{{ $post->getDatetime('created_at', 'Y-m-d') }}">{{ $post->created_at->isoFormat('Do MMMM YYYY') }}</time>
                                        </p>
                                        <p>{{ $post->getTeaser() }}</p>
                                        @if($user = $post->user)
                                        <p class="text-color-dark font-weight-semibold mb-0">
                                            <img src="{{ \Avatar::create($user->name)->toBase64() }}" class="img-thumbnail-small rounded-circle d-inline-block mr-2" width="25" height="25" alt="" />
                                            by {{ $user->name }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach

                    </div>
                    {{--
                    <hr class="mt-5 mb-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <span>Showing 1-9 of 60 results</span>
                        </div>
                        <div class="col-auto">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination mb-0">
                                    <li class="page-item">
                                        <a class="page-link prev" href="#" aria-label="Previous">
                                            <span><i class="fas fa-angle-left" aria-label="Previous"></i></span>
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">...</li>
                                    <li class="page-item"><a class="page-link" href="#">15</a></li>
                                    <li class="page-item">
                                        <a class="page-link next" href="#" aria-label="Next">
                                            <span><i class="fas fa-angle-right" aria-label="Next"></i></span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    --}}
                    @include('parts.pagination', ['items' => $posts])
                </div>
            </div>
        </div>

    </div>
@endsection