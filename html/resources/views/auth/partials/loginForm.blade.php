{{--@php(xdebug_break())--}}
<form id="loginForm"
      action="{{ route('admin.login.submit') }}"
      method="POST"
      class="needs-validation"
      novalidate>
  @csrf
  <div class="mb-3">
    <label for="iptEmail"
           class="form-label">Email</label>
    <input type="email"
           class="form-control input-validate @error('email') is-invalid @enderror"
           id="iptEmail"
           name="email"
           value="{{ old('email') }}"
           required
           autocomplete="email"
           autofocus
           aria-describedby="emailHelp">
    <div class="invalid-feedback">
      {{ $errors->first('email') ?: 'Please enter a valid email.' }}
    </div>
  </div>
  <div class="mb-4">
    <label for="iptPassword"
           class="form-label">Password</label>
    <input type="password"
           class="form-control input-validate @error('password') is-invalid @enderror"
           name="password"
           required
           minlength="8"
           autocomplete="current-password"
           id="iptPassword">
    <div class="invalid-feedback">
      {{ $errors->first('password') ?: 'Password is required and minimum 8 characters.' }}
    </div>
  </div>
  <div class="d-flex align-items-center justify-content-between mb-5">
    <div class="form-check">
      <input class="form-check-input primary"
             type="checkbox"
             value=""
             id="chkRememberMe"
             checked>
      <label class="form-check-label text-dark"
             for="chkRememberMe">Remember me</label>
    </div>
    <a class="text-primary fw-bold"
       href="{{ route('admin.forgot') }}">Forgot Password?</a>
  </div>
  <span id="btnWrapper"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        title="Fill all fields to Sign In.">
    <button type="submit"
            id="btnSubmit"
            class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
  </span>
</form>
<div id="error-container" class="min-h-50">
  <div id="login-error"
       class=""
       style="color: red; padding-top: 1em;">
    @if ($errors->has('credentials'))
      {{ $errors->first('credentials') }}
    @endif
  </div>
</div>
