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

<aside class="main-sidebar sidebar-light-primary elevation-4">

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

        {{-- LAPORAN KERUSAKAN (ACCIDENT) --}}
        <li class="nav-item">
          <a href="{{ route($routePrefix.'accident') }}"
             class="nav-link @yield('menu-accident-active')">
            <i class="nav-icon fas fa-notes-medical"></i>
            <p>Laporan Kerusakan</p>
          </a>
        </li>

        {{-- LAPORAN TEMUAN (INCIDENT) --}}
        <li class="nav-item">
          <a href="{{ route($routePrefix.'incident') }}"
             class="nav-link @yield('menu-incident-active')">
            <i class="nav-icon fas fa-exclamation-triangle"></i>
            <p>Laporan Temuan</p>
          </a>
        </li>

        {{-- LAPORAN PERBAIKAN (RIWAYAT) - hanya HSE --}}
        @if ($module === 'hse')
          <li class="nav-item">
            <a href="{{ route('hse.perbaikan') }}"
               class="nav-link @yield('menu-perbaikan-active')">
              <i class="nav-icon fas fa-tools"></i>
              <p>Laporan Perbaikan</p>
            </a>
          </li>
        @endif

      </ul>

    </nav>
  </div>
</aside>