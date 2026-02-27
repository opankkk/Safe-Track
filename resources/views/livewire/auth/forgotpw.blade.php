@extends('layouts.app')

@section('title', 'Lupa Password | Sistem HSE')
@section('body-class', 'hold-transition login-page')
@section('is-auth', true)

@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Sistem</b>HSE</a>
  </div>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">
        Anda lupa password? Di sini Anda bisa meminta reset password.
      </p>

      <form>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">
              Request new password
            </button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="{{ route('user.login') }}">Login</a>
      </p>
      <p class="mb-0">
        {{-- optional kalau belum ada halaman register, boleh hapus --}}
        <a href="#" class="text-center">Register a new membership</a>
      </p>
    </div>
  </div>
</div>
@endsection