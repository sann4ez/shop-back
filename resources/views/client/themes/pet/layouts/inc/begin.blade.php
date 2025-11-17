<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="shop">
<head>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!!
        \Seo::setGroup(\Domain::getGroup())
            ->setDefault([
                'og_site_name' => config('app.name'),
                'og_url' => URL::full(),
                'og_locale' => app()->getLocale(),
            ])->renderHtml()
     !!}

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,300,400,500,600,700,900%7COpen+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ Theme::url('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/linear-icons/css/linear-icons.min.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/owl.carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/owl.carousel/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/magnific-popup/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/toastr/toastr.min.css') }}">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ Theme::url('css/theme.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('css/theme-elements.css') }}">

    <!-- Current Page CSS -->
    <link rel="stylesheet" href="{{ Theme::url('vendor/rs-plugin/css/settings.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/rs-plugin/css/layers.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('vendor/rs-plugin/css/navigation.css') }}">

    <!-- Skin CSS -->
    <link rel="stylesheet" href="{{ Theme::url('css/skins/default.css') }}">

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ Theme::url('css/custom.css') }}">

    <!-- Head Libs -->
    <script src="{{ Theme::url('vendor/modernizr/modernizr.min.js') }}"></script>
    @stack('styles')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! \Variable::getArray('site.asset_in_head', '', \Domain::getGroup()) !!}
</head>
<body>
{!! \Variable::getArray('site.asset_start_body', '', \Domain::getGroup()) !!}

@if(session()->get('prevent_login'))
    <input type="button" data-url="{{ route('login.back') }}" class="btn btn-primary btn-rounded btn-4 font-weight-semibold text-0 js-action-form" data-method="POST" value="< Back Login"  style="position: fixed; z-index: 10000; top: 15px; right: 15px; opacity: 0.5" data-confirm="{{ trans('lte::main.Login') }}?">
@endif