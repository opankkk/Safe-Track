{{-- resources/views/livewire/h-s-e-manager/dashboard.blade.php --}}
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

@section('content')
<div class="container-fluid d-flex flex-column"
     style="min-height: calc(100vh - 170px);">

  {{-- METRIC --}}
  <div class="row">
    {{-- Accident --}}
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="p-3 text-white position-relative overflow-hidden"
           style="border-radius:10px; min-height:120px; background:linear-gradient(135deg,#ff4b7d,#d81b60);">
        <i class="fas fa-notes-medical position-absolute"
           style="top:14px; right:14px; opacity:.85; font-size:18px;"></i>

        <div style="font-size:26px; font-weight:800; line-height:1; margin-bottom:4px;">0</div>
        <div style="font-size:12px; opacity:.95;">Laporan</div>
        <div style="font-size:12px; opacity:.95;">Total Accident Report</div>

        <div class="position-absolute"
             style="left:50%; top:65%; transform:translate(-50%,-50%); width:6px; height:6px; background:rgba(255,255,255,.85); border-radius:999px;"></div>
      </div>
    </div>

    {{-- Unsafe Action --}}
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="p-3 text-white position-relative overflow-hidden"
           style="border-radius:10px; min-height:120px; background:linear-gradient(135deg,#ffa000,#f57c00);">
        <i class="fas fa-user-times position-absolute"
           style="top:14px; right:14px; opacity:.85; font-size:18px;"></i>

        <div style="font-size:26px; font-weight:800; line-height:1; margin-bottom:4px;">0</div>
        <div style="font-size:12px; opacity:.95;">Laporan</div>
        <div style="font-size:12px; opacity:.95;">Unsafe Action</div>

        <div class="position-absolute"
             style="left:50%; top:65%; transform:translate(-50%,-50%); width:6px; height:6px; background:rgba(255,255,255,.85); border-radius:999px;"></div>
      </div>
    </div>

    {{-- Unsafe Condition --}}
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="p-3 text-white position-relative overflow-hidden"
           style="border-radius:10px; min-height:120px; background:linear-gradient(135deg,#1e88e5,#1565c0);">
        <i class="fas fa-exclamation-triangle position-absolute"
           style="top:14px; right:14px; opacity:.85; font-size:18px;"></i>

        <div style="font-size:26px; font-weight:800; line-height:1; margin-bottom:4px;">0</div>
        <div style="font-size:12px; opacity:.95;">Laporan</div>
        <div style="font-size:12px; opacity:.95;">Unsafe Condition</div>

        <div class="position-absolute"
             style="left:50%; top:65%; transform:translate(-50%,-50%); width:6px; height:6px; background:rgba(255,255,255,.85); border-radius:999px;"></div>
      </div>
    </div>
  </div>

  {{-- CHART + NOTIF (FULL HEIGHT) --}}
  <div class="row flex-grow-1 align-items-stretch">

    {{-- LEFT: Chart --}}
    <div class="col-lg-9 mb-3 d-flex">
      <div class="card d-flex flex-column w-100" style="border-radius:10px;">
        <div class="card-body d-flex flex-column">

          <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px;">
            <div>
              <div class="font-weight-bold" style="font-size:16px;">Lagging Indicator</div>
              <small class="text-muted">Diagram batang per bulan (stacked) + total.</small>
            </div>

            <div class="d-flex align-items-center" style="gap:8px;">
              <small class="text-muted">Tahun</small>
              <select id="filterYearLagging" class="form-control form-control-sm" style="min-width:120px;">
                <option value="2025" selected>2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
              </select>
            </div>
          </div>

          <div class="mt-3 flex-grow-1" style="min-height:280px;">
            <canvas id="chartLagging"></canvas>
          </div>

        </div>
      </div>
    </div>

    {{-- RIGHT: Notifikasi --}}
    <div class="col-lg-3 mb-3 d-flex">
      <div class="card d-flex flex-column w-100" style="border-radius:10px;">
        <div class="card-body d-flex flex-column">

          <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px;">
            <div>
              <div class="font-weight-bold" style="font-size:18px;">Notifikasi</div>
              <small class="text-muted">Approval laporan Unsafe & Accident.</small>
            </div>
          </div>

          <div class="mt-3 flex-grow-1 overflow-auto" style="padding-right:6px;">
            <div id="notifWrap" class="d-flex flex-column" style="gap:12px;">

              {{-- fallback jika JS mati --}}
              <a href="{{ url('/hse-manager/accident') }}" class="d-block text-decoration-none" style="color:inherit; border-radius:14px;">
                <div class="d-flex text-white"
                     style="gap:12px; padding:14px; border-radius:14px; background:#ff851b;">
                  <div class="d-flex align-items-center justify-content-center"
                       style="width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,.16); flex:0 0 44px; font-size:18px;">
                    <i class="fas fa-clock"></i>
                  </div>
                  <div>
                    <div style="font-weight:800; font-size:15px; margin-bottom:6px; line-height:1.2;">
                      Accident Report - AC-2026-0001 (pending)
                    </div>
                    <div style="font-size:13px; color:rgba(255,255,255,.92); font-weight:600;">Baru saja</div>
                  </div>
                </div>
              </a>

            </div>
          </div>

        </div>
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
    { id: 1, id_laporan: 'AC-2026-0001' },
    { id: 2, id_laporan: 'AC-2026-0002' },
    { id: 3, id_laporan: 'AC-2026-0003' },
  ];

  const dummyIncidentRows = [
    { id: 1, id_laporan: 'UA-2026-0001' },
    { id: 2, id_laporan: 'UC-2026-0002' },
    { id: 3, id_laporan: 'UC-2026-0003' },
  ];

  const notifDummy = [
    { status: 'pending', time: 'Baru saja', type: 'accident', ref_id: 1 },
    { status: 'open',    time: '1 jam lalu', type: 'incident', ref_id: 2 },
    { status: 'close',   time: 'Kemarin', type: 'accident', ref_id: 3 },
  ];

  function getNotifMeta(status){
    if (status === 'pending') return { bg: '#ff851b', icon: 'fas fa-clock' };
    if (status === 'open')    return { bg: '#3c8dbc', icon: 'fas fa-folder-open' };
    if (status === 'close')   return { bg: '#001f3f', icon: 'fas fa-lock' };
    return { bg: '#ff851b', icon: 'fas fa-clock' };
  }

  function getHrefByType(type){
    if (type === 'accident') return `{{ url('/hse-manager/accident') }}`;
    if (type === 'incident') return `{{ url('/hse-manager/incident') }}`;
    return 'javascript:void(0)';
  }

  function findRow(type, id){
    if (type === 'accident') return dummyAccidentRows.find(r => String(r.id) === String(id));
    if (type === 'incident') return dummyIncidentRows.find(r => String(r.id) === String(id));
    return null;
  }

  function renderNotifItem(item){
    const m = getNotifMeta(item.status);
    const row = findRow(item.type, item.ref_id);

    const prefix = item.type === 'accident' ? 'Accident Report' : 'Incident Report';
    const idLaporan = row?.id_laporan || `#${item.ref_id}`;
    const title = `${prefix} - ${idLaporan} (${String(item.status || 'pending').toLowerCase()})`;

    const card = `
      <div class="d-flex text-white" style="gap:12px; padding:14px; border-radius:14px; background:${m.bg};">
        <div class="d-flex align-items-center justify-content-center"
             style="width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,.16); flex:0 0 44px; font-size:18px;">
          <i class="${m.icon}"></i>
        </div>
        <div>
          <div style="font-weight:800; font-size:15px; margin-bottom:6px; line-height:1.2;">${title}</div>
          <div style="font-size:13px; color:rgba(255,255,255,.92); font-weight:600;">${item.time || '-'}</div>
        </div>
      </div>
    `;

    if (item.status === 'pending') {
      const href = getHrefByType(item.type);
      return `<a href="${href}" class="d-block text-decoration-none" style="color:inherit; border-radius:14px;">${card}</a>`;
    }

    return card;
  }

  const $notifWrap = document.getElementById('notifWrap');
  if ($notifWrap) {
    $notifWrap.innerHTML = notifDummy.map(renderNotifItem).join('');
  }
</script>
@endpush