@extends('layouts.app')

@php
    Seo::setDefault([
        'title' => 'Promotions',
    ]);
@endphp

@section('content')
    <div role="main" class="main">

        <section class="section">
            <div class="container">

                {{ Breadcrumbs::render('promotions.index') }}

                <div class="row  mb-5">
                    <div class="col">
                        <h1 class="font-weight-bold">Promotions</h1>
                    </div>
                </div>

                <div class="row justify-content-center">

                    @foreach($promotions as $promotion)
                        @if($loop->odd)
                        <div class="col-sm-6 order-3 order-md-4 p-sm-0">
                            <div class="owl-carousel owl-theme carousel-grid-style-1 dots-style-2 nav-style-2 h-100" data-plugin-options="{'items': 1, 'dots': true, 'nav': false, 'animateIn': 'animate__fadeIn', 'animateOut': 'animate__fadeOut'}">
                                @foreach($promotion->getMedia('images') as $media)
                                <div class="h-100">
                                    <a href="#">
                                        <div class="image-frame hover-effect-2 h-100">
                                            <div class="image-frame-wrapper h-100">
                                                <img src="{{ $media->getUrl() }}" class="img-fluid min-height-285" alt="" />
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-6 order-4 order-md-3 p-sm-0">
                            <div class="card bg-light-5 border-0 justify-content-center h-100">
                                <div class="card-body card-body-flex-0 p-5">
                                    <span class="d-block top-sub-title text-color-primary mb-2">{{ $promotion->getDatePeriodStr() }}</span>
                                    <h2 class="text-color-dark font-weight-semibold line-height-3 text-4 mb-1">
                                        <a href="#" class="link-color-dark d-block">{{ $promotion->name }}</a>
                                    </h2>
                                    <p>{!! $promotion->body !!}</p>
                                </div>
                            </div>
                        </div>
                        @else
                        {{--
                        <div class="col-sm-6 order-5 p-sm-0">
                            <a href="#">
                                <div class="image-frame hover-effect-2 h-100">
                                    <div class="image-frame-wrapper min-height-285 h-100">
                                        <div class="image-frame-background" data-plugin-image-background data-plugin-options="{'imageUrl': 'img/blog/posts/post-3-square.jpg'}"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        --}}
                        <div class="col-sm-6 order-3 order-md-4 p-sm-0">
                            <div class="owl-carousel owl-theme carousel-grid-style-1 dots-style-2 nav-style-2 h-100" data-plugin-options="{'items': 1, 'dots': true, 'nav': false, 'animateIn': 'animate__fadeIn', 'animateOut': 'animate__fadeOut'}">
                                @foreach($promotion->getMedia('images') as $media)
                                    <div class="h-100">
                                        <a href="#">
                                            <div class="image-frame hover-effect-2 h-100">
                                                <div class="image-frame-wrapper h-100">
                                                    <img src="{{ $media->getUrl() }}" class="img-fluid min-height-285" alt="" />
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-6 order-6 p-sm-0">
                            <div class="card bg-light-5 border-0 justify-content-center h-100">
                                <div class="card-body card-body-flex-0 p-5">
                                    <span class="d-block top-sub-title text-color-primary mb-2">{{ $promotion->getDatePeriodStr() }}</span>
                                    <h2 class="text-color-dark font-weight-semibold line-height-3 text-4 mb-1">
                                        <a href="#" class="link-color-dark d-block">{{ $promotion->name }}</a>
                                    </h2>
                                    <p>{!! $promotion->body !!}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </section>

    </div>
@stop