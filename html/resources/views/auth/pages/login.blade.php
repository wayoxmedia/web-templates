@extends('auth.layouts.guest')

@section('title', 'Admin Login')

@section('SITE_URL', 'Eglee Admin')

@section('SITE_NAME', 'Eglee Admin')

@push('head')
  <meta name="robots" content="noindex,nofollow">
  <style>
    .tooltip[data-popper-placement^="top"] {
      margin-bottom: 20px !important;
    }
    .min-h-50 {
      min-height: 50px !important;
    }
  </style>
@endpush

@section('content')
  @include('auth.partials.loginForm')
@endsection

@push('scripts')
  <script type="module" src="{{ asset('auth/js/login.js') }}"></script>
@endpush
