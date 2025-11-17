<!-- Vendor scripts plugins -->
<script src="{{ Theme::url('js/plugins.js') }}"></script>
<!-- Scripts application -->
<script src="{{ Theme::url('js/script.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'sHost': '{{ \Domain::getSelected('host') }}',
            'sLocale': '{{ \Domain::getLocale() }}'
        }
    });
</script>
<script src="{{ Theme::url('client.js') }}?v=1"></script>

@include('parts.alerts-toastr')

<form action="#" class="hidden" method="POST" id="js-action-form">
    @csrf
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="destination" value="{{ Request::fullUrl() }}" class="js-destination-val">
</form>

@stack('scripts')
{!! \Variable::getArray('site.asset_end_body', '', \Domain::getId()) !!}
</body>
</html>
