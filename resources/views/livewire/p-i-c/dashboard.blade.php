{{-- resources/views/livewire/h-s-e/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard | Sistem HSE')
@section('menu-dashboard-active', 'active')
@section('hide-navbar', true)

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

  /* ==========================
     Notifikasi (CARD ikut warna status)
     Palette:
       open    : #3c8dbc
       close   : #001f3f
       pending : #ff851b
     NOTE: jangan pakai class "close" (bentrok bootstrap .close)
     ========================== */
  .notif-wrap { display:flex; flex-direction:column; gap:14px; }

  /* container scroll */
  .notif-scroll{
    max-height: 350px;     /* atur sesuai kebutuhan */
    overflow-y: auto;
    padding-right: 6px;    /* ruang scrollbar */
  }
  /* scrollbar (opsional, biar rapi) */
  .notif-scroll::-webkit-scrollbar { width: 8px; }
  .notif-scroll::-webkit-scrollbar-track { background: transparent; }
  .notif-scroll::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,.15);
    border-radius: 999px;
  }

  /* item card */
  .notif-item{
    display:flex;
    gap:14px;
    padding:18px 16px;
    border-radius:14px;
    color:#fff;
    width: 100%;
  }
  .notif-item.pending{ background:#ff851b; }
  .notif-item.open{ background:#3c8dbc; }
  .notif-item.status-close{ background:#001f3f; }

  .notif-icon{
    width:46px; height:46px;
    border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    flex: 0 0 46px;
    font-size:18px;
    background: rgba(255,255,255,.16);
    color:#fff;
  }

  .notif-title{
    font-weight:800;
    font-size:15px;
    margin-bottom:2px;
    color:#fff;
  }
  .notif-desc{
    color: rgba(255,255,255,0.86);
    line-height:1.35;
    margin-bottom:10px;
  }
  .notif-time{
    font-size:13px;
    color: rgba(255,255,255,0.92);
    font-weight:600;
  }

  /* link wrapper: wajib block + full width */
  .notif-link{
    display:block;
    width:100%;
    text-decoration:none !important;
    color:inherit;
    border-radius:14px;
  }
  .notif-link:hover{ filter: brightness(0.98); }
  .notif-link:focus{
    outline: none;
    box-shadow: 0 0 0 3px rgba(13,110,253,.18);
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

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

  <div class="row">
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="metric-card g-pink p-3">
        <i class="metric-icon fas fa-notes-medical"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Laporan</div>
        <div class="metric-unit">Total Accident Report</div>
        <div class="metric-dot"></div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
      <div class="metric-card g-orange p-3">
        <i class="metric-icon fas fa-user-times"></i>
        <div class="metric-value">0</div>
        <div class="metric-label">Laporan</div>
        <div class="metric-unit">Unsafe Action</div>
        <div class="metric-dot"></div>
      </div>
    </div>

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

  <div class="row">

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

          {{-- SCROLL WRAPPER --}}
          <div class="mt-3 notif-scroll">
            <div class="notif-wrap" id="notifWrap">
              {{-- fallback jika JS mati --}}
              <a class="notif-link" href="{{ route('pic.accident') }}">
                <div class="notif-item pending">
                  <div class="notif-icon"><i class="fas fa-clock"></i></div>
                  <div class="notif-content">
                    <div class="notif-title">Accident Report - Budi Susanto (Pending)</div>
                    <div class="notif-desc">Laporan dari Budi Susanto pada 24 Nov 2025 di Area Loading belum disetujui. Mohon tindak lanjut.</div>
                    <div class="notif-time">Baru saja</div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

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
  // ======================
  // Chart: Lagging Indicator (dummy front-end)
  // ======================
  const labelsMonthTotal = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Total'];

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

  // ======================
  // Notifikasi (dummy front-end)
  // ======================
  const dummyAccidentRows = [
    { id: 1, nama: 'Budi Susanto', jenis: 'First Aid', departemen: 'Workshop', lokasi: 'Area Loading', tanggal: '24 Nov 2025', process_status: 'pending' },
    { id: 2, nama: 'Siti Rahma', jenis: 'Property Damage', departemen: 'Produksi', lokasi: 'Gudang', tanggal: '01 Jan 2026', process_status: 'open' },
    { id: 3, nama: 'Ahmad Fauzi', jenis: 'Nearmiss', departemen: 'Engineering', lokasi: 'Workshop A', tanggal: '02 Jan 2026', process_status: 'close' },
  ];

  const dummyIncidentRows = [
    { id: 1, nama: 'Budi Susanto', temuan: 'Temuan APD tidak digunakan', jenis: 'Unsafe Action', departemen: 'Workshop', lokasi: 'Workshop A', tanggal: '24 Nov 2025', process_status: 'pending' },
    { id: 2, nama: 'Budiarto', temuan: 'Lantai licin tanpa rambu', jenis: 'Unsafe Condition', departemen: 'Produksi', lokasi: 'Area Loading', tanggal: '01 Jan 2026', process_status: 'open' },
    { id: 3, nama: 'Budiman', temuan: 'Kabel berserakan di jalur pejalan kaki', jenis: 'Unsafe Condition', departemen: 'Engineering', lokasi: 'Gudang', tanggal: '02 Jan 2026', process_status: 'close' },
  ];

  const notifDummy = [
    { status: 'pending', time: 'Baru saja', type: 'accident', ref_id: 1 },
    { status: 'open',    time: '1 jam lalu', type: 'incident', ref_id: 2 },
    { status: 'close',   time: 'Kemarin', type: 'accident', ref_id: 3 },
  ];

  function getNotifMeta(status){
    if (status === 'pending') return { cardClass: 'pending', icon: 'fas fa-clock' };
    if (status === 'open')    return { cardClass: 'open', icon: 'fas fa-folder-open' };
    if (status === 'close')   return { cardClass: 'status-close', icon: 'fas fa-lock' };
    return { cardClass: 'pending', icon: 'fas fa-clock' };
  }

  function getHrefByType(type){
    if (type === 'accident') return `{{ route('pic.accident') }}`;
    if (type === 'incident') return `{{ route('pic.incident') }}`;
    return 'javascript:void(0)';
  }

  function findRow(type, id){
    if (type === 'accident') return dummyAccidentRows.find(r => String(r.id) === String(id));
    if (type === 'incident') return dummyIncidentRows.find(r => String(r.id) === String(id));
    return null;
  }

  function buildTitle(item, row){
    const prefix = item.type === 'accident' ? 'Accident Report' : 'Incident Report';
    const name = row?.nama ? ` - ${row.nama}` : '';
    const statusText = item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : 'Pending';
    return `${prefix}${name} (${statusText})`;
  }

  function buildDesc(item, row){
    const nama = row?.nama || 'Pelapor';
    const tanggal = row?.tanggal ? ` pada ${row.tanggal}` : '';
    const lokasi = row?.lokasi ? ` di ${row.lokasi}` : '';

    if (item.status === 'pending') {
      return `Laporan dari ${nama}${tanggal}${lokasi} belum disetujui. Mohon tindak lanjut.`;
    }
    if (item.status === 'open') {
      return `Laporan dari ${nama}${tanggal}${lokasi} telah disetujui, menunggu persetujuan / tindak lanjut dari PIC.`;
    }
    if (item.status === 'close') {
      return `Laporan dari ${nama}${tanggal}${lokasi} ditolak.`;
    }
    return `Laporan dari ${nama}${tanggal}${lokasi}.`;
  }

  function renderNotifItem(item){
    const m = getNotifMeta(item.status);
    const row = findRow(item.type, item.ref_id);

    const title = buildTitle(item, row);
    const desc = buildDesc(item, row);

    const card = `
      <div class="notif-item ${m.cardClass}">
        <div class="notif-icon"><i class="${m.icon}"></i></div>
        <div class="notif-content">
          <div class="notif-title">${title}</div>
          <div class="notif-desc">${desc}</div>
          <div class="notif-time">${item.time || '-'}</div>
        </div>
      </div>
    `;

    if (item.status === 'pending') {
      const href = getHrefByType(item.type);
      return `<a class="notif-link" href="${href}">${card}</a>`;
    }

    return card;
  }

  const $notifWrap = document.getElementById('notifWrap');
  if ($notifWrap) {
    $notifWrap.innerHTML = notifDummy.map(renderNotifItem).join('');
  }
</script>
@endpush