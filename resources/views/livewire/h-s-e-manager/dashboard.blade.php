@extends('layouts.app')

@section('title', 'Dashboard | Sistem HSE')
@section('menu-dashboard-active', 'active')
@section('hide-navbar', true)

{{-- Header kiri seperti screenshot --}}
@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Periode Laporan</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">November 2025</span>
  </div>
@endsection

{{-- Breadcrumb bisa dikosongkan kalau tidak dipakai --}}
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>

 .sidebar-light-primary .nav-sidebar .nav-link {
      color: #333;
  }

  .sidebar-light-primary .nav-sidebar .nav-link:hover {
      background-color: #f2f4f7;
      color: #0d6efd;
  }

  .sidebar-light-primary .nav-sidebar .nav-link.active {
      background-color: #e7f1ff;
      color: #0d6efd;
      font-weight: 600;
  }
  /* Biar layout mirip screenshot */
  .content-header { padding-bottom: 0; }
  .dash-topbar { display:flex; justify-content:space-between; align-items:flex-start; gap: 12px; }
  .dash-filter { display:flex; gap: 8px; align-items:center; }

  /* Card metrik gradient */
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
  .metric-card .metric-unit {
    font-size: 12px;
    opacity: .95;
  }
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

  /* Card bawah */
  .kpi-card {
    border-radius: 10px;
    color: #fff;
    min-height: 86px;
  }
  .kpi-card .kpi-big { font-size: 26px; font-weight: 800; line-height: 1; }
  .kpi-card .kpi-sub { font-size: 12px; opacity: .95; }
  .kpi-red    { background: linear-gradient(135deg, #ff5a6a, #f50057); }
  .kpi-amber  { background: linear-gradient(135deg, #ffb74d, #fb8c00); }
  .kpi-green  { background: linear-gradient(135deg, #66bb6a, #2e7d32); }

  /* Chart area placeholder */
  .chart-placeholder {
    height: 220px;
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- Topbar: kiri judul sudah di page-title, kanan filter --}}
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

  {{-- ROW 1: 5 metric cards --}}
  <div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="metric-card g-blue p-3">
        <i class="metric-icon fas fa-users"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Orang</div>
        <div class="metric-unit">Jumlah Pegawai</div>
        <div class="metric-dot"></div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
      <div class="metric-card g-green p-3">
        <i class="metric-icon far fa-clock"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Jam</div>
        <div class="metric-unit">Jam Kerja Normal</div>
        <div class="metric-dot"></div>
      </div>
    </div>

    <div class="col-lg-2 col-md-6 mb-3">
      <div class="metric-card g-orange p-3">
        <i class="metric-icon far fa-clock"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Jam</div>
        <div class="metric-unit">Jam Kerja Lembur</div>
        <div class="metric-dot"></div>
      </div>
    </div>

    <div class="col-lg-2 col-md-6 mb-3">
      <div class="metric-card g-pink p-3">
        <i class="metric-icon fas fa-notes-medical"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Orang</div>
        <div class="metric-unit">Pegawai Sakit</div>
        <div class="metric-dot"></div>
      </div>
    </div>

    <div class="col-lg-2 col-md-6 mb-3">
      <div class="metric-card g-purple p-3">
        <i class="metric-icon fas fa-user-clock"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Jam</div>
        <div class="metric-unit">Jam Kerja Hilang</div>
        <div class="metric-dot"></div>
      </div>
    </div>
  </div>

  {{-- ROW 2: 2 charts --}}
  <div class="row">
    <div class="col-lg-6 mb-3">
      <div class="card" style="border-radius: 10px;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="font-weight-bold">0</div>
              <small class="text-muted">0 item last period</small>
              <div class="mt-2 font-weight-bold">Laporan Kecelakaan</div>
            </div>
          </div>
          <div class="chart-placeholder mt-3">
            <canvas id="chartAccident"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-3">
      <div class="card" style="border-radius: 10px;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="font-weight-bold">0</div>
              <small class="text-success">↑ 5 item last period</small>
              <div class="mt-2 font-weight-bold">Laporan Observasi</div>
            </div>
          </div>
          <div class="chart-placeholder mt-3">
            <canvas id="chartObservation"></canvas>
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