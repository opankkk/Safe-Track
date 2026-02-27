{{-- resources/views/livewire/h-s-e/incident-report.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Temuan | Sistem HSE')
@section('menu-incident-active', 'active')
@section('hide-navbar', true)


@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Laporan Temuan (Unsafe)</span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ url('/hse/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Temuan</li>
@endsection

@push('styles')
<style>
  .table td, .table th { vertical-align: middle; }

  /* chip/pill jenis */
  .pill-type{
    display:inline-block;
    padding: .35rem .65rem;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
  }
  .pill-ua { background: rgba(255,193,7,.15); border-color: rgba(255,193,7,.25); color: #b07a00; }
  .pill-uc { background: rgba(13,202,240,.12); border-color: rgba(13,202,240,.25); color: #0b7285; }

  /* bukti button kecil rapi */
  .bukti-btn{ padding: .25rem .55rem; border-radius: .4rem; }

  /* aksi ala AdminLTE projects (rapi, rounded) */
  .aksi-btn{
    font-weight: 600;
    border-radius: .4rem;
    padding: .35rem .75rem;
  }

  /* kolom nama: judul + subtext */
  .name-title{ font-weight: 700; margin-bottom: 2px; }
  .name-sub{ font-size: 12px; color: #6c757d; }

  /* avatar mini */
  .avatar-mini{
    width: 30px; height: 30px;
    border-radius: 999px;
    background: #f4f6f9;
    border: 1px solid #e5e7eb;
    display: inline-block;
    margin-right: .5rem;
    vertical-align: middle;
  }

  /* biar header card serasa AdminLTE */
  .card{ border-radius: .75rem; }
</style>
@endpush

@section('content')
<div class="container-fluid">

  {{-- Header + filter + search --}}
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Laporan Temuan</h3>

      <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 220px;">
          <input type="text" name="table_search" class="form-control float-right" placeholder="Cari...">
          <div class="input-group-append">
            <button type="button" class="btn btn-default">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- Filter bar (opsional, masih front-end) --}}
    <div class="card-body pb-0">
      <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap:12px;">
        <div class="d-flex flex-column" style="gap:10px;">
          {{-- Filter Jenis --}}
          {{-- Filter Status --}}
        </div>
      </div>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0">
          <thead>
            <tr>
              <th style="width:60px;" class="text-center">No</th>
              <th>Nama</th>
              <th style="width:170px;">Jenis</th>
              <th>Departemen</th>
              <th>Lokasi</th>
              <th style="width:140px;">Tanggal</th>
              <th style="width:110px;" class="text-center">Bukti</th>
              <th style="width:260px;" class="text-right">Aksi</th>
            </tr>
          </thead>

          <tbody>

            {{-- 1) Pending + UA --}}
            <tr data-jenis="ua" data-status="pending">
              <td class="text-center">1</td>

              <td>
                <span class="avatar-mini"></span>
                <span class="name-title">Budi Susanto</span>
                <div class="name-sub">Temuan APD tidak digunakan</div>
              </td>

              <td>
                <span class="pill-type pill-ua">Unsafe Action</span>
              </td>

              <td>Workshop</td>
              <td>Workshop A</td>
              <td>24 Nov 2025</td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBuktiIncident"
                  data-pdf="{{ asset('storage/bukti/incident-1.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm aksi-btn">
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm aksi-btn">
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 2) Pending + UC --}}
            <tr data-jenis="uc" data-status="pending">
              <td class="text-center">2</td>

              <td>
                <span class="avatar-mini"></span>
                <span class="name-title">Budiarto</span>
                <div class="name-sub">Lantai licin tanpa rambu</div>
              </td>

              <td>
                <span class="pill-type pill-uc">Unsafe Condition</span>
              </td>

              <td>Produksi</td>
              <td>Area Loading</td>
              <td>01 Jan 2026</td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBuktiIncident"
                  data-pdf="{{ asset('storage/bukti/incident-2.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm aksi-btn">
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm aksi-btn">
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 3) Sudah Disetujui + UC --}}
            <tr data-jenis="uc" data-status="ok">
              <td class="text-center">3</td>

              <td>
                <span class="avatar-mini"></span>
                <span class="name-title">Budiman</span>
                <div class="name-sub">Kabel berserakan di jalur pejalan kaki</div>
              </td>

              <td>
                <span class="pill-type pill-uc">Unsafe Condition</span>
              </td>

              <td>Engineering</td>
              <td>Gudang</td>
              <td>02 Jan 2026</td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBuktiIncident"
                  data-pdf="{{ asset('storage/bukti/incident-3.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <span class="badge badge-success p-2" style="border-radius:.4rem;">
                  <i class="fas fa-check mr-1"></i> Disetujui
                </span>
              </td>
            </tr>

            {{-- 4) Sudah Ditolak + UA --}}
            <tr data-jenis="ua" data-status="no">
              <td class="text-center">4</td>

              <td>
                <span class="avatar-mini"></span>
                <span class="name-title">Rina Putri</span>
                <div class="name-sub">Tidak memakai helm di area kerja</div>
              </td>

              <td>
                <span class="pill-type pill-ua">Unsafe Action</span>
              </td>

              <td>Workshop</td>
              <td>Workshop A</td>
              <td>03 Jan 2026</td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBuktiIncident"
                  data-pdf="{{ asset('storage/bukti/incident-4.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <span class="badge badge-danger p-2" style="border-radius:.4rem;">
                  <i class="fas fa-times mr-1"></i> Ditolak
                </span>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted">Menampilkan <b>1</b> sampai <b>4</b> dari <b>4</b> data</small>
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary" disabled>Selanjutnya</button>
        </div>
      </div>

    </div>
  </div>

</div>

{{-- Modal Bukti PDF --}}
<div class="modal fade" id="modalBuktiIncident" tabindex="-1" role="dialog" aria-labelledby="modalBuktiIncidentLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBuktiIncidentLabel">
          <i class="far fa-file-pdf mr-1"></i> Bukti Laporan (PDF)
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-0" style="height:80vh;">
        <iframe id="pdfFrameIncident" src="" style="border:0;width:100%;height:100%;"></iframe>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Bukti: klik icon pdf -> set src iframe
  $(document).on('click', '.js-open-pdf', function () {
    const pdfUrl = $(this).data('pdf');
    $('#pdfFrameIncident').attr('src', pdfUrl);
  });

  // modal close -> reset src
  $('#modalBuktiIncident').on('hidden.bs.modal', function () {
    $('#pdfFrameIncident').attr('src', '');
  });

  // Filter front-end (optional)
  $(document).on('click', '[data-filter-jenis]', function () {
    // active state
    $(this).closest('.nav').find('.nav-link').removeClass('active');
    $(this).addClass('active');

    const jenis = $(this).data('filter-jenis');
    const status = $('[data-filter-status].active').data('filter-status') || 'all';

    filterRows(jenis, status);
  });

  $(document).on('click', '[data-filter-status]', function () {
    $(this).closest('.nav').find('.nav-link').removeClass('active');
    $(this).addClass('active');

    const status = $(this).data('filter-status');
    const jenis = $('[data-filter-jenis].active').data('filter-jenis') || 'all';

    filterRows(jenis, status);
  });

  function filterRows(jenis, status) {
    const $rows = $('tbody tr[data-jenis][data-status]');
    $rows.show();

    if (jenis !== 'all') {
      $rows.filter(function(){ return $(this).data('jenis') !== jenis; }).hide();
    }
    if (status !== 'all') {
      $rows.filter(function(){ return $(this).data('status') !== status; }).hide();
    }
  }
</script>
@endpush