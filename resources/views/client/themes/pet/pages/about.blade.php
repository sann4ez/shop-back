@extends('layouts.app')

@php
    Seo::setModel($page);
@endphp

@section('content')
    <div role="main" class="main">
        <div class="container">

            {{ Breadcrumbs::render('pages.show', $page) }}

            <div class="row">
                <div class="col">
                    <h1 class="font-weight-bold">{{ $page->name }}</h1>
                </div>
            </div>
        </div>

        <section class="section section-height-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="100">
                            <span class="top-sub-title text-color-primary">LOREM IPSUM DOLOR SIT</span>

                            <h2 class="font-weight-bold text-6 mb-3">Who We Are</h2>
                        </div>
                        <p class="lead mb-3 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="300">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim. Nullam id varius nunc.</p>

                        <p class="text-color-light-3 mb-4 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim. Nullam id varius nunc. Vivamus bibendum magna ex, et faucibus lacus venenatis eget. Suspendisse potenti. Curabitur euismod facilisis neque, eu malesuada diam finibus non. Nullam nec vehicula leo. Morbi porta ante eu feugiat mollis.</p>

                        <a href="/contacts" class="btn btn-outline btn-rounded btn-primary btn-v-3 btn-h-4 font-weight-bold text-0 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="700">CONTACT US</a>
                    </div>
                    <div class="col-10 col-md-6 col-lg-5 mx-auto ml-md-auto appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">
                        <div class="particles d-flex align-items-center pr-0 pr-lg-3 pr-xl-5">
                            <svg class="svg-particles d-none d-sm-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 493.72 405.79">
                                <g class="g-particles g-particles-group-1 appear-animation" data-appear-animation="expandParticles" data-appear-animation-delay="500">
                                    <line class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" x1="150.28" y1="108.35" x2="159.03" y2="102.1"/>
                                    <line class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" x1="325.51" y1="118.98" x2="334.26" y2="112.73"/>
                                    <line class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" x1="483.33" y1="94.52" x2="492.08" y2="88.27"/>
                                    <line class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" x1="321.14" y1="279.22" x2="329.89" y2="272.97"/>
                                    <line class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" x1="148.47" y1="279.22" x2="157.22" y2="272.97"/>
                                    <line class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" x1="11.14" y1="305.17" x2="19.89" y2="298.92"/>
                                </g>
                                <g class="g-particles g-particles-group-2 appear-animation" data-appear-animation="expandParticles" data-appear-animation-delay="800">
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M51.78,105.64s-.37-12.75,5.25-3.5.38,7.13,0,7.13" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M229.34,103.24s-.37-12.75,5.25-3.5.38,7.12,0,7.12" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M401,103.24s-.37-12.75,5.25-3.5.38,7.12,0,7.12" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M401,274.1s-.37-12.75,5.25-3.5.38,7.13,0,7.13" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M227.33,276.14s-.37-12.75,5.25-3.5.38,7.13,0,7.13" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M55.49,276.14s-.37-12.75,5.25-3.5.38,7.13,0,7.13" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M97.76,41.59s-2.5,4.83,2.83,2.33.67,2.67.67,2.67" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M448.14,22.85s-2.5,4.83,2.83,2.33.67,2.67.67,2.67" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-1" fill="none" stroke="#969da0" stroke-miterlimit="10" d="M246,44.18s.1,5.85,4.41-.28,2.26,1.66,2.26,1.66" transform="translate(-3.14 -1.85)"/>
                                </g>
                                <g class="g-particles g-particles-group-3 appear-animation" data-appear-animation="expandParticles" data-appear-animation-delay="1100">
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="4.77" cy="151" r="1.14" transform="translate(-108.51 45.76) rotate(-45)"/>
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="149.72" cy="30.16" r="1.14" transform="translate(19.39 112.85) rotate(-45)"/>
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="304.7" cy="3.48" r="1.14" transform="translate(83.64 214.63) rotate(-45)"/>
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="495.22" cy="155.04" r="1.14" transform="translate(32.28 393.74) rotate(-45)"/>
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="315.2" cy="163.8" r="1.14" transform="translate(-26.64 269.01) rotate(-45)"/>
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="153.42" cy="344.7" r="1.14" transform="translate(-201.94 207.59) rotate(-45)"/>
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="249.61" cy="406" r="1.14" transform="translate(-217.11 293.57) rotate(-45)"/>
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="313.32" cy="336.94" r="1.14" transform="translate(-149.62 318.39) rotate(-45)"/>
                                    <circle class="cls-2" fill="#969da0" stroke="#969da0" cx="148.58" cy="172.2" r="1.14" transform="translate(-81.38 153.65) rotate(-45)"/>
                                </g>
                                <g class="g-particles g-particles-group-4 appear-animation" data-appear-animation="expandParticles" data-appear-animation-delay="1400">
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="200.87 18.34 197.2 18.34 199.03 21"/>
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="179.66 368.64 180.47 364.7 176.12 365.86"/>
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="369.87 368.64 370.67 364.7 366.31 365.86"/>
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="369.31 5.73 371.83 1.9 367.27 1.65"/>
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="217.7 153.19 210.32 153.19 214.01 158.56"/>
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="385.03 153.19 377.66 153.19 381.34 158.56"/>
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="385.03 324.15 377.66 324.15 381.34 329.52"/>
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="213.01 324.15 205.64 324.15 209.32 329.52"/>
                                    <polyline class="cls-3" fill="none" stroke="#969da0" points="52.35 324.15 44.98 324.15 48.66 329.52"/>
                                </g>
                                <g class="g-particles g-particles-group-5 appear-animation" data-appear-animation="expandParticles" data-appear-animation-delay="1700">
                                    <path class="cls-4" fill="none" stroke="#969da0" d="M264.48,188.12s-4,8.83,3.33,6.17,5.67-.5,5.67-.5-1.33,3.67-2.17,3.5" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-4" fill="none" stroke="#969da0" d="M444.52,179.07s-4,8.83,3.33,6.17,5.67-.5,5.67-.5-1.33,3.67-2.17,3.5" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-4" fill="none" stroke="#969da0" d="M94.23,196.91s-4,8.83,3.33,6.17,5.67-.5,5.67-.5-1.33,3.67-2.17,3.5" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-4" fill="none" stroke="#969da0" d="M92.81,369.86s-2.63,6.55,2.19,4.57,3.73-.37,3.73-.37-.88,2.72-1.43,2.6" transform="translate(-3.14 -1.85)"/>
                                    <path class="cls-4" fill="none" stroke="#969da0" d="M269.94,368.71c.06-.09,3.2,5.44,4.68,2,2-4.8,2.54-2.76,2.54-2.76s1.15,2.61.66,2.89" transform="translate(-3.14 -1.85)"/>
                                </g>
                                <g class="g-particles g-particles-group-6 appear-animation" data-appear-animation="expandParticles" data-appear-animation-delay="2000">
                                    <polyline class="cls-5" fill="#969da0" stroke="#969da0" points="8.58 76.55 13.71 78.42 12.58 72.8"/>
                                    <polyline class="cls-5" fill="#969da0" stroke="#969da0" points="8.58 228.65 13.71 230.53 12.58 224.9"/>
                                    <polyline class="cls-5" fill="#969da0" stroke="#969da0" points="178.55 238.99 183.68 240.86 182.55 235.24"/>
                                    <polyline class="cls-5" fill="#969da0" stroke="#969da0" points="349.47 240.53 354.59 242.4 353.47 236.78"/>
                                    <polyline class="cls-5" fill="#969da0" stroke="#969da0" points="338.72 402.28 343.84 404.15 342.72 398.53"/>
                                    <polyline class="cls-5" fill="#969da0" stroke="#969da0" points="147.72 399.46 152.84 401.34 151.72 395.71"/>
                                    <polyline class="cls-5" fill="#969da0" stroke="#969da0" points="482.39 209.27 485.95 213.4 487.71 207.94"/>
                                    <polyline class="cls-5" fill="#969da0" stroke="#969da0" points="349.58 66.58 354.83 68.06 353.29 62.54"/>
                                </g>
                            </svg>
                            <div class="particles-counter bg-light" data-plugin-float-element data-plugin-options="{'startPos': 'top', 'speed': 7, 'horizontal': true, 'transition': true}">
                                <div class="counters counters-sm">
                                    <div class="row m-0">
                                        <div class="col-sm-6">
                                            <div class="counter text-dark my-4 pb-1">
                                                <strong data-to="25000" data-append="+">25000+</strong>
                                                <label class="font-weight-light">Happy Clients</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="counter text-dark my-4 pb-1">
                                                <strong data-to="15">15</strong>
                                                <label class="font-weight-light">Years in Business</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="counter text-dark my-4 pt-1">
                                                <strong data-to="352">352</strong>
                                                <label class="font-weight-light">Answered Tickets</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="counter text-dark my-4 pt-1">
                                                <strong data-to="178">178</strong>
                                                <label class="font-weight-light">Development Hours</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="row text-center pb-2 mb-4">
                    <div class="col">
                        <div class="overflow-hidden">
                            <span class="top-sub-title text-color-primary d-block appear-animation" data-appear-animation="maskUp">OUR ROCKSTARS</span>
                        </div>
                        <div class="overflow-hidden">
                            <h2 class="font-weight-bold appear-animation" data-appear-animation="maskUp" data-appear-animation-delay="200">Our Team</h2>
                        </div>
                    </div>
                </div>
                <div class="row appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">
                    <div class="col">
                        <div class="owl-carousel owl-theme dots-style-1 nav-style-3" data-plugin-options="{'responsive': {'0': {'items': 1}, '576': {'items': 1}, '768': {'items': 3}, '979': {'items': 4}, '1199': {'items': 4}}, 'dots': true, 'nav': false, 'loop': false, 'navtext': [], 'margin': 30}">
                            <div>
                                <div class="text-center mb-2">
                                    <div class="image-frame image-frame-style-1 image-frame-effect-1 mb-4">
												<span class="image-frame-wrapper">
													<img src="{{ Theme::url('img/authors/author-1.jpg') }}" class="img-fluid" alt="">
													<span class="image-frame-inner-border"></span>
													<span class="image-frame-action image-frame-action-effect-1 image-frame-action-sm">
														<a href="ajax/team-member-detail.html" class="simple-ajax-popup">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-magnifier text-color-light"></i>
															</span>
														</a>
														<a href="about-me.html">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-link text-color-light"></i>
															</span>
														</a>
													</span>
												</span>
                                    </div>
                                    <h3 class="font-weight-bold text-4 mb-0">John Doe</h3>
                                    <span class="text-1">CEO AND FOUNDER</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-center mb-2">
                                    <div class="image-frame image-frame-style-1 image-frame-effect-1 mb-4">
												<span class="image-frame-wrapper">
													<img src="{{ Theme::url('img/authors/author-2.jpg') }}" class="img-fluid" alt="">
													<span class="image-frame-inner-border"></span>
													<span class="image-frame-action image-frame-action-effect-1 image-frame-action-sm">
														<a href="ajax/team-member-detail.html" class="simple-ajax-popup">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-magnifier text-color-light"></i>
															</span>
														</a>
														<a href="about-me.html">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-link text-color-light"></i>
															</span>
														</a>
													</span>
												</span>
                                    </div>
                                    <h3 class="font-weight-bold text-4 mb-0">Bob Doe</h3>
                                    <span class="text-1">CO-FOUNDER</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-center mb-2">
                                    <div class="image-frame image-frame-style-1 image-frame-effect-1 mb-4">
												<span class="image-frame-wrapper">
													<img src="{{ Theme::url('img/authors/author-3.jpg') }}" class="img-fluid" alt="">
													<span class="image-frame-inner-border"></span>
													<span class="image-frame-action image-frame-action-effect-1 image-frame-action-sm">
														<a href="ajax/team-member-detail.html" class="simple-ajax-popup">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-magnifier text-color-light"></i>
															</span>
														</a>
														<a href="about-me.html">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-link text-color-light"></i>
															</span>
														</a>
													</span>
												</span>
                                    </div>
                                    <h3 class="font-weight-bold text-4 mb-0">Jessica Doe</h3>
                                    <span class="text-1">SALES</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-center mb-2">
                                    <div class="image-frame image-frame-style-1 image-frame-effect-1 mb-4">
												<span class="image-frame-wrapper">
													<img src="{{ Theme::url('img/authors/author-4.jpg') }}" class="img-fluid" alt="">
													<span class="image-frame-inner-border"></span>
													<span class="image-frame-action image-frame-action-effect-1 image-frame-action-sm">
														<a href="ajax/team-member-detail.html" class="simple-ajax-popup">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-magnifier text-color-light"></i>
															</span>
														</a>
														<a href="about-me.html">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-link text-color-light"></i>
															</span>
														</a>
													</span>
												</span>
                                    </div>
                                    <h3 class="font-weight-bold text-4 mb-0">Alice Doe</h3>
                                    <span class="text-1">MARKETING</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-center mb-2">
                                    <div class="image-frame image-frame-style-1 image-frame-effect-1 mb-4">
												<span class="image-frame-wrapper">
													<img src="{{ Theme::url('img/authors/author-5.jpg') }}" class="img-fluid" alt="">
													<span class="image-frame-inner-border"></span>
													<span class="image-frame-action image-frame-action-effect-1 image-frame-action-sm">
														<a href="ajax/team-member-detail.html" class="simple-ajax-popup">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-magnifier text-color-light"></i>
															</span>
														</a>
														<a href="about-me.html">
															<span class="image-frame-action-icon">
																<i class="lnr lnr-link text-color-light"></i>
															</span>
														</a>
													</span>
												</span>
                                    </div>
                                    <h3 class="font-weight-bold text-4 mb-0">WILL DOE</h3>
                                    <span class="text-1">MARKETING</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="section bg-light-5">
            <div class="container">
                <div class="row align-items-center justify-content-center appear-animation" data-appear-animation="fadeInLeftShorter">
                    <div class="col-4 col-md-1-5 text-center text-md-left">
                        <img src="{{ Theme::url('img/logos/logo-7.png') }}" width="170" alt="" class="img-fluid">
                    </div>
                    <div class="col-4 col-md-1-5 text-center">
                        <img src="{{ Theme::url('img/logos/logo-8.png') }}" width="80" alt="" class="img-fluid">
                    </div>
                    <div class="col-4 col-md-1-5 text-center">
                        <img src="{{ Theme::url('img/logos/logo-24.png') }}" width="100" alt="" class="img-fluid">
                    </div>
                    <div class="col-4 col-md-1-5 text-center text-md-right mt-5 mt-md-0">
                        <img src="{{ Theme::url('img/logos/logo-10.png') }}" width="140" alt="" class="img-fluid">
                    </div>
                    <div class="col-4 col-md-1-5 text-center text-md-right mt-5 mt-md-0">
                        <img src="{{ Theme::url('img/logos/logo-11.png') }}" width="100" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop