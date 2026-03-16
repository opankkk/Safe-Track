{{-- resources/views/livewire/h-s-e/dashboard.blade.php --}}
@section('title', 'Dashboard | Sistem HSE')
@section('menu-dashboard-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Periode Laporan</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">
      {{ $filterYear }}
    </span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

<div>

  {{-- ═══════════════════════════════════════════════
       FILTER BAR: Tahun + Bulan (WIB)
       FILTER BAR: Tahun (WIB)
  ═══════════════════════════════════════════════ --}}
  <div class="container-fluid mb-3">
    <div class="d-flex align-items-center flex-wrap" style="gap:10px;">

      <small class="text-muted font-weight-bold" style="white-space:nowrap;">Filter Periode:</small>

      {{-- Filter Tahun --}}
      <select class="form-control form-control-sm"
              style="max-width:120px;"
              wire:model.live="filterYear">
        @foreach(range(\Carbon\Carbon::now('Asia/Jakarta')->year, \Carbon\Carbon::now('Asia/Jakarta')->year - 4) as $yr)
          <option value="{{ $yr }}">{{ $yr }}</option>
        @endforeach
      </select>

      {{-- Spinner saat loading --}}
      <div wire:loading class="d-flex align-items-center text-muted" style="gap:6px;">
        <span class="spinner-border spinner-border-sm" role="status"></span>
        <small>Memuat...</small>
      </div>

    </div>
  </div>

  {{-- ═══════════════════════════════════════════════
       METRIC CARDS (view only, tidak bisa diklik)
  ═══════════════════════════════════════════════ --}}
  <div class="container-fluid">
    <div class="row">

      {{-- Accident --}}
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="p-3 text-white position-relative overflow-hidden"
             style="border-radius:10px; min-height:120px;
                    background:linear-gradient(135deg,#ff4b7d,#d81b60);
                    user-select:none;">
          <i class="fas fa-notes-medical position-absolute"
             style="top:14px; right:14px; opacity:.85; font-size:18px;"></i>

          <div style="font-size:36px; font-weight:800; line-height:1; margin-bottom:4px;">
            {{ $countAccident }}
          </div>
          <div style="font-size:12px; opacity:.9;">Laporan Masuk</div>
          <div style="font-size:13px; font-weight:700;">Accident Report</div>
        </div>
      </div>

      {{-- Unsafe Action --}}
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="p-3 text-white position-relative overflow-hidden"
             style="border-radius:10px; min-height:120px;
                    background:linear-gradient(135deg,#ffa000,#f57c00);
                    user-select:none;">
          <i class="fas fa-user-times position-absolute"
             style="top:14px; right:14px; opacity:.85; font-size:18px;"></i>

          <div style="font-size:36px; font-weight:800; line-height:1; margin-bottom:4px;">
            {{ $countUnsafeAction }}
          </div>
          <div style="font-size:12px; opacity:.9;">Laporan Masuk</div>
          <div style="font-size:13px; font-weight:700;">Unsafe Action</div>
        </div>
      </div>

      {{-- Unsafe Condition --}}
      <div class="col-lg-3 col-md-6 mb-3">
        <div class="p-3 text-white position-relative overflow-hidden"
             style="border-radius:10px; min-height:120px;
                    background:linear-gradient(135deg,#1e88e5,#1565c0);
                    user-select:none;">
          <i class="fas fa-exclamation-triangle position-absolute"
             style="top:14px; right:14px; opacity:.85; font-size:18px;"></i>

          <div style="font-size:36px; font-weight:800; line-height:1; margin-bottom:4px;">
            {{ $countUnsafeCondition }}
          </div>
          <div style="font-size:12px; opacity:.9;">Laporan Masuk</div>
          <div style="font-size:13px; font-weight:700;">Unsafe Condition</div>
        </div>
      </div>

      {{-- Verifikasi Hasil --}}
      <div class="col-lg-3 col-md-6 mb-3">
        <a href="{{ url('/hse/report') }}" class="text-decoration-none">
          <div class="p-3 text-white position-relative overflow-hidden"
               style="border-radius:10px; min-height:120px;
                      background:linear-gradient(135deg,#17a2b8,#117a8b);
                      user-select:none;">
            <i class="fas fa-check-double position-absolute"
               style="top:14px; right:14px; opacity:.85; font-size:18px;"></i>

            <div style="font-size:36px; font-weight:800; line-height:1; margin-bottom:4px;">
              {{ $countVerifikasiHasil }}
            </div>
            <div style="font-size:12px; opacity:.9;">Menunggu Review HSE</div>
            <div style="font-size:13px; font-weight:700;">Verifikasi Hasil</div>
          </div>
        </a>
      </div>

    </div>
  </div>
  {{-- END METRIC CARDS --}}


  {{-- ═══════════════════════════════════════════════
       CHART + NOTIF
  ═══════════════════════════════════════════════ --}}
  <div class="container-fluid flex-grow-1 d-flex flex-column">
    <div class="row flex-grow-1 align-items-stretch" style="min-height:420px;">

      {{-- LEFT: Chart --}}
      <div class="col-lg-9 mb-3 d-flex">
        <div class="card d-flex flex-column w-100" style="border-radius:10px;">
          <div class="card-body d-flex flex-column">

            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px;">
              <div>
                <div class="font-weight-bold" style="font-size:16px;">Lagging Indicator</div>
                <small class="text-muted">
                  Monitoring laporan — <strong>{{ $filterYear }}</strong>
                </small>
              </div>
              {{-- Year info saja (sudah diatur di filter bar atas) --}}
              <span class="badge badge-secondary" style="font-size:12px; padding:6px 12px;">
                {{ $filterYear }}
              </span>
            </div>

            <div class="mt-3 flex-grow-1" style="min-height:380px; position:relative;">

              @if(!$chartData['hasData'])
                {{-- Empty state --}}
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted text-center"
                     style="min-height:280px;">
                  <i class="fas fa-chart-bar" style="font-size:52px; opacity:.18; margin-bottom:14px;"></i>
                  <div style="font-size:14px; font-weight:700;">Belum ada data laporan berstatus <em>Close</em></div>
                  <small>Chart akan tampil setelah laporan diproses dan berstatus <em>Close</em>.</small>
                </div>
              @else
                <div wire:key="chart-container-{{ $filterYear }}" style="height: 100%;">
                  <canvas id="chartLagging" wire:ignore.self></canvas>
                </div>
              @endif

            </div>

          </div>
        </div>
      </div>
      {{-- END LEFT --}}

      {{-- RIGHT: Notifikasi --}}
      <div class="col-lg-3 mb-3 d-flex">
        <div class="card d-flex flex-column w-100" style="border-radius:10px;">
          <div class="card-body d-flex flex-column">

            <div class="d-flex justify-content-between align-items-center" style="gap:8px;">
              <div>
                <div class="font-weight-bold" style="font-size:18px;">Notifikasi</div>
              </div>
              @if(count($notifications) > 0)
                <span class="badge badge-danger"
                      style="font-size:13px; border-radius:20px; padding:5px 11px; flex-shrink:0;">
                  {{ count($notifications) }}
                </span>
              @endif
            </div>

            <div class="mt-3 flex-grow-1 overflow-auto" style="padding-right:4px; max-height:480px;">
              <div class="d-flex flex-column" style="gap:10px;">

                @forelse($notifications as $notif)
                  {{-- Kartu notifikasi dapat diklik --}}
                  <a href="{{ $notif['href'] }}"
                     class="d-block text-decoration-none"
                     style="color:inherit; border-radius:14px; transition:opacity .2s;"
                     onmouseover="this.style.opacity='.85'"
                     onmouseout="this.style.opacity='1'">
                    <div class="d-flex text-white"
                         style="gap:12px; padding:12px 14px; border-radius:14px; background:{{ $notif['bg'] }};">
                      <div class="d-flex align-items-center justify-content-center"
                           style="width:40px; height:40px; border-radius:10px;
                                  background:rgba(255,255,255,.18); flex:0 0 40px; font-size:17px;">
                        <i class="{{ $notif['icon'] }}"></i>
                      </div>
                      <div style="min-width:0;">
                        <div style="font-weight:800; font-size:13px; margin-bottom:3px; line-height:1.3; word-break:break-word;">
                          {{ $notif['label'] }} - {{ $notif['report_number'] }}
                        </div>
                        <div style="font-size:10px; color:rgba(255,255,255,.9); font-weight:700;
                                    text-transform:uppercase; letter-spacing:.5px; margin-bottom:2px;">
                            {{ $notif['status_label'] ?? 'Pending' }}
                        </div>
                        <div style="font-size:11px; color:rgba(255,255,255,.75);">
                          {{ $notif['time_diff'] }}
                        </div>
                      </div>
                    </div>
                  </a>
                @empty
                  <div class="d-flex flex-column align-items-center justify-content-center text-muted"
                       style="padding:40px 0;">
                    <i class="fas fa-check-circle" style="font-size:40px; opacity:.22; margin-bottom:10px;"></i>
                    <div style="font-size:14px; font-weight:600;">Semua laporan sudah diproses</div>
                    <small>Tidak ada laporan pending.</small>
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
@if($chartData['hasData'])
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
  let chartInstance = null;

  function initChart() {
    const ctx = document.getElementById('chartLagging');
    if (!ctx) return;

    if (chartInstance) {
      chartInstance.destroy();
    }

    chartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [
          { label: 'Unsafe Condition',    data: {!! json_encode($chartData['unsafeCondition']) !!},   backgroundColor: '#1565c0' },
          { label: 'Unsafe Action',       data: {!! json_encode($chartData['unsafeAction']) !!},      backgroundColor: '#f57c00' },
          { label: 'Nearmiss',            data: {!! json_encode($chartData['nearmiss']) !!},           backgroundColor: '#8bc34a' },
          { label: 'Gangguan Kesehatan',  data: {!! json_encode($chartData['gangguanKesehatan']) !!}, backgroundColor: '#9c27b0' },
          { label: 'First Aid',           data: {!! json_encode($chartData['firstAid']) !!},          backgroundColor: '#00bcd4' },
          { label: 'Medical Aid',         data: {!! json_encode($chartData['medicalAid']) !!},        backgroundColor: '#ff9800' },
          { label: 'Heavy Accident',      data: {!! json_encode($chartData['heavyAccident']) !!},     backgroundColor: '#d81b60' },
          { label: 'Fatality',            data: {!! json_encode($chartData['fatality']) !!},          backgroundColor: '#b71c1c' },
          { label: 'Loss Mandays',        data: {!! json_encode($chartData['lossMandays']) !!},       backgroundColor: '#5d4037' },
          { label: 'Property Damage',     data: {!! json_encode($chartData['propertyDamage']) !!},    backgroundColor: '#607d8b' },
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' }
        },
        scales: {
          x: { stacked: true },
          y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } }
        }
      }
    });
  }

  // Init on load
  initChart();

  // Re-init on Livewire update
  document.addEventListener('dashboardUpdated', () => {
    setTimeout(initChart, 100);
  });
})();
</script>
@endif
@endpush