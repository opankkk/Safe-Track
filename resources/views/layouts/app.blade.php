<!DOCTYPE html>
<html>
<head>
  @include('layouts.partials.head')
</head>

<body class="@yield('body-class', 'hold-transition sidebar-mini')">

{{-- kalau halaman auth (login/register/forgot), tampilkan konten saja --}}
@if(View::hasSection('is-auth'))
  @yield('content')
@else
<div class="wrapper">

  {{-- Navbar --}}
@if(!View::hasSection('hide-navbar'))
    @include('layouts.partials.navbar')
@endif

{{-- Sidebar --}}
@if(!View::hasSection('hide-sidebar'))
    @include('layouts.partials.sidebar')
@endif

  <div class="content-wrapper">
    @include('layouts.partials.content-header')

    <section class="content">
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