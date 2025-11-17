@extends('layouts.app')

@section('content')
    <div role="main" class="main bg-light-5">
        <section class="section">
            <img src="{{ Theme::url('img/others/lamp-holder.png') }}" class="img-fluid lamp-style-2 position-absolute transform-center-x appear-animation" data-appear-animation="fadeIn" alt="" />
            <div class="container">
                <div class="row justify-content-center text-center py-5 mt-5 mb-3">
                    <div class="col-md-8 col-lg-6 pt-4 mt-5">
                        <h1 class="font-weight-bold text-6 mb-5 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200"><strong class="d-block font-weight-bold text-20">500!</strong>UNEXPECTED ERROR</h1>
                        <p class="mb-5 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">An unexpected error has occured. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim. </p>
                        <ul class="social-icons social-icons-icon-dark d-inline-flex appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">
                            <li class="social-icons-facebook">
                                <a href="http://www.facebook.com/" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            </li>
                            <li class="social-icons-twitter mx-2">
                                <a href="http://www.twitter.com/" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li class="social-icons-instagram">
                                <a href="http://www.instagram.com/" target="_blank" title="Instragram"><i class="fab fa-instagram"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection