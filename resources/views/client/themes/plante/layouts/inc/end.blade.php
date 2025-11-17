<!-- Vendor scripts plugins -->
<script src="{{ Theme::url('js/plugins.js') }}"></script>
<!-- Scripts application -->
<script src="{{ Theme::url('js/script.min.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'S-Domain': '{{ \Domain::getSelected('host') }}',
            'S-Locale': '{{ \Domain::getLocale() }}'
        }
    });
</script>
<script src="{{ Theme::url('client.js') }}"></script>

@include('parts.alerts-toastr')

<form class="hidden" method="POST" id="js-action-form">
    @csrf
    <input type="hidden" name="_method" value="POST" autocomplete="off">
    <input type="hidden" name="destination" value="{{ Request::fullUrl() }}" class="js-destination-val" autocomplete="off">
</form>

@stack('scripts')

</body>
</html>
