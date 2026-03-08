@php
  // Deteksi module dari URL: hse/*, pic/*, hse-manager/*
  $module = request()->segment(1);

  // fallback kalau bukan salah satu module
  if (!in_array($module, ['hse', 'pic', 'hse-manager'])) {
    $module = 'hse';
  }

  // route name prefix sesuai module
  $routePrefix = match ($module) {
    'pic' => 'pic.',
    'hse-manager' => 'hse.manager.',
    default => 'hse.',
  };

  // label brand (opsional)
  $brandText = match ($module) {
    'pic' => 'Sistem HSE - PIC',
    'hse-manager' => 'Sistem HSE - Manager',
    default => 'Sistem HSE - HSE',
  };
@endphp

<aside class="main-sidebar sidebar-light-primary elevation-4 d-flex flex-column">

  {{-- Brand --}}
  <a href="{{ route($routePrefix.'dashboard') }}" class="brand-link">
    <img src="{{ asset('adminlte3/dist/img/AdminLTELogo.png') }}"
         alt="Logo"
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">{{ $brandText }}</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">

      <ul class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false">

        {{-- DASHBOARD --}}
        <li class="nav-item">
          <a href="{{ route($routePrefix.'dashboard') }}"
             class="nav-link @yield('menu-dashboard-active')">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- LAPORAN KERUSAKAN & TEMUAN - tidak tampil di HSE MANAGER --}}
        @if ($module !== 'hse-manager')
          <li class="nav-item">
            <a href="{{ route($routePrefix.'accident') }}"
              class="nav-link @yield('menu-accident-active')">
              <i class="nav-icon fas fa-notes-medical"></i>
              <p>Laporan Kerusakan</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route($routePrefix.'incident') }}"
              class="nav-link @yield('menu-incident-active')">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>Laporan Temuan</p>
            </a>
          </li>
        @endif

        {{-- LAPORAN PERBAIKAN (RIWAYAT) - HSE & HSE MANAGER --}}
        @if (in_array($module, ['hse', 'hse-manager']))
          <li class="nav-item">
            <a href="{{ route($routePrefix.'report') }}"
               class="nav-link @yield('menu-perbaikan-active')">
              <i class="nav-icon fas fa-tools"></i>
              <p>Laporan Perbaikan</p>
            </a>
          </li>
        @endif

        {{-- PLAN TINDAK LANJUT - hanya HSE MANAGER --}}
        @if ($module === 'hse-manager')
          <li class="nav-item">
            <a href="{{ route($routePrefix.'plan-tindak-lanjut') }}"
               class="nav-link @yield('menu-plan-tindak-lanjut-active')">
              <i class="nav-icon fas fa-clipboard-check"></i>
              <p>Plan Tindak Lanjut</p>
            </a>
          </li>
        @endif

        {{-- SUPERADMIN MENU FOR MANAGER --}}
        @if (auth()->check() && auth()->user()->role === 'manager')
          <li class="nav-header">AKSES SUPERADMIN</li>
          <li class="nav-item">
            <a href="{{ route('hse.manager.dashboard') }}" class="nav-link {{ $module === 'hse-manager' ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Halaman Manager</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('hse.dashboard') }}" class="nav-link {{ $module === 'hse' ? 'active' : '' }}">
              <i class="nav-icon fas fa-hard-hat"></i>
              <p>Halaman HSE Officer</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pic.dashboard') }}" class="nav-link {{ $module === 'pic' ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Halaman PIC</p>
            </a>
          </li>
        @endif

      </ul>

    </nav>
  </div>

  {{-- ================= LOGOUT BUTTON - POSISI PALING BAWAH ================= --}}
  <div class="mt-auto p-3 border-top">
    <form method="POST" action="{{ route('logout') }}" id="logout-form">
      @csrf
      <a href="#" 
         class="btn btn-block btn-outline-danger btn-sm font-weight-bold" 
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt mr-2"></i> Logout
      </a>
    </form>
  </div>

</aside>