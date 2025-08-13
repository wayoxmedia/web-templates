<!-- Scripts -->
<script>
  const msaConfig = {
    siteName: <?= json_encode(defined('SITE_NAME') ? SITE_NAME : '') ?>,
    supportEmail: <?= json_encode(defined('SUPPORT_EMAIL') ? SUPPORT_EMAIL : '') ?>,
    apiUrl: <?= json_encode(defined('API_URL') ? API_URL : '') ?>,
    local_env: <?= json_encode(defined('LOCAL_ENV') && LOCAL_ENV) ?>
  };
</script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/bootstrap.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/classie.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/respond.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/jquery.counterup.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/waypoints.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/smoothscroll.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/jquery.backstretch.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/wow.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/detectmobilebrowser.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/owl.carousel.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/imagesloaded.pkgd.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/masonry.pkgd.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/cbpGridGallery.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/vendor/styleswitch.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('templates/default/js/script.js') }}"></script>
