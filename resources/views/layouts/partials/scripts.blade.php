<!-- jQuery -->
<script src="{{ asset('adminlte3/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte3/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE demo (opsional, boleh hapus kalau tidak perlu) -->
<script src="{{ asset('adminlte3/dist/js/demo.js') }}"></script>

@livewireScripts

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Konfigurasi Standar Toast
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });

  // Listener untuk Flash Message Backend
  document.addEventListener('DOMContentLoaded', () => {
    @if(session()->has('success') || session()->has('successUA') || session()->has('successUC'))
      Toast.fire({
        icon: 'success',
        title: "{{ session('success') ?? session('successUA') ?? session('successUC') }}"
      });
    @endif

    @if(session()->has('error'))
      Toast.fire({
        icon: 'error',
        title: "{{ session('error') }}"
      });
    @endif
  });

  // Listener untuk Livewire Dispatch (Livewire 3)
  window.addEventListener('swal:toast', event => {
    const data = event.detail[0] || event.detail;
    Toast.fire({
      icon: data.type || 'success',
      title: data.message
    });
  });
</script>

@stack('scripts')