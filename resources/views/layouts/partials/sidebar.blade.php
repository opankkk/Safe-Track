@php
  // Apakah user ini adalah manager?
  $isManager = auth()->check() && auth()->user()->role === 'manager';

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

  // label brand: Manager selalu tampil sebagai Manager, tak peduli di halaman mana
  $brandText = $isManager
    ? 'Sistem HSE - Manager'
    : match ($module) {
        'pic' => 'Sistem HSE - PIC',
        'hse-manager' => 'Sistem HSE - Manager',
        default => 'Sistem HSE - HSE',
      };
@endphp

<aside class="main-sidebar sidebar-light-primary elevation-4 d-flex flex-column">

  {{-- Brand --}}
  <a href="{{ route($routePrefix.'dashboard') }}" class="brand-link text-center border-bottom-0 d-flex flex-column align-items-center py-3">
    <div class="brand-logo-wrapper mb-2">
      <img src="https://pamitra.co.id/landing/images/logo-gray.png"
           alt="Pamitra Logo"
           class="brand-image-xl"
           style="width: 140px; height: auto; max-height: none; float: none; display: block; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));">
    </div>
    <br>
    <span class="brand-text font-weight-bolder" style="font-size: 0.95rem; line-height: 1.2; color: #002E5B; white-space: normal; letter-spacing: 0.5px; text-transform: uppercase;">
      Sistem Pelaporan HSE
    </span>
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

        {{-- LAPORAN KECELAKAAN & TEMUAN - tidak tampil di HSE MANAGER --}}
        @if ($module !== 'hse-manager')
          <li class="nav-item">
            <a href="{{ route($routePrefix.'accident') }}"
              class="nav-link @yield('menu-accident-active')">
              <i class="nav-icon fas fa-notes-medical"></i>
              <p>Laporan Kecelakaan</p>
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

        {{-- LAPORAN PENANGANAN (RIWAYAT) - HSE & HSE MANAGER --}}
        @if (in_array($module, ['hse', 'hse-manager']))
          <li class="nav-item">
            <a href="{{ route($routePrefix.'report') }}"
               class="nav-link @yield('menu-perbaikan-active')">
              <i class="nav-icon fas fa-tools"></i>
              <p>Laporan Penanganan</p>
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