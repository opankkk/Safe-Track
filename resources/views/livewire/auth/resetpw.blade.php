<div>
@section('title', 'Reset Password | Sistem HSE')
@section('body-class', 'hold-transition login-page auth-page')
@section('is-auth', true)
<div class="auth-bg"></div>

  <div class="login-box">
    <div class="card card-outline card-primary shadow auth-card">
      <div class="card-header text-center border-0 pb-0">
        <h1 class="h5 mb-1 font-weight-bold">Reset Password Baru</h1>
        <p class="text-muted mb-0">Silahkan Masukkan Password Baru Anda Di Bawah Ini</p>
      </div>

      <div class="card-body login-card-body pt-4">
        <form wire:submit.prevent="resetPassword">
          <input type="hidden" wire:model="token">
          
          <div class="input-group mb-3">
            <input type="email" wire:model="email" class="form-control" placeholder="Email" readonly>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          @error('email') <span class="text-danger small mt-n2 mb-3 d-block">{{ $message }}</span> @enderror

          <div class="input-group mb-3">
            <input type="password" wire:model="password" class="form-control" placeholder="Password Baru">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          @error('password') <span class="text-danger small mt-n2 mb-3 d-block">{{ $message }}</span> @enderror

          <div class="input-group mb-3">
            <input type="password" wire:model="password_confirmation" class="form-control" placeholder="Konfirmasi Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block btn-lg auth-btn">
            <span class="fas fa-save mr-2"></span>
            Simpan Password Baru
          </button>
        </form>

        <div class="mt-3 text-center">
          <a href="{{ route('user.login') }}" class="small text-primary font-weight-semibold">
            <span class="fas fa-arrow-left mr-1"></span> Kembali Ke Login
          </a>
        </div>

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
