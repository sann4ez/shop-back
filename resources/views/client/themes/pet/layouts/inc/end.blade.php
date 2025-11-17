<form action="#" class="hidden" method="POST" id="js-action-form">
    @csrf
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="destination" value="{{ Request::fullUrl() }}" class="js-destination-val">
</form>


<!-- Vendor -->
<script src="{{ Theme::url('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ Theme::url('vendor/jquery.appear/jquery.appear.min.js') }}"></script>
<script src="{{ Theme::url('vendor/jquery.easing/jquery.easing.min.js') }}"></script>
<script src="{{ Theme::url('vendor/jquery.cookie/jquery.cookie.js') }}"></script>
<script src="{{ Theme::url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ Theme::url('vendor/common/common.min.js') }}"></script>
<script src="{{ Theme::url('vendor/jquery.validation/jquery.validate.min.js') }}"></script>
<script src="{{ Theme::url('vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
<script src="{{ Theme::url('vendor/jquery.gmap/jquery.gmap.min.js') }}"></script>
<script src="{{ Theme::url('vendor/jquery.lazyload/jquery.lazyload.min.js') }}"></script>
<script src="{{ Theme::url('vendor/isotope/jquery.isotope.min.js') }}"></script>
<script src="{{ Theme::url('vendor/owl.carousel/owl.carousel.min.js') }}"></script>
<script src="{{ Theme::url('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ Theme::url('vendor/vide/jquery.vide.min.js') }}"></script>
<script src="{{ Theme::url('vendor/vivus/vivus.min.js') }}"></script>
<script src="{{ Theme::url('vendor/toastr/toastr.min.js') }}"></script>

<!-- Theme Base, Components and Settings -->
<script src="{{ Theme::url('js/theme.js') }}"></script>

<!-- Current Page Vendor and Views -->
<script src="{{ Theme::url('vendor/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ Theme::url('vendor/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>

<!-- Theme Custom -->
<script src="{{ Theme::url('js/custom.js') }}?v=3"></script>

<!-- Theme Initialization Files -->
<script async src="{{ Theme::url('js/theme.init.js') }}"></script>


<!-- Current Page Vendor and Views: contacts -->
<script src="{{ Theme::url('js/views/view.contact.js') }}"></script>

<!-- Examples: products -->
<script src="{{ Theme::url('js/examples/examples.gallery.js') }}"></script>

@include('parts.alerts-toastr')

<!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information.
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-12345678-1', 'auto');
    ga('send', 'pageview');
</script>
 -->
@stack('scripts')
{!! \Variable::getArray('site.asset_end_body', '', \Domain::getGroup()) !!}
</body>
</html>

