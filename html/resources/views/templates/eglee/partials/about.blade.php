<!-- Main Wrapper -->
<div id="main-wrapper">
  <!-- acerca -->
  <section id="acerca" class="">
    <div class="container">
      <div class="row">
        <h2 class="section-title text-center">
        <span class="section-title-border wow pulse red"
              data-wow-duration="1s"
              data-wow-delay="1s">{{ safe_html(config('constants.TEXT_ESPECIALIDAD')) }}</span>
        </h2>
        <div class="section-info col-md-10 col-md-offset-1 text-center wow fadeInDown">
          <h3 class="sub-title-lg red">{!! safe_html(config('constants.TEXT_WELCOME')) !!}</h3>
        </div>
      </div>
    </div>
    <!-- about-listing -->
    <div id="about-listing">
      <div class="container">
        <div class="row responsive-text-align-3-cols">
          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 listing-wrapper wow fadeInLeft animated"
               data-wow-delay="0.5s">
            <div class="circle-point"></div>
            <div class="post-list post-thinkers">
              <img src="{{ asset('templates/default/img/empanada.png') }}"
                   alt="{{ safe_html(config('constants.TITLE_EMPANADAS')) }}"
                   class="img-responsive image-centered"/>
              <h2 class="single-title red">{{ safe_html(config('constants.TITLE_EMPANADAS')) }}</h2>
              <p>{{ safe_html(config('constants.DESCRIPTION_EMPANADAS')) }}</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 listing-wrapper wow fadeInLeft animated"
               data-wow-delay="1s">
            <div class="circle-point"></div>
            <div class="post-list post-dremers">
              <img src="{{ asset('templates/default/img/arepa.png') }}"
                   alt="{{ config('constants.TEXTO_AREPAS') }}"
                   class="img-responsive image-centered"/>
              <h2 class="single-title red">{{ config('constants.TEXTO_AREPAS') }}</h2>
              <p>{{ config('constants.DESCRIPTION_AREPAS') }}</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 listing-wrapper-last wow fadeInLeft animated"
               data-wow-delay="1.5s">
            <div class="post-list post-researchers">
              <img src="{{ asset('templates/default/img/almuerzos.png') }}"
                   alt="{{ config('constants.TITLE_ALMUERZOS') }}"
                   class="img-responsive image-centered"/>
              <h2 class="single-title red">{{ config('constants.TITLE_ALMUERZOS') }}</h2>
              <p>{{ config('constants.DESCRIPTION_ALMUERZOS') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- / acerca -->
</div>
<!-- / Main Wrapper -->
