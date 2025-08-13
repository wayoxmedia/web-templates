<!-- logo -->
<div class="logo-wrapper">
  <h1 class="logo">
    <span style="text-align: center;">
      <img src="{{ asset('templates/default/img/logo.png') }}"
           class="logo-resize"
           alt="{{ config('constants.SITE_NAME') }}"/>
    </span>
  </h1>
</div>
<!-- /logo -->

<!-- navigation icon -->
<div class="navmenu-open">
  <a href="javascript:void(0);" id="trigger-navbar"><span class="icon_menu"></span></a>
</div>
<!-- / navigation icon -->

<!-- home -->
<section id="home" class="">
  <div class="row">
    <div class="col-xs-12">
      <img src="{{ asset('templates/default/img/top.jpg') }}" alt="arepas" class="responsive"/>
      <div class="btn-home-container">
        <input type="button"
               value="{{ config('constants.ORDER_NOW') }}"
               class="btn-cta btn-cta-white" onclick="window.location.href='#contacto'">
      </div>
    </div>
  </div>
</section>
<!-- /home -->
