@include('layouts.inc.begin')
        {{--@include('front.layouts.inc.aside')--}}
    <div class="body">
        @include('layouts.inc.header')
        {{--@include('front.parts.alerts')--}}
        @yield('content')
        @include('layouts.inc.footer')
    </div>
@include('layouts.inc.end')

@stack('modals')
