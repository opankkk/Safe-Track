<aside class="main-sidebar sidebar-light-primary elevation-4">
  <a href="#" class="brand-link">
    <img src="{{ asset('adminlte3/dist/img/AdminLTELogo.png') }}"
         alt="Logo"
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">Sistem HSE</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="#" class="nav-link @yield('menu-dashboard-active')">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link @yield('menu-reports-active')">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Laporan</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>