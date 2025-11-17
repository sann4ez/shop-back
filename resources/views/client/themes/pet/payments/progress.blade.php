@extends('layouts.app')

@section('content')
    <div role="main" class="main">
        <section class="section bg-light-5">
            <img src="{{ Theme::url('img/others/lamp-holder.png') }}" class="img-fluid lamp-style-2 position-absolute transform-center-x appear-animation" data-appear-animation="fadeIn" alt="" />
            <div class="container">
                <div class="row justify-content-center text-center py-5 mt-5 mb-3">
                    <div class="col-md-8 col-lg-6 pt-4 mt-5">
                        <h1 class="font-weight-bold text-6 mb-5 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">WAIT</h1>
                        <p class="mb-5 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">Payment confirmation is in progress.</p>

                        <a href="/" class="btn btn-primary btn-rounded btn-v-3 btn-h-3 font-weight-bold appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="800"><i class="fas fa-angle-left mr-3 text-3"></i> BACK TO HOMEPAGE</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection