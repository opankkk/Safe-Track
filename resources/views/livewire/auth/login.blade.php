@extends('layouts.app')

@section('title', 'Login | Sistem HSE')
@section('body-class', 'hold-transition login-page')
@section('is-auth', true)

@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Sistem</b>HSE</a>
  </div>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Silakan login untuk melanjutkan</p>

      <form>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">Remember Me</label>
            </div>
          </div>
          <div class="col-4">
            <a href="{{ route('hse.manager.dashboard') }}" class="btn btn-primary btn-block">
                Login
            </a>
          </div>
        </div>
      </form>

      <p class="mb-1 mt-3">
        <a href="{{ route('user.forgotpw') }}">Lupa password?</a>
      </p>
    </div>
  </div>
</div>
@endsection