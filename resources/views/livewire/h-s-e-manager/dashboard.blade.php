@extends('layouts.app')

@section('title', 'Dashboard | Sistem HSE')
@section('menu-dashboard-active', 'active')
@section('hide-navbar', true)

{{-- Header kiri --}}
@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Periode Laporan</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">November 2025</span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
  .sidebar-light-primary .nav-sidebar .nav-link { color: #333; }
  .sidebar-light-primary .nav-sidebar .nav-link:hover { background-color: #f2f4f7; color: #0d6efd; }
  .sidebar-light-primary .nav-sidebar .nav-link.active { background-color: #e7f1ff; color: #0d6efd; font-weight: 600; }

  /* Layout */
  .content-header { padding-bottom: 0; }
  .dash-topbar { display:flex; justify-content:space-between; align-items:flex-start; gap: 12px; }
  .dash-filter { display:flex; gap: 8px; align-items:center; }

  /* Metric cards */
  .metric-card {
    border-radius: 10px;
    color: #fff;
    min-height: 120px;
    position: relative;
    overflow: hidden;
  }
  .metric-card .metric-icon {
    position: absolute;
    top: 14px;
    right: 14px;
    opacity: .85;
    font-size: 18px;
  }
  .metric-card .metric-value {
    font-size: 26px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 4px;
  }
  .metric-card .metric-unit,
  .metric-card .metric-label {
    font-size: 12px;
    opacity: .95;
  }
  .metric-card .metric-dot {
    position: absolute;
    left: 50%;
    top: 65%;
    transform: translate(-50%, -50%);
    width: 6px;
    height: 6px;
    background: rgba(255,255,255,.85);
    border-radius: 999px;
  }

  /* Gradients */
  .g-blue   { background: linear-gradient(135deg, #1e88e5, #1565c0); }
  .g-green  { background: linear-gradient(135deg, #2ecc71, #1e9c5a); }
  .g-orange { background: linear-gradient(135deg, #ffa000, #f57c00); }
  .g-pink   { background: linear-gradient(135deg, #ff4b7d, #d81b60); }
  .g-purple { background: linear-gradient(135deg, #7c4dff, #512da8); }

  /* KPI cards */
  .kpi-card { border-radius: 10px; color: #fff; min-height: 86px; }
  .kpi-card .kpi-big { font-size: 26px; font-weight: 800; line-height: 1; }
  .kpi-card .kpi-sub { font-size: 12px; opacity: .95; }
  .kpi-red   { background: linear-gradient(135deg, #ff5a6a, #f50057); }
  .kpi-amber { background: linear-gradient(135deg, #ffb74d, #fb8c00); }
  .kpi-green { background: linear-gradient(135deg, #66bb6a, #2e7d32); }

  /* Chart placeholder */
  .chart-placeholder { height: 220px; }

  /* Notifikasi (mirip screenshot) */
  .notif-wrap { display:flex; flex-direction:column; gap:14px; }
  .notif-item {
    display:flex;
    gap:14px;
    padding:18px 16px;
    border-radius:14px;
    background:#f7f9fc;
  }
  .notif-icon {
    width:46px; height:46px;
    border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    flex: 0 0 46px;
    font-size:18px;
  }
  .notif-icon.success { background:#dff7e8; color:#1f9254; }
  .notif-icon.warning { background:#fff3cd; color:#b07a00; }
  .notif-icon.info    { background:#e7f1ff; color:#0d6efd; }

  .notif-title { font-weight:800; font-size:15px; margin-bottom:2px; color:#2b2f33; }
  .notif-desc  { color:#6c757d; line-height:1.35; margin-bottom:10px; }
  .notif-time  { font-size:13px; color:#48628a; font-weight:600; }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- Topbar: kanan filter periode --}}
  <div class="dash-topbar mb-3">
    <div></div>
    <div class="dash-filter">
      <select class="form-control form-control-sm" style="min-width: 220px;">
        <option>November 2025</option>
        <option>Oktober 2025</option>
        <option>September 2025</option>
      </select>
      <button class="btn btn-primary btn-sm">Filter</button>
      <button class="btn btn-light btn-sm">Reset</button>
    </div>
  </div>

  {{-- ROW 1: 3 metric cards --}}
  <div class="row">

    {{-- Total Accident --}}
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="metric-card g-pink p-3">
        <i class="metric-icon fas fa-notes-medical"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Laporan</div>
        <div class="metric-unit">Total Accident Report</div>
        <div class="metric-dot"></div>
      </div>
    </div>

    {{-- Total Unsafe Action --}}
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="metric-card g-orange p-3">
        <i class="metric-icon fas fa-user-times"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Laporan</div>
        <div class="metric-unit">Unsafe Action</div>
        <div class="metric-dot"></div>
      </div>
    </div>

    {{-- Total Unsafe Condition --}}
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="metric-card g-blue p-3">
        <i class="metric-icon fas fa-exclamation-triangle"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Laporan</div>
        <div class="metric-unit">Unsafe Condition</div>
        <div class="metric-dot"></div>
      </div>
    </div>

  </div>

  {{-- ROW 2: Lagging Indicator + Notifikasi --}}
  <div class="row">

    {{-- LEFT: Lagging Indicator --}}
    <div class="col-lg-7 mb-3">
      <div class="card" style="border-radius: 10px;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px;">
            <div>
              <div class="font-weight-bold" style="font-size:16px;">Lagging Indicator</div>
              <small class="text-muted">Diagram batang per bulan (stacked) + total.</small>
            </div>

            <div class="d-flex align-items-center" style="gap:8px;">
              <small class="text-muted">Tahun</small>
              <select id="filterYearLagging" class="form-control form-control-sm" style="min-width: 120px;">
                <option value="2025" selected>2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
              </select>
            </div>
          </div>

          <div class="chart-placeholder mt-3" style="height: 300px;">
            <canvas id="chartLagging"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- RIGHT: Notifikasi --}}
    <div class="col-lg-5 mb-3">
      <div class="card" style="border-radius: 10px;">
        <div class="card-body">

          <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px;">
            <div>
              <div class="font-weight-bold" style="font-size:18px;">Notifikasi</div>
              <small class="text-muted">Approval laporan Unsafe & Accident.</small>
            </div>

            <div class="d-flex align-items-center" style="gap:8px;">
              <small class="text-muted">Tahun</small>
              <select class="form-control form-control-sm" style="min-width: 120px;">
                <option value="2025" selected>2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
              </select>
            </div>
          </div>

          <div class="mt-3 notif-wrap">

            <div class="notif-item">
              <div class="notif-icon success">
                <i class="fas fa-check"></i>
              </div>
              <div class="notif-content">
                <div class="notif-title">Approval Unsafe Action disetujui</div>
                <div class="notif-desc">Laporan unsafe action anda telah disetujui oleh atasan.</div>
                <div class="notif-time">Baru saja</div>
              </div>
            </div>

            <div class="notif-item">
              <div class="notif-icon warning">
                <i class="far fa-clock"></i>
              </div>
              <div class="notif-content">
                <div class="notif-title">Menunggu approval Accident Report</div>
                <div class="notif-desc">Laporan kecelakaan sedang menunggu persetujuan atasan.</div>
                <div class="notif-time">1 jam lalu</div>
              </div>
            </div>

            <div class="notif-item">
              <div class="notif-icon info">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <div class="notif-content">
                <div class="notif-title">Approval Unsafe Condition disetujui</div>
                <div class="notif-desc">Laporan unsafe condition anda telah disetujui.</div>
                <div class="notif-time">Kemarin</div>
              </div>
            </div>
          </div>

          <div class="mt-3 text-right">
            <a href="javascript:void(0)" class="text-primary" style="font-weight:600;">Lihat semua</a>
          </div>

        </div>
      </div>
    </div>

  </div>

  {{-- ROW 3: KPI cards --}}
  <div class="row">

    <div class="col-lg-4 mb-3">
      <div class="kpi-card kpi-red p-3 d-flex align-items-center justify-content-between">
        <div>
          <div class="kpi-big">0.00%</div>
          <div class="kpi-sub">Severity Rate</div>
        </div>
        <i class="fas fa-percentage" style="opacity:.85;"></i>
      </div>
    </div>

    <div class="col-lg-4 mb-3">
      <div class="kpi-card kpi-amber p-3">
        <div class="d-flex justify-content-between">
          <div>
            <div class="kpi-big">0%</div>
            <div class="kpi-sub">Masuk</div>
          </div>
          <div>
            <div class="kpi-big">0%</div>
            <div class="kpi-sub">Keluar</div>
          </div>
        </div>
        <div class="mt-2 d-flex align-items-center justify-content-between">
          <div class="font-weight-bold">Limbah B3</div>
          <i class="fas fa-recycle" style="opacity:.85;"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-4 mb-3">
      <div class="kpi-card kpi-green p-3 d-flex align-items-center justify-content-between">
        <div>
          <div class="kpi-big">0.00%</div>
          <div class="kpi-sub">Frequency Rate</div>
        </div>
        <i class="fas fa-chart-bar" style="opacity:.85;"></i>
      </div>
    </div>

  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const labelsMonthTotal = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Total'];

  // Dummy data (frontend only)
  const laggingDataByYear = {
    2025: {
      nearmiss:   [2,0,0,0,1,1,1,1,1,0,0,0,8],
      firstAid:   [0,0,0,0,1,2,0,0,1,0,0,0,4],
      medicalAid: [0,0,0,0,0,1,0,0,0,0,0,0,1],
      heavyAcc:   [0,0,0,0,0,0,0,0,0,0,0,0,0],
      fatality:   [0,0,0,0,0,0,0,0,0,0,0,0,0],
      lossDay:    [0,0,0,0,0,0,0,0,0,0,0,0,0],
      propDmg:    [0,0,0,0,0,1,1,0,1,0,0,0,3],
      majorNC:    [0,0,0,0,0,0,0,0,0,0,0,0,0],
    },
    2024: {
      nearmiss:   [1,0,0,0,0,1,0,1,0,0,0,0,3],
      firstAid:   [0,0,0,0,1,0,0,0,0,0,0,0,1],
      medicalAid: [0,0,0,0,0,0,0,0,0,0,0,0,0],
      heavyAcc:   [0,0,0,0,0,0,0,0,0,0,0,0,0],
      fatality:   [0,0,0,0,0,0,0,0,0,0,0,0,0],
      lossDay:    [0,0,0,0,0,0,0,0,0,0,0,0,0],
      propDmg:    [0,0,0,0,0,0,0,0,0,0,0,0,0],
      majorNC:    [0,0,0,0,0,0,0,0,0,0,0,0,0],
    },
    2023: {
      nearmiss:   [0,0,0,0,0,0,0,0,0,0,0,0,0],
      firstAid:   [0,0,0,0,0,0,0,0,0,0,0,0,0],
      medicalAid: [0,0,0,0,0,0,0,0,0,0,0,0,0],
      heavyAcc:   [0,0,0,0,0,0,0,0,0,0,0,0,0],
      fatality:   [0,0,0,0,0,0,0,0,0,0,0,0,0],
      lossDay:    [0,0,0,0,0,0,0,0,0,0,0,0,0],
      propDmg:    [0,0,0,0,0,0,0,0,0,0,0,0,0],
      majorNC:    [0,0,0,0,0,0,0,0,0,0,0,0,0],
    }
  };

  function buildLaggingDatasets(year){
    const d = laggingDataByYear[year] || laggingDataByYear[2025];
    return [
      { label: '1 Nearmiss', data: d.nearmiss },
      { label: '2 First Aid', data: d.firstAid },
      { label: '3 Medical Aid', data: d.medicalAid },
      { label: '4 Heavy Accident', data: d.heavyAcc },
      { label: '5 Fatality', data: d.fatality },
      { label: '6 Loss Mandays', data: d.lossDay },
      { label: '7 Property Damage', data: d.propDmg },
      { label: '8 Major Non Conformance Finding by Client', data: d.majorNC },
    ];
  }

  const ctxLagging = document.getElementById('chartLagging');

  const chartLagging = new Chart(ctxLagging, {
    type: 'bar',
    data: {
      labels: labelsMonthTotal,
      datasets: buildLaggingDatasets('2025')
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: { display: true, text: 'Lagging Indicator 2025' },
        legend: { position: 'bottom' }
      },
      scales: {
        x: { stacked: true },
        y: { stacked: true, beginAtZero: true }
      }
    }
  });

  document.getElementById('filterYearLagging').addEventListener('change', function(){
    const year = this.value;
    chartLagging.data.datasets = buildLaggingDatasets(year);
    chartLagging.options.plugins.title.text = `Lagging Indicator ${year}`;
    chartLagging.update();
  });
</script>
@endpush