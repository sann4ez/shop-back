<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <meta http-equiv="X-UA-Compatible" content="ie=edge" >
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-TileColor" content="#da532c" >
    <meta name="theme-color" content="#ffffff" >
    @php
        \Seo::setGroup(\Domain::getId() ?: '')
            ->setPath(get_path_without_host(urldecode(\Request::fullUrl()), true));

        if ($seoPath = \Seo::getSeopath()) {
            \Seo::setTags(array_merge($seoPath->tags, [
                'robots' => build_robots($seoPath->tags['robotses'] ?? [])
            ]));
        } elseif (request()->has('sort') || request()->has('page')) {
            \Seo::setTags(['robots' => 'noindex, follow']);
        } elseif (\Variable::getArray('seo.close', false, \Domain::getId()) && (request()->routeIs('catalog.category') || request()->routeIs('catalog.search') || request()->routeIs('catalog.brands.show') || request()->routeIs('promotions.show')) && request()->query()) {
            \Seo::setTags(['robots' => 'noindex, follow']);
        } elseif (\Variable::getArray('seo.close', false, \Domain::getId())) {
            \Seo::setTags(['robots' => 'noindex, nofollow']);
        }
    @endphp

    {!!
        \Seo::setDefault([
                'og_site_name' => config('app.name'),
                'og_url' => URL::full(),
                'og_locale' => app()->getLocale(),
                'og_image' => \Variable::getArray('seo.og_image', '', \Domain::getId()),
                'canonical' => request()->url(),
                //'robots' => '' // get from robotses (for model)
            ])->renderHtml()
     !!}
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="{{ Theme::url('img/apple-touch-icon.png') }}"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="{{ Theme::url('img/favicon-32x32.png') }}"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ Theme::url('img/favicon-16x16.png') }}"
    >
    <link rel="manifest" href="{{ Theme::url('img/site.webmanifest') }}" >

    <!-- Vendor styles plugins -->
{{--    <link rel="stylesheet" href="{{ Theme::url('css/plugins.css') }}">--}}
    <!-- Styles application -->
    <link rel="stylesheet" href="{{ Theme::url('css/style.css') }}?v=4" >
    <link rel="stylesheet" href="{{ Theme::url('css/custom-style.min.css') }}" >

    {!! \Variable::getArray('site.asset_in_head', '', \Domain::getId()) !!}
</head>
<body class="{{ $bodyClass ?? '' }}">
{!! \Variable::getArray('site.asset_start_body', '', \Domain::getId()) !!}
