@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
  @php
    $user  = (array) session('auth.user', []);
    $roles = (array) session('auth.roles', []);
    $isAdmin = in_array('admin', $roles, true);
  @endphp

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Dashboard</h1>
    <span class="badge bg-secondary">{{ implode(' · ', $roles) ?: 'no-roles' }}</span>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h2 class="h5">Welcome back</h2>
      <p class="text-muted mb-0">
        @if(!empty($user))
          You are signed in as <strong>{{ $user['name'] ?? $user['email'] ?? 'User' }}</strong>.
        @else
          You are not signed in.
        @endif
      </p>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h3 class="h6 text-uppercase text-muted mb-3">Your profile</h3>
          <dl class="row mb-0">
            <dt class="col-4">ID</dt>
            <dd class="col-8">{{ $user['id'] ?? '—' }}</dd>

            <dt class="col-4">Name</dt>
            <dd class="col-8">{{ $user['name'] ?? '—' }}</dd>

            <dt class="col-4">Email</dt>
            <dd class="col-8">{{ $user['email'] ?? '—' }}</dd>
          </dl>
        </div>
      </div>
    </div>

    @if($isAdmin)
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h3 class="h6 text-uppercase text-muted mb-3">Admin tools</h3>
            <ul class="mb-0">
              <li>
                <a href="#" class="text-decoration-none disabled" aria-disabled="true">Manage users (coming soon)</a>
              </li>
              <li>
                <a href="#" class="text-decoration-none disabled" aria-disabled="true">Site settings (coming soon)</a>
              </li>
              <li><a href="#" class="text-decoration-none disabled" aria-disabled="true">Logs (coming soon)</a></li>
            </ul>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection
