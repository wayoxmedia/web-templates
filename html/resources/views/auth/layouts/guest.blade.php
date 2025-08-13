<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Sign in')</title>

  <!-- Styles: Bootstrap via CDN -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">

  <style>
    body { background-color: #f0f2f5; }
    .container-narrow { max-width: 720px; }
    .auth-wrapper { min-height: 100vh; display: grid; place-items: center; }
    .brand { font-weight: 700; letter-spacing: .2px; }
  </style>

  @stack('head')
</head>
<body>
<div class="auth-wrapper">
  <main class="container container-narrow">
    <div class="text-center mb-4">
      <a href="{{ url('/') }}" class="text-decoration-none text-dark">
        <span class="brand">MyStore</span>
      </a>
    </div>

    {{-- Flash / errors --}}
    @if (session('status'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="fw-semibold mb-1">Please fix the following errors:</div>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li class="small">{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @yield('content')

    <p class="text-center text-muted small mt-4 mb-0">
      &copy; {{ date('Y') }} MyStore
    </p>
  </main>
</div>

<!-- Scripts: Bootstrap via CDN -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-VjEeSF2cArB+9mGk4rVw9f3rE+7v2jK8ALHq5X9J3XbID0Yk4K+f4S9JzJ8x0m9E"
  crossorigin="anonymous"></script>

@stack('scripts')
</body>
</html>
