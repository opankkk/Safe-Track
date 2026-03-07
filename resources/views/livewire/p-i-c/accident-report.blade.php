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
    <a href="{{ url('/pic/dashboard') }}">Dashboard</a>
  </li>
  <li class="breadcrumb-item active">Laporan Kerusakan</li>
@endsection

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
            <input type="text" id="tableSearch" class="form-control" placeholder="Cari...">
            <div class="input-group-append">
              <button class="btn btn-default" type="button">
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
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-pdf="{{ asset('storage/bukti/contoh-1.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button
                  class="btn btn-success btn-sm btn-action js-approve"
                  data-id="1"
                  data-nolaporan="AR-2026-0001"
                  data-jenis="First Aid"
                  data-lokasi="Area Loading"
                  data-tanggal="24 Nov 2025, 09:15"
                >
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
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-pdf="{{ asset('storage/bukti/contoh-2.pdf') }}"
                >
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
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-pdf="{{ asset('storage/bukti/contoh-3.pdf') }}"
                >
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
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-pdf="{{ asset('storage/bukti/contoh-4.pdf') }}"
                >
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
          dari <b id="totalRows">4</b>
          data
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
@endsection