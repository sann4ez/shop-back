@include('layouts.inc.begin')
@include('layouts.inc.header')
@yield('content')
@include('layouts.inc.footer-simple')
@include('layouts.inc.end')

@stack('modals')
