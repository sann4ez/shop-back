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

        <!-- Go to the bottom of the page to change settings and map location. -->
        {{--<div id="googlemaps" class="google-map"></div>--}}

        <section class="section">
            <div class="container">
                <div class="row text-center">
                    <div class="col">
                        <h2 class="font-weight-bold appear-animation" data-appear-animation="fadeInUpShorter">Contact Us</h2>
                        <p class="lead appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
                            We understand the customer always comes first, so if you have any questions about our store or any of our products please don't hesitate to contact us with the form below.
                            If you experience any issues, email us at support@sleepgo.top.</p>
                    </div>
                </div>
                <div class="row pt-5">
                    <div class="col-lg-12 appear-animation" data-appear-animation="fadeInRightShorter">
                        <form class=" form-style-2" action="{{ route('incomings.store') }}" method="POST">
                            @honeypot
                            @csrf
                            <input type="hidden" name="type" value="contacts">
                            <div class="contact-form-success alert alert-success d-none">
                                <strong>Success!</strong> Your message has been sent to us.
                            </div>
                            <div class="contact-form-error alert alert-danger d-none">
                                <strong>Error!</strong> There was an error sending your message.
                                <span class="mail-error-message d-block"></span>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" value="{{ old('name') }}" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" placeholder="Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="email" value="{{ old('email') }}" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email" placeholder="E-mail" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <input type="text" value="{{ old('subject') }}" data-msg-required="Please enter the subject." maxlength="100" class="form-control" name="subject" id="subject" placeholder="Subject" >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <textarea maxlength="5000" data-msg-required="Please enter your message." rows="5" class="form-control" name="message" id="message" placeholder="Message" required>{{ old('message') }}</textarea>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col">
                                    <input type="submit" value="SEND MESSAGE" class="btn btn-primary btn-rounded btn-4 font-weight-semibold text-0" data-loading-text="Loading...">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@push('scripts')
    {{--<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script>

        /*
        Map Settings

            Find the Latitude and Longitude of your address:
                - http://universimmedia.pagesperso-orange.fr/geo/loc.htm
                - http://www.findlatitudeandlongitude.com/find-address-from-latitude-and-longitude/

        */
        (function( $ ) {

            'use strict';

            // Map Markers
            var mapMarkers = [{
                address: "217 Summit Boulevard, Birmingham, AL 35243",
                html: "<strong>Alabama Office</strong><br>217 Summit Boulevard, Birmingham, AL 35243",
                icon: {
                    image: "img/pin.png",
                    iconsize: [26, 46],
                    iconanchor: [12, 46]
                }
            },{
                address: "645 E. Shaw Avenue, Fresno, CA 93710",
                html: "<strong>California Office</strong><br>645 E. Shaw Avenue, Fresno, CA 93710",
                icon: {
                    image: "img/pin.png",
                    iconsize: [26, 46],
                    iconanchor: [12, 46]
                }
            },{
                address: "New York, NY 10017",
                html: "<strong>New York Office</strong><br>New York, NY 10017",
                icon: {
                    image: "img/pin.png",
                    iconsize: [26, 46],
                    iconanchor: [12, 46]
                }
            }];

            // Map Initial Location
            var initLatitude = 40.75198;
            var initLongitude = -73.96978;

            // Map Extended Settings
            var mapSettings = {
                controls: {
                    panControl: true,
                    zoomControl: true,
                    mapTypeControl: true,
                    scaleControl: true,
                    streetViewControl: true,
                    overviewMapControl: true
                },
                scrollwheel: false,
                markers: mapMarkers,
                latitude: initLatitude,
                longitude: initLongitude,
                zoom: 4
            };

            var map = $('#googlemaps').gMap(mapSettings);

            var mapRef = $('#googlemaps').data('gMap.reference');

            // Styles from https://snazzymaps.com/
            var styles = [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}];

            var styledMap = new google.maps.StyledMapType(styles, {
                name: 'Styled Map'
            });

            mapRef.mapTypes.set('map_style', styledMap);
            mapRef.setMapTypeId('map_style');

            // Redraw Map On Resize
            $(window).afterResize(function(){
                $('#googlemaps').gMap(mapSettings);
                var mapRef = $('#googlemaps').data('gMap.reference');

                mapRef.mapTypes.set('map_style', styledMap);
                mapRef.setMapTypeId('map_style');
            });

        }).apply( this, [ jQuery ]);

    </script>
    --}}
@endpush
