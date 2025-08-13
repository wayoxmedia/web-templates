<!-- arepas -->
<section id="arepas" class="arepa-combos-container">
  <div class="row">
    <div class="col">
      <img src="{{ asset('templates/default/img/arepas_combos.jpg') }}" alt="arepas combos" class="responsive"/>
      <div class="btn-arepas-container">
        <h1 class="btn-x-small-red">{{ config('constants.TEXTO_COMBO') }}<br>
          <span class="white">{{ config('constants.TEXTO_AREPAS') }}</span>
        </h1>
        <p class="red txt-x-small" style="line-height: 1.2;">
          {{ config('constants.PARRAFO_AREPAS') }}
        </p>
        <input type="button"
               value="{{ config('constants.TXT_ORDER_NOW') }}"
               class="btn-cta btn-cta-red" onclick="window.location.href='#contacto'">
      </div>
    </div>
  </div>
</section>
<!-- /arepas -->
