{{-- resources/views/livewire/h-s-e/accident-report.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Kerusakan | Sistem HSE')
@section('menu-accident-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">
      Laporan Kerusakan (Accident)
    </span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item">
    <a href="{{ url('/hse/dashboard') }}">Dashboard</a>
  </li>
  <li class="breadcrumb-item active">Laporan Kerusakan</li>
@endsection


{{-- ================= STYLE (Scoped Clean) ================= --}}
@push('styles')
<style>
.page-hse-accident .table td,
.page-hse-accident .table th{
  vertical-align: middle;
}

/* Button */
.page-hse-accident .btn-round-sm{
  padding:.25rem .55rem;
  border-radius:.4rem;
}
.page-hse-accident .btn-action{
  font-weight:600;
  border-radius:.4rem;
  padding:.35rem .75rem;
}

/* Badge */
.page-hse-accident .badge-pillish{
  padding:.5rem .75rem;
  border-radius:.4rem;
  display:inline-flex;
  align-items:center;
  gap:.35rem;
  font-weight:600;
  color:#fff !important;
  border:0 !important;
}
.page-hse-accident .st-pending{ background:#ff851b !important; }
.page-hse-accident .st-open{ background:#3c8dbc !important; }
.page-hse-accident .st-close{ background:#001f3f !important; }

/* Status wrap */
.page-hse-accident .status-wrap{
  display:inline-flex;
  flex-direction:column;
  align-items:center;
  line-height:1.1;
  gap:.25rem;
}
.page-hse-accident .status-note{
  font-size:.75rem;
  color:#6c757d;
  font-weight:600;
  white-space:nowrap;
}

/* Truncate */
.page-hse-accident .truncate-2{
  display:-webkit-box;
  -webkit-line-clamp:2;
  -webkit-box-orient:vertical;
  overflow:hidden;
}

/* Filter & Pager */
.page-hse-accident .filter-select{
  border-radius:.4rem;
  font-weight:600;
}
.page-hse-accident .pager-btn{
  border-radius:.4rem;
  font-weight:600;
  padding:.35rem .75rem;
}
.page-hse-accident .pager-btn:disabled{
  opacity:.6;
  cursor:not-allowed;
}

/* Modal FollowUp */
.page-hse-accident #modalFollowUp .modal-dialog{
  max-width:900px;
}
.page-hse-accident #modalFollowUp .modal-content{
  max-height:calc(100vh - 3rem);
  overflow:hidden;
}
.page-hse-accident #modalFollowUp .modal-body{
  overflow-y:auto;
  max-height:calc(100vh - 3rem - 120px);
}
.page-hse-accident #modalFollowUp .modal-footer{
  position:sticky;
  bottom:0;
  background:#fff;
  z-index:2;
  border-top:1px solid rgba(0,0,0,.1);
}
</style>
@endpush



{{-- ================= CONTENT ================= --}}
@section('content')
<div class="page-hse-accident">
<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Kerusakan</h3>

      <div class="card-tools">
        <div class="d-flex align-items-center" style="gap:.5rem;">

          <select id="statusFilter"
                  class="form-control form-control-sm filter-select"
                  style="width:150px;">
            <option value="all" selected>Semua Status</option>
            <option value="pending">Pending</option>
            <option value="open">Open</option>
            <option value="close">Close</option>
          </select>

          <div class="input-group input-group-sm" style="width:220px;">
            <input type="text" class="form-control" placeholder="Cari...">
            <div class="input-group-append">
              <button class="btn btn-default">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="accidentTable">
          <thead>
            <tr>
              <th class="text-center" style="width:150px;">No Laporan</th>
              <th>Jenis Insiden</th>
              <th style="width:140px;">Departemen</th>
              <th style="width:160px;">Lokasi</th>
              <th style="width:170px;">Tanggal & Waktu</th>
              <th style="min-width:260px;">Uraian Insiden</th>
              <th style="width:190px;">Tindak Lanjut Korban</th>
              <th class="text-center" style="width:160px;">Status</th>
              <th class="text-center" style="width:110px;">Bukti</th>
              <th class="text-right" style="width:280px;">Aksi</th>
            </tr>
          </thead>

          <tbody>

            {{-- 1 Pending --}}
            <tr data-id="1" data-process-status="pending" data-approval="none">
              <td class="text-center font-weight-bold">AR-2026-0001</td>
              <td>First Aid</td>
              <td>Workshop</td>
              <td>Area Loading</td>
              <td>24 Nov 2025, 09:15</td>
              <td>
                <div class="truncate-2">
                  Tangan tergores saat pemindahan material, korban langsung dibersihkan dan diberi antiseptik.
                </div>
              </td>
              <td>Penanganan P3K</td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish st-pending">
                  <i class="fas fa-clock"></i> Pending
                </span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm btn-action js-approve" data-id="1">
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action js-reject" data-id="1">
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 2 Open --}}
            <tr data-id="2" data-process-status="open" data-approval="approved">
              <td class="text-center font-weight-bold">AR-2026-0002</td>
              <td>Property Damage</td>
              <td>Produksi</td>
              <td>Gudang</td>
              <td>01 Jan 2026, 13:40</td>
              <td>
                <div class="truncate-2">
                  Forklift menyenggol rak, menyebabkan kerusakan panel dan 1 pallet jatuh.
                </div>
              </td>
              <td>Tidak ada korban</td>

              <td class="text-center js-status-cell">
                <span class="status-wrap">
                  <span class="badge badge-pillish st-open">
                    <i class="fas fa-folder-open"></i> Open
                  </span>
                  <small class="status-note">Pending : PIC</small>
                </span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm btn-action" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action" disabled>
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 3 Close --}}
            <tr data-id="3" data-process-status="close" data-approval="approved">
              <td class="text-center font-weight-bold">AR-2026-0003</td>
              <td>Nearmiss</td>
              <td>Engineering</td>
              <td>Workshop A</td>
              <td>02 Jan 2026, 08:05</td>
              <td>
                <div class="truncate-2">
                  Material hampir jatuh dari rak karena penguncian kurang sempurna.
                </div>
              </td>
              <td>Preventive action</td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish st-close">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm btn-action" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action" disabled>
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 4 Close (Rejected) --}}
            <tr data-id="4" data-process-status="close" data-approval="rejected">
              <td class="text-center font-weight-bold">AR-2026-0004</td>
              <td>First Aid</td>
              <td>Workshop</td>
              <td>Area Loading</td>
              <td>03 Jan 2026, 17:20</td>
              <td>
                <div class="truncate-2">
                  Pelaporan terlambat dan bukti tidak lengkap, perlu pengajuan ulang sesuai SOP.
                </div>
              </td>
              <td>Penanganan P3K</td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish st-close">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm btn-action" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action" disabled>
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted">
          Menampilkan <b id="showFrom">1</b> sampai <b id="showTo">4</b>
          dari <b id="totalRows">4</b> data
          <span id="filterHint"></span>
        </small>

        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary pager-btn" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary pager-btn" disabled>Selanjutnya</button>
        </div>
      </div>

    </div>
  </div>

</div>
</div>
@endsection