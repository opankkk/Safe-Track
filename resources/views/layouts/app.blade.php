<!DOCTYPE html>
<html>
<head>
  @include('layouts.partials.head')
</head>

<body class="@yield('body-class', 'hold-transition sidebar-mini sidebar-mini-md layout-fixed')">

{{-- kalau halaman auth (login/register/forgot), tampilkan konten saja --}}
@if(View::hasSection('is-auth'))
  <div class="auth-wrapper">
    {{ $slot ?? '' }}
    @yield('content')
  </div>
@else
<div class="wrapper">

  {{-- Navbar --}}
  @php
    $showNavbar = !View::hasSection('hide-navbar');
  @endphp
  
  <div class="{{ $showNavbar ? '' : 'd-lg-none' }}">
    @include('layouts.partials.navbar')
  </div>

{{-- Sidebar --}}
@if(!View::hasSection('hide-sidebar'))
    @include('layouts.partials.sidebar')
@endif

  <div class="content-wrapper">
    @include('layouts.partials.content-header')

    <section class="content">
      {{ $slot ?? '' }}
      @yield('content')
    </section>
  </div>

  @include('layouts.partials.footer')
  @include('layouts.partials.control-sidebar')

</div>
@endif

@include('layouts.partials.scripts')
</body>
</html>