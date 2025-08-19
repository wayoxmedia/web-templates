@extends($layout ?? 'templates.default.layouts.app')

@section('title', 'Temporary issue')

@section('content')
  <div class="mx-auto max-w-xl py-16 text-center">
    <h1 class="mb-4 text-2xl font-semibold">{{ 'Something went wrong' }}</h1>
    <p class="mb-6 text-gray-700">
      {{ $message ?? 'Please try again in a moment.' }}
    </p>
    <a href="{{ url()->current() }}" class="rounded bg-black px-4 py-2 text-white">
      {{ 'Retry' }}
    </a>
  </div>
@endsection
