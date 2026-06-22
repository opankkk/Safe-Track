@section('title', 'Login | Sistem HSE')
@section('body-class', 'hold-transition login-page auth-page')
@section('is-auth', true)

<div>

  <div class="auth-bg"></div>

  <div class="login-box">

    <div class="card card-outline card-primary shadow auth-card">

      <div class="card-header text-center border-0 pb-0">
        <h1 class="h5 mb-1 font-weight-bold">Selamat Datang Kembali</h1>
        <p class="text-muted mb-0">Silahkan Login Untuk Melanjutkan</p>
      </div>

      <div class="card-body login-card-body pt-4">

        @if (session()->has('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form wire:submit.prevent="login">

          <div class="input-group mb-3">
            <input type="email" wire:model="email" class="form-control" placeholder="Email" autocomplete="email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>

          @error('email')
          <span class="text-danger small mt-n2 mb-3 d-block">{{ $message }}</span>
          @enderror


          <div class="input-group mb-2">
            <input type="password" wire:model="password" class="form-control" placeholder="Password" autocomplete="current-password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          @error('password')
          <span class="text-danger small mb-3 d-block">{{ $message }}</span>
          @enderror


          <div class="d-flex justify-content-between align-items-center mb-3">

            <div class="icheck-primary mb-0">
              <input type="checkbox" wire:model="remember" id="remember">
              <label for="remember" class="mb-0">Remember Me</label>
            </div>

            <a href="{{ route('user.forgotpw') }}" class="small text-primary font-weight-semibold">
              Lupa password?
            </a>

          </div>


          <button type="submit" class="btn btn-primary btn-block btn-lg auth-btn">
            <span class="fas fa-sign-in-alt mr-2"></span>
            Login
          </button>

        </form>

        <div class="mt-4 text-center text-muted small">
          <span class="d-inline-flex align-items-center">
            <span class="fas fa-shield-alt mr-2"></span>
            Sistem HSE • Secure Access
          </span>
        </div>

      </div>

    </div>

  </div>

</div>