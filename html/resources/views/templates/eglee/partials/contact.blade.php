<!-- contacto -->
<section id="contacto" class="">
  <div class="row bgRed">
    <div class="col-xs-9 text-center">
      <div class="row">
        <div class="col-xs-12">
          <div class="">
            <img src="{{ asset('templates/default/img/horizontal_curves.png') }}"
                 alt="Curved Lines"
                 class="img-responsive image-centered"/>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="">
            <h2 class="section-title white">{{ config('constants.TEXTO_PONTE_EN_CONTACTO') }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-3">
      <img src="{{ asset('templates/default/img/3WhiteDiag.png') }}"
           alt="3 Lines White Diagonal"
           class="img-responsive image-centered"/>
    </div>
  </div>
  <form class="" id="contact_form">
    <div class="row bgRed">
      <p></p>
      <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"></div>
      <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
        <div class="form-elements-inner-padding">
          <label for="iptName" class="form-label"></label>
          <input type="text"
                 name="iptName"
                 maxlength="30"
                 placeholder="{{ config('constants.TXT_YOUR_NAME') }}"
                 class="form-control"
                 id="iptName">
        </div>
      </div>
      <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
        <div class="form-elements-inner-padding">
          <label for="iptEmail" class="form-label"></label>
          <input type="email"
                 name="iptEmail"
                 maxlength="50"
                 placeholder="{{ config('constants.TXT_YOUR_EMAIL') }}"
                 class="form-control"
                 id="iptEmail">
        </div>
      </div>
      <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"></div>
    </div>
    <div class="row bgRed">
      <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"></div>
      <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
        <div class="form-elements-inner-padding">
          <label for="iptMessage" class="form-label"></label>
          <textarea name="iptMessage"
                    id="iptMessage"
                    class="form-control"
                    placeholder="{{ config('constants.TXT_YOUR_MSG') }}"
                    cols="30"
                    maxlength="500"
                    rows="10"></textarea>
        </div>
      </div>
      <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"></div>
    </div>
    <div class="row bgRed"><p class="bgRed">&nbsp;</p></div>
    <div class="row bgRed">
      <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"></div>
      <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
        <div class="form-elements-inner-padding">
          <button type="submit"
                  class="btn btn-yellow red btn-lg btn-block"
                  id="btnSubmitContact">{{ config('constants.BTN_SEND') }}
          </button>
        </div>
      </div>
    </div>
    <div class="row bgRed"><p>&nbsp;</p></div>
    <div class="row bgRed">
      <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"></div>
      <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
        <div class="form-elements-inner-padding">
          <div class="hidden alert" role="alert" id="contactFormResponse"></div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"></div>
    </div>
  </form>
  <div class="row bgRed">
    <div class="col-xs-12"><p>&nbsp;</p></div>
  </div>
</section>
