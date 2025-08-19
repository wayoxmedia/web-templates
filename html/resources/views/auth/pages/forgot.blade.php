@extends('auth.layouts.guest')

@section('title', 'Admin Login')

@section('SITE_URL', 'Eglee Admin')

@section('SITE_NAME', 'Eglee Admin')

@push('head')
  <meta name="robots" content="noindex,nofollow">
@endpush

@section('content')
  {{--  @include('auth.partials.forgotForm')--}}
@endsection

@push('scripts')
  <script type="module" src="{{ asset('admin/js/forgot.js') }}"></script>
@endpush
