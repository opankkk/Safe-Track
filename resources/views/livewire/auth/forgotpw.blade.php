<div>
@section('title', 'Lupa Password | Sistem HSE')
@section('body-class', 'hold-transition login-page auth-page')
@section('is-auth', true)
<div class="auth-bg"></div>

<div class="login-box">
  <div class="login-logo mb-3">
    <a href="#" class="text-white">
      <span class="d-inline-block px-3 py-2 rounded shadow-sm auth-brand">
        <b>Sistem</b>HSE
      </span>
    </a>
  </div>

  <div class="card card-outline card-primary shadow auth-card">
    <div class="card-header text-center border-0 pb-0">
      <h1 class="h5 mb-1 font-weight-bold">Reset Password</h1>
      <p class="text-muted mb-0">
        Masukkan email Anda untuk meminta tautan reset password.
      </p>
    </div>

    <div class="card-body login-card-body pt-4">
      <form wire:submit.prevent="sendResetLink">
        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="input-group mb-3">
          <input type="email" wire:model="email" class="form-control" placeholder="Email" autocomplete="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        @error('email') <span class="text-danger small mt-n2 mb-3 d-block">{{ $message }}</span> @enderror

        <button type="submit" class="btn btn-primary btn-block btn-lg auth-btn">
          <span class="fas fa-paper-plane mr-2"></span>
          Request Reset Link
        </button>
      </form>

      <div class="mt-3 text-center">
        <a href="{{ route('user.login') }}" class="small text-primary font-weight-semibold">
          <span class="fas fa-arrow-left mr-1"></span> Kembali ke Login
        </a>
      </div>
    </div>
  </div>
  </div>
</div>
</div>