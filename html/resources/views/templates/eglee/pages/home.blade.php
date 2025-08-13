@extends('templates.eglee.layouts.app')

@section('title', 'Eglee\'s Gourmet')
@section('meta_description', 'Comida Venezolana en Chicago, hecha en casa y con el sabor de la abuela.')
@section('keywords', 'eglee, gourmet, gourmet food, gourmet restaurant, gourmet delivery, arepas, empanadas, comida venezolana, comida latina, comida venezolana en chicago')

@section('content')
  @include('templates.eglee.partials.menu')
  @include('templates.eglee.partials.header')
  @include('templates.eglee.partials.about')
  @include('templates.eglee.partials.combos')
  @include('templates.eglee.partials.almuerzos')
  @include('templates.eglee.partials.arepas')
  @include('templates.eglee.partials.contact')
  @include('templates.eglee.partials.subscribe')
@endsection
