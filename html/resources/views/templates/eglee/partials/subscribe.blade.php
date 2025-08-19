<!-- subscribe -->
<section id="subscribe" class="">
  <div class="row bgTan">
    <div class="col-xs-12">
      <div class="spacer40">&nbsp;</div>
    </div>
  </div>
  <div class="row bgTan">
    <div class="col-xs-12">
      <div class="">
        <img src="{{ asset('templates/default/img/eglita_ok.png') }}" alt="subscribe"
             class="img-responsive image-centered"/>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row bgTan">
      <h2 class="section-title text-center">
        <span class="section-title-border wow pulse red"
              data-wow-duration="1s"
              data-wow-delay="1s">{{ config('constants.TEXTO_SUBSCRIBE') }}
        </span>
      </h2>
      <div class="section-info col-md-8 col-md-offset-2 text-center wow fadeInDown">
        <h3 class="sub-title-lg red">{{ config('constants.TEXTO_SUBSCRIBE_PARR') }}</h3>
      </div>
    </div>
  </div>
  <div class="row bgTan">
    <form action="" class="" id="subscribe_form">
      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-2">&nbsp;</div>
      <div class="col-xs-10 col-sm-10 col-md-10 col-lg-8">
        <div class="row rounded bgRed subscribe-height">
          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center subscribe-container">
            <label for="selAddressType"></label>
            <select id="selAddressType"
                    name="selAddressType"
                    class="form-control bgRed subscribe-select-width subscribe-select-alignment">
              <option value="e">{{ config('constants.TEXTO_EMAIL') }}</option>
              <option value="p">{{ config('constants.TEXTO_TELF') }}</option>
            </select>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 subscribe-container">
            <label for="iptAddress"></label>
            <input type="text"
                   id="iptAddress"
                   name="iptAddress"
                   placeholder="{{ config('constants.TEXTO_SU_EMAIL') }}"
                   class="form-control bgRed input-position-absolute"/>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 subscribe-container">
            <div class="bgYellow rounded subscribe-button-container">
              <input type="submit"
                     id="btnSubmitSubscribe"
                     name="btnSubmitSubscribe"
                     value="{{ config('constants.TEXTO_SUBSCRIBE_ME') }}"
                     class="btn subscribe-button"/>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-2">&nbsp;</div>
    </form>
  </div>
  <div class="row bgTan">
    <div class="col-xs-12">
      <div class="spacer40">&nbsp;</div>
    </div>
  </div>
  <div class="row bgTan">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3"></div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <div class="form-elements-inner-padding">
        <div class="hidden alert text-center alert-dismissable" role="alert" id="submitFormResponse"></div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3"></div>
  </div>
  <div class="row bgTan">
    <div class="col-xs-12">
      <div class="spacer40">&nbsp;</div>
    </div>
  </div>
</section>
<!-- /subscribe -->
