<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $page['meta_title'] ?? $page['title'] ?? config('app.name') }}</title>
  @if(!empty($page['meta_description']))
    <meta name="description" content="{{ $page['meta_description'] }}">
  @endif

  {{-- Theme assets (no bundler): --}}
  <link rel="stylesheet" href="{{ asset('templates/'.($template->slug ?? 'default').'/css/app.css') }}">
</head>
<body>
<header>
  {{-- Example header using theme settings --}}
  @php $brand = $settings['branding'] ?? []; @endphp
  <div class="container">
    @if(!empty($brand['logo']))
      <img src="{{ $brand['logo'] }}" alt="Logo" style="height:48px;">
    @else
      <strong>{{ $tenant->name ?? config('app.name') }}</strong>
    @endif
  </div>
  @php $menu = $settings['header']['menu'] ?? []; @endphp
  <nav>
    @foreach($menu as $item)
      <a href="{{ $item['href'] ?? '#' }}">{{ $item['label'] ?? 'Link' }}</a>
    @endforeach
  </nav>
</header>

<main class="container">
  @yield('content')
</main>

<script src="{{ asset('templates/'.($template->slug ?? 'default').'/js/app.js') }}" defer></script>
</body>
</html>
