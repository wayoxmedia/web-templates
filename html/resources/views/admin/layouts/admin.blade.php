<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Admin')</title>

  <!-- Styles: Bootstrap via CDN (no bundler) -->
  <!-- NOTE: Replace CDN versions if you maintain a strict CSP or need pinning -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">

  <style>
    /* Minimal admin layout helpers */
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: 700; }
    .container-narrow { max-width: 960px; }
    .mt-navbar { margin-top: 1rem; }
  </style>

  @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container container-narrow">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">MyStore Admin</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav"
            aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="adminNav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        {{-- Example future links:
        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Users</a></li>
        --}}
      </ul>

      <div class="d-flex align-items-center gap-2">
        @php
          $authUser  = (array) session('auth.user', []);
          $userName  = $authUser['name'] ?? $authUser['email'] ?? 'User';
        @endphp

        @if(!empty($authUser))
          <span class="text-white-50 small me-2">Signed in as</span>
          <span class="text-white fw-semibold me-3">{{ $userName }}</span>

          <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light">
              Logout
            </button>
          </form>
        @else
          <a class="btn btn-sm btn-outline-light" href="{{ route('admin.login') }}">Login</a>
        @endif
      </div>
    </div>
  </div>
</nav>

<main class="container container-narrow mt-navbar">
  {{-- Flash status message --}}
  @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('status') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- Global validation errors (fallback) --}}
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
</main>

<footer class="py-4">
  <div class="container container-narrow">
    <div class="text-center text-muted small">
      &copy; {{ date('Y') }} MyStore Admin
    </div>
  </div>
</footer>

<!-- Scripts: Bootstrap via CDN -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-VjEeSF2cArB+9mGk4rVw9f3rE+7v2jK8ALHq5X9J3XbID0Yk4K+f4S9JzJ8x0m9E"
  crossorigin="anonymous"></script>

@stack('scripts')
</body>
</html>
