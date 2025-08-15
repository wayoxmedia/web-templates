<!DOCTYPE html>
<html lang="en">
<head>
  {{-- Meta --}}
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'My Store Panel')</title>

  {{-- Google Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

  {{-- CSS --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('templates/default/img/favicon.svg') }}" />
  <link rel="stylesheet" href="{{ asset('admin/css/styles.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}" />

  {{-- JS --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
          integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
          crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
          crossorigin="anonymous"></script>
  <script type="module" src="{{ asset('admin/js/global.js') }}"></script>

  @stack('head')
</head>
<body>
{{-- Body Wrapper --}}
<div class="page-wrapper"
     id="main-wrapper"
     data-layout="vertical"
     data-navbarbg="skin6"
     data-sidebartype="full"
     data-sidebar-position="fixed"
     data-header-position="fixed">
  <div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3">
          <div class="card mb-0">
            <div class="card-body">
              <a href="@yield('SITE_URL', 'My Store Panel')"
                 class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="{{ asset('templates/default/img/eglita_ok.png') }}" alt="">
              </a>
              <p class="text-center">@yield('SITE_NAME', 'My Store Panel')</p>
              @yield('content')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="global-spinner-overlay" style="display: none;">
  <div id="spinner">
    <img src="{{ asset('admin/img/loading.gif') }}"
         alt="Loading..."
         class="spinner-img">
    <p class="text-loading">Logging in...</p>
  </div>
</div>
@stack('scripts')
</body>
</html>
