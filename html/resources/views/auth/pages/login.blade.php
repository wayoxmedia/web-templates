@extends('auth.layouts.guest')

@section('title', 'Admin Login')

@section('SITE_URL', 'Eglee Admin')

@section('SITE_NAME', 'Eglee Admin')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h1 class="h4 mb-3">Sign in</h1>
          <p class="text-muted mb-4">Use your admin credentials to access the dashboard.</p>

          <form action="{{ route('admin.login.submit') }}" method="POST" novalidate>
            @csrf

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
              >
              @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input
                  type="password"
                  class="form-control @error('password') is-invalid @enderror"
                  id="password"
                  name="password"
                  required
                  minlength="8"
                  autocomplete="current-password"
                >
                <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Show/Hide password">
                  <span class="d-inline-block" id="toggleIcon">Show</span>
                </button>
                @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-text">Minimum 8 characters.</div>
            </div>

            {{-- Submit --}}
            <div class="d-grid">
              <button type="submit" class="btn btn-primary" id="loginBtn">
                Sign in
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- Optional footer links --}}
      <div class="text-center mt-3">
        <a href="#" class="small text-decoration-none disabled" aria-disabled="true">Forgot password?</a>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Prevent double submissions
      const form = document.querySelector('form[action="{{ route('admin.login.submit') }}"]');
      const btn  = document.getElementById('loginBtn');
      if (form && btn) {
        form.addEventListener('submit', function () {
          btn.setAttribute('disabled', 'disabled');
        });
      }

      // Simple password visibility toggle
      const toggleBtn  = document.getElementById('togglePassword');
      const toggleIcon = document.getElementById('toggleIcon');
      const pwdInput   = document.getElementById('password');

      if (toggleBtn && toggleIcon && pwdInput) {
        toggleBtn.addEventListener('click', function () {
          const isText = pwdInput.getAttribute('type') === 'text';
          pwdInput.setAttribute('type', isText ? 'password' : 'text');
          toggleIcon.textContent = isText ? 'Show' : 'Hide';
        });
      }
    });
  </script>
@endpush
