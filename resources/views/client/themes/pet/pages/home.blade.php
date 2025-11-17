@extends('layouts.app')

@php
    Seo::setModel($page);
@endphp

@section('content')
    <div role="main" class="main">
        @if(($items = \App\Models\Menu\Menu::itemsBySlug('slider_home')) && $items->count())
        <div class="slider-container slider-container-full-height rev_slider_wrapper">
            <div id="revolutionSlider" class="slider rev_slider" data-version="5.4.8" data-plugin-revolution-slider data-plugin-options="{'delay': 9000, 'sliderLayout': 'fullscreen', 'fullScreenOffsetContainer': '#header', 'gridwidth': [1140,960,720,540], 'gridheight': [900,900,900,900], 'disableProgressBar': 'on', 'responsiveLevels': [4096,1200,992,576], 'navigation' : {'arrows': { 'enable': false, 'hide_under': 767, 'style': 'slider-arrows-style-1 slider-arrows-dark' }, 'bullets': {'enable': false, 'style': 'bullets-style-1', 'h_align': 'center', 'v_align': 'bottom', 'space': 7, 'v_offset': 35, 'h_offset': 0}}}">
                <ul>
                    @foreach($items as $slide)
                    <li data-transition="fade">
                        <img src="{{ $slide->getMyFirstMediaUrl('image') ?: Theme::url('img/slides/shop/slide-3-2.jpg') }}"
                            alt=""
                            data-bgposition="center center"
                            data-bgfit="cover"
                            data-bgrepeat="no-repeat"
                            data-kenburns="on"
                            data-duration="2500"
                            data-ease="Power2.easeInOut"
                            data-scalestart="125"
                            data-scaleend="100"
                            data-rotatestart="0"
                            data-rotateend="0"
                            data-blurstart="20"
                            data-blurend="0"
                            data-offsetstart="0 0"
                            data-offsetend="0 0"
                            class="rev-slidebg">

                        <div class="tp-caption font-primary font-weight-bold"
                            data-color="{{ $slide->getAdded('color', '#000000') }}"
                            data-x="left" data-hoffset="['52','52','17','17']"
                            data-y="center" data-voffset="['-80','-80','-80','-70']"
                            data-start="1000"
                            data-fontsize="['23','23','23','23']"
                            data-lineheight="['32','32','32','32']"
                            data-transform_in="y:[100%];opacity:0;s:500;"
                            data-transform_out="y:[100%];opacity:0;s:500;"
                            data-mask_in="x:0px;y:0px;">{{ $slide->getAdded('title1') }}</div>

                        <div class="tp-caption font-primary text-color-dark font-weight-bold"
                            data-color="{{ $slide->getAdded('color', '#000000') }}"
                            data-x="left" data-hoffset="['50','50','15','15']"
                            data-y="center" data-voffset="['-30','-30','-30','-30']"
                            data-start="1000"
                            data-fontsize="['65','65','65','47']"
                            data-lineheight="['70','70','70','52']"
                            data-transform_in="y:[100%];opacity:0;s:500;"
                            data-transform_out="y:[100%];opacity:0;s:500;"
                            data-mask_in="x:0px;y:0px;">{{ $slide->getAdded('title2') }}</div>

                        <div class="tp-caption font-primary text-color-dark font-weight-normal"
                            data-color="{{ $slide->getAdded('color', '#000000') }}"
                            data-x="left" data-hoffset="['50','50','15','15']"
                            data-y="center" data-voffset="['35','35','35','25']"
                            data-start="1000"
                            data-fontsize="['28','28','28','28']"
                            data-lineheight="['32','32','32','32']"
                            data-transform_in="y:[100%];opacity:0;s:500;"
                            data-transform_out="y:[100%];opacity:0;s:500;"
                            data-mask_in="x:0px;y:0px;">{{ $slide->getAdded('title3') }}</div>
                        @if($url = $slide->getUrlClient())
                        <a class="tp-caption btn btn-rounded btn-primary font-weight-semibold"
                            href="{{ $url }}"
                            target="{{ $slide->target }}"
                            data-hash
                            data-hash-offset="75"
                            data-x="left" data-hoffset="['50','50','15','15']"
                            data-y="center" data-voffset="['115','115','115','115']"
                            data-start="1600"
                            data-whitespace="nowrap"
                            data-fontsize="['13','14','14','14']"
                            data-paddingtop="['13','14','14','14']"
                            data-paddingbottom="['13','13','13','16']"
                            data-paddingleft="['50','50','50','50']"
                            data-paddingright="['50','50','50','50']"
                            data-transform_in="y:[-50%];opacity:0;s:500;"
                            data-transform_out="y:[50%];opacity:0;s:500;">{{ $slide->name }}</a>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        @endif

        @if(isset($featuredProducts) && $featuredProducts->count())
        <section class="section pt-5 pb-3">
            <div class="container">
                <div class="row text-center mb-4">
                    <div class="col">
                        <div class="overflow-hidden">
                            <span class="d-block top-sub-title text-color-primary appear-animation" data-appear-animation="maskUp">RECOMENDED</span>
                        </div>
                        <div class="overflow-hidden mb-2">
                            <h2 class="font-weight-bold mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="200">Featured Products</h2>
                        </div>
                    </div>
                </div>
                <div class="row appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">
                    @foreach($featuredProducts as $variation)
                    <div class="col-md-3">
                        <div class="product mb-4">
                            @include('catalog.inc.variation-frame-content', ['variation' => $variation])
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        @if(isset($topProducts) && $topProducts->count())
        <section class="section pt-5 pb-3">
            <div class="container">
                <div class="row text-center mb-4">
                    <div class="col">
                        <div class="overflow-hidden">
                            <span class="d-block top-sub-title text-color-primary appear-animation" data-appear-animation="maskUp">TOP PRODUCTS</span>
                        </div>
                        <div class="overflow-hidden mb-2">
                            <h2 class="font-weight-bold mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="200">Best Selling</h2>
                        </div>
                    </div>
                </div>
                <div class="row appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">

                    @foreach($topProducts as $variation)
                    <div class="col-md-3">
                        <div class="product mb-4">
                            @include('catalog.inc.variation-frame-content', ['variation' => $variation])
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </section>
        @endif

        @if(isset($popularCategories) && $popularCategories->count())
        <section class="section pt-5">
            <div class="container">
                <div class="row text-center mb-4">
                    <div class="col">
                        <div class="overflow-hidden">
                            <span class="d-block top-sub-title text-color-primary appear-animation" data-appear-animation="maskUp">CATEGORIES</span>
                        </div>
                        <div class="overflow-hidden mb-2">
                            <h2 class="font-weight-bold mb-0 appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="200">Browser Our Categories</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @foreach($popularCategories as $category)
                    <div class="col-8 col-md-5 col-lg-3 mb-4 mb-lg-0 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="200">
                        <a href="{{ $category->getUrlClient()  }}">
                            <div class="image-frame overlay overlay-show overlay-op-5 image-frame-style-1 image-frame-effect-2 image-frame-style-5">
                                <div class="image-frame-wrapper">
                                    {{--270*178--}}
                                    <img src="{{ $category->getMyFirstMediaUrl('image') ?:\Theme::url('img/shop/categorie-bg-1.jpg') }}" class="img-fluid" alt="">
                                    <div class="image-frame-info image-frame-info-show">
                                        <div class="image-frame-info-box-style-1">
                                            <h3 class="font-weight-bold text-color-default text-uppercase text-1 m-0 p-0">{{ $category->name }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <section class="section bg-light-5 p-0">
            <div class="container">
                <div class="featured-boxes featured-boxes-no-border-bottom">
                    <div class="row">
                        <div class="featured-box col-lg-4">
                            <a class="text-decoration-none" href="#">
                                <i class="fa fa-undo text-color-dark appear-animation" data-appear-animation="fadeInRightShorter"></i>
                                <div class="appear-animation" data-appear-animation="fadeInLeftShorter">
                                    <h2 class="font-weight-semibold text-3 mb-0">FREE RETURN</h2>
                                    <p>Return for any reason within 15 days</p>
                                </div>
                            </a>
                        </div>
                        <div class="featured-box col-lg-4">
                            <a class="text-decoration-none" href="#">
                                <i class="fa fa-shipping-fast text-color-dark appear-animation" data-appear-animation="fadeInRightShorter"></i>
                                <div class="appear-animation" data-appear-animation="fadeInLeftShorter">
                                    <h2 class="font-weight-semibold text-3 mb-0">FREE SHIPPING</h2>
                                    <p>Use promocode <span class="text-color-primary">FREESHIPPING</span></p>
                                </div>
                            </a>
                        </div>
                        <div class="featured-box col-lg-4">
                            <a class="text-decoration-none" href="#">
                                <i class="fa fa-headphones-alt text-color-dark appear-animation" data-appear-animation="fadeInRightShorter"></i>
                                <div class="appear-animation" data-appear-animation="fadeInLeftShorter">
                                    <h2 class="font-weight-semibold text-3 mb-0">LOVED BY OUR CUSTOMERS</h2>
                                    <p>Support 24/7</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('inc.newsletter-form')

    </div>
@endsection
