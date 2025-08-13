<!DOCTYPE html>
<html class="no-js" lang="en-US">
<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <meta name="description" content="@yield('meta_description')">
  <meta name="keywords" content="@yield('meta_keywords')">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  @include('templates.eglee.partials.head')
</head>
<body>
@include('templates.eglee.partials.preloader')
<!-- wrapper -->
<div id="wrapper" class="light-patterns">
  @yield('content')
  @include('templates.eglee.partials.footer')
  <!-- /wrapper -->
</div>
@include('templates.eglee.partials.footer-scripts')
</body>
</html>
