<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {!!
        \Seo::setGroup(\Domain::getGroup())
            ->setDefault([
                'og_site_name' => config('app.name'),
                'og_url' => URL::full(),
                'og_locale' => app()->getLocale(),
            ])->renderHtml()
     !!}
    <link rel="stylesheet" href="{{ Theme::url('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ Theme::url('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! \Variable::getArray('site.asset_in_head', '', \Domain::getGroup()) !!}
</head>
<body>
{!! \Variable::getArray('site.asset_start_body', '', \Domain::getGroup()) !!}
@yield('content')
<script src="{{ Theme::url('js/plugins.js') }}"></script>
<script src="{{ Theme::url('js/script.js') }}"></script>
@stack('scripts')
{!! \Variable::getArray('site.asset_end_body', '', \Domain::getGroup()) !!}
</body>
</html>
