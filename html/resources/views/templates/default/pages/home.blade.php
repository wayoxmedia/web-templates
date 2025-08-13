@extends('theme::layouts.app')

@section('content')
  {{-- Simple hero block --}}
  @php $hero = $content['hero'] ?? []; @endphp

  <section class="hero">
    <h1>{{ $page['title'] ?? ($hero['title'] ?? 'Welcome') }}</h1>
    @if(!empty($hero['subtitle']))
      <p class="subtitle">{{ $hero['subtitle'] }}</p>
    @endif
    @if(!empty($hero['cta']))
      <p><a class="btn" href="{{ $hero['cta']['href'] ?? '#' }}">{{ $hero['cta']['label'] ?? 'Learn more' }}</a></p>
    @endif
  </section>

  {{-- Features list --}}
  @php $features = $content['features'] ?? []; @endphp
  @if(!empty($features))
    <section class="features">
      <ul>
        @foreach($features as $f)
          <li>
            <strong>{{ $f['title'] ?? 'Feature' }}</strong><br>
            <small>{{ $f['text'] ?? '' }}</small>
          </li>
        @endforeach
      </ul>
    </section>
  @endif
@endsection
