<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ Theme::url('img/favicon.ico') }}">
    {!!
        \Seo::setGroup(\Domain::getGroup())
            ->setDefault([
                'og_site_name' => config('app.name'),
                'og_url' => URL::full(),
                'og_locale' => app()->getLocale(),
                'og_image' => \Domain::getSelected()?->getFirstMediaUrl('image', 'og_image'),
            ])->renderHtml()
    !!}
    <!-- Vendor styles plugins -->
    <link rel="stylesheet" href="{{ Theme::url('css/plugins.css') }}">
    <!-- Styles application -->
    <link rel="stylesheet" href="{{ Theme::url('fonts/material-icons/index.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('fonts/Lato/fonts.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('fonts/roboto/stylesheet.css') }}">
    {{--<link rel="stylesheet" href="{{ Theme::url('css/style.min.css') }}">--}}
    <link rel="stylesheet" href="{{ Theme::url('css/style.min.css') }}">
</head>
<body>
