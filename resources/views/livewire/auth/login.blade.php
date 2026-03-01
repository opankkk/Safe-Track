@extends('layouts.app')

@section('title', 'Login | Sistem HSE')
@section('body-class', 'hold-transition login-page auth-page')
@section('is-auth', true)

@section('content')
<div class="auth-bg"></div>

<div class="login-box">
  {{-- Brand --}}
  <div class="login-logo mb-3">
    <a href="#" class="text-white">
      <span class="d-inline-block px-3 py-2 rounded shadow-sm auth-brand">
        <b>Sistem</b>HSE
      </span>
    </a>
  </div>

  <div class="card card-outline card-primary shadow auth-card">
    <div class="card-header text-center border-0 pb-0">
      <h1 class="h5 mb-1 font-weight-bold">Selamat datang kembali</h1>
      <p class="text-muted mb-0">Silakan login untuk melanjutkan</p>
    </div>

    <div class="card-body login-card-body pt-4">
      <form>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" autocomplete="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-2">
          <input type="password" class="form-control" placeholder="Password" autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="icheck-primary mb-0">
            <input type="checkbox" id="remember">
            <label for="remember" class="mb-0">Remember Me</label>
          </div>

          <a href="{{ route('user.forgotpw') }}" class="small text-primary font-weight-semibold">
            Lupa password?
          </a>
        </div>

        <a href="{{ route('hse.manager.dashboard') }}" class="btn btn-primary btn-block btn-lg auth-btn">
          <span class="fas fa-sign-in-alt mr-2"></span>
          Login
        </a>
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

{{-- Styling khusus halaman auth (tetap gaya AdminLTE, hanya dipoles) --}}
@push('styles')
<style>
  /* Halaman auth: background modern tapi tetap simpel */
  .auth-page{
    position: relative;
    min-height: 100vh;
    overflow: hidden;
    background: #0b1220; /* fallback */
  }
  .auth-bg{
    position: absolute;
    inset: 0;
    background:
      radial-gradient(1200px 600px at 20% 10%, rgba(0,123,255,.35), transparent 60%),
      radial-gradient(900px 500px at 90% 30%, rgba(40,167,69,.25), transparent 55%),
      radial-gradient(800px 450px at 40% 90%, rgba(255,193,7,.18), transparent 55%),
      linear-gradient(135deg, #0b1220 0%, #111b2e 50%, #0b1220 100%);
    filter: saturate(1.05);
    z-index: 0;
  }
  .login-box{ position: relative; z-index: 1; }

  .auth-brand{
    background: rgba(255,255,255,.12);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.12);
  }

  .auth-card{
    border-radius: 14px;
    overflow: hidden;
  }

  .auth-card .card-body{
    border-radius: 0 0 14px 14px;
  }

  /* Input jadi lebih “soft” tapi tetap bootstrap/adminlte */
  .auth-card .form-control{
    border-radius: 10px;
  }
  .auth-card .input-group-text{
    border-radius: 0 10px 10px 0;
  }

  .auth-btn{
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: .2px;
    box-shadow: 0 10px 25px rgba(0,123,255,.20);
  }
  .auth-btn:hover{
    box-shadow: 0 14px 30px rgba(0,123,255,.28);
  }

  /* Teks kecil */
  .font-weight-semibold{ font-weight: 600; }
</style>
@endpush
@endsection