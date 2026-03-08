{{-- resources/views/livewire/h-s-e-manager/report.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Perbaikan | Sistem HSE')
@section('menu-perbaikan-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Laporan Perbaikan</span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ url('/hse-manager/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Perbaikan</li>
@endsection

@section('content')
<div class="container-fluid">

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Laporan Perbaikan</h3>

      <div class="card-tools">
        {{-- Filter + Search --}}
        <div class="filter-bar">

          {{-- Filter Jenis Laporan (UA/UC/Accident) --}}
          <select id="filterJenisReport" class="form-control form-control-sm" style="width: 210px;">
            <option value="all">Semua Jenis</option>
            <option value="ua">Unsafe Action</option>
            <option value="uc">Unsafe Condition</option>
            <option value="accident">Accident</option>
          </select>

          {{-- Filter Status Proses --}}
          <select id="filterStatusReport" class="form-control form-control-sm" style="width: 180px;">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="open">Open</option>
            <option value="close">Close</option>
          </select>

          {{-- Search --}}
          <div class="input-group input-group-sm" style="width: 220px;">
            <input type="text" id="reportSearchInput" class="form-control float-right" placeholder="Cari...">
            <div class="input-group-append">
              <button type="button" class="btn btn-default" id="reportSearchBtn">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          {{-- Reset --}}
          <button type="button" class="btn btn-outline-secondary btn-sm" id="reportResetBtn" title="Reset filter & pencarian">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="reportTable">
          <thead>
            <tr>
              <th style="width:170px;" class="text-center">ID Laporan</th>
              <th style="width:220px;">Jenis Laporan</th>
              <th style="width:190px;">Tanggal & Waktu</th>
              <th style="width:150px;" class="text-center">Status</th>
              <th style="width:110px;" class="text-center">Bukti</th>
              <th style="width:260px;" class="text-right">Aksi</th>
            </tr>
          </thead>

          <tbody>
            {{-- 1) Pending + UA --}}
            <tr data-id="1" data-jenis="ua" data-approval="none" data-process-status="pending">
              <td class="text-center id-laporan">RP-2026-0001</td>

              <td>
                <span class="badge badge-warning badge-pillish">
                  <i class="fas fa-user-shield"></i> Unsafe Action
                </span>
              </td>

              <td>02 Mar 2026 09:15</td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish badge-status pending">
                  <i class="fas fa-clock"></i> Pending
                </span>
              </td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBuktiReport"
                  data-pdf="{{ asset('storage/bukti/report-1.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <div class="aksi-wrap">
                  <button
                    type="button"
                    class="btn btn-success btn-sm aksi-btn js-approve"
                    data-id="1"
                    data-id_laporan="RP-2026-0001"
                    data-jenis="Unsafe Action"
                    data-tanggal="02 Mar 2026 09:15"
                  >
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>

                  <button type="button" class="btn btn-danger btn-sm aksi-btn js-reject" data-id="1">
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>

            {{-- 2) Open + UC --}}
            <tr data-id="2" data-jenis="uc" data-approval="approved" data-process-status="open">
              <td class="text-center id-laporan">RP-2026-0002</td>

              <td>
                <span class="badge badge-info badge-pillish">
                  <i class="fas fa-exclamation-triangle"></i> Unsafe Condition
                </span>
              </td>

              <td>01 Mar 2026 14:05</td>

              <td class="text-center js-status-cell">
                <div class="status-wrap">
                  <span class="badge badge-pillish badge-status open">
                    <i class="fas fa-folder-open"></i> Open
                  </span>
                  <small class="status-note">Pending : PIC</small>
                </div>
              </td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBuktiReport"
                  data-pdf="{{ asset('storage/bukti/report-2.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <div class="aksi-wrap">
                  <button type="button" class="btn btn-success btn-sm aksi-btn" disabled>
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button type="button" class="btn btn-danger btn-sm aksi-btn" disabled>
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>

            {{-- 3) Close + UC --}}
            <tr data-id="3" data-jenis="uc" data-approval="approved" data-process-status="close">
              <td class="text-center id-laporan">RP-2026-0003</td>

              <td>
                <span class="badge badge-info badge-pillish">
                  <i class="fas fa-exclamation-triangle"></i> Unsafe Condition
                </span>
              </td>

              <td>28 Feb 2026 10:30</td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish badge-status status-close">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBuktiReport"
                  data-pdf="{{ asset('storage/bukti/report-3.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <div class="aksi-wrap">
                  <button type="button" class="btn btn-success btn-sm aksi-btn" disabled>
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button type="button" class="btn btn-danger btn-sm aksi-btn" disabled>
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>

            {{-- 4) Accident (DISABLED) --}}
            <tr data-id="4" data-jenis="accident" data-approval="approved" data-process-status="close">
              <td class="text-center id-laporan">AC-2026-0004</td>

              <td>
                <span class="badge badge-pillish badge-accident">
                  <i class="fas fa-ambulance"></i> Accident
                </span>
              </td>

              <td>27 Feb 2026 08:10</td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish badge-status status-close">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBuktiReport"
                  data-pdf="{{ asset('storage/bukti/report-4.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <div class="aksi-wrap">
                  <button type="button" class="btn btn-success btn-sm aksi-btn" disabled>
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button type="button" class="btn btn-danger btn-sm aksi-btn" disabled>
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted" id="reportCountInfo">
          Menampilkan <b id="reportShownCount">4</b> data dari <b id="reportTotalCount">4</b> data
        </small>
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary pager-btn" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary pager-btn" disabled>Selanjutnya</button>
        </div>
      </div>

    </div>
  </div>

</div>

{{-- Modal Bukti PDF --}}
<div class="modal fade" id="modalBuktiReport" tabindex="-1" role="dialog" aria-labelledby="modalBuktiReportLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBuktiReportLabel">
          <i class="far fa-file-pdf mr-1"></i> Bukti Laporan (PDF)
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-0" style="height:80vh;">
        <iframe id="pdfFrameReport" src="" style="border:0;width:100%;height:100%;"></iframe>
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
  // Bukti PDF
  $(document).on('click', '.js-open-pdf', function () {
    const pdfUrl = $(this).data('pdf');
    $('#pdfFrameReport').attr('src', pdfUrl);
  });

  $('#modalBuktiReport').on('hidden.bs.modal', function () {
    $('#pdfFrameReport').attr('src', '');
  });

  // Render status proses
  function renderProcessStatus(status, note = '') {
    if (status === 'pending') {
      return `<span class="badge badge-pillish badge-status pending"><i class="fas fa-clock"></i> Pending</span>`;
    }
    if (status === 'open') {
      return `
        <div class="status-wrap">
          <span class="badge badge-pillish badge-status open">
            <i class="fas fa-folder-open"></i> Open
          </span>
          ${note ? `<small class="status-note">${note}</small>` : ``}
        </div>
      `;
    }
    if (status === 'close') {
      return `<span class="badge badge-pillish badge-status status-close"><i class="fas fa-lock"></i> Close</span>`;
    }
    return '';
  }

  // FILTER: jenis + status + search
  function normalizeText(s){
    return (s || '').toString().toLowerCase().trim();
  }

  function applyReportFilters(){
    const jenisVal  = $('#filterJenisReport').val();   // all | ua | uc | accident
    const statusVal = $('#filterStatusReport').val();  // all | pending | open | close
    const keyword   = normalizeText($('#reportSearchInput').val());

    const $rows = $('#reportTable tbody tr');
    const total = $rows.length;

    let shown = 0;

    $rows.each(function(){
      const $row = $(this);

      const rowJenis  = normalizeText($row.attr('data-jenis'));          // ua/uc/accident
      const rowStatus = normalizeText($row.attr('data-process-status')); // pending/open/close
      const rowText   = normalizeText($row.text());

      const matchJenis  = (jenisVal === 'all')  ? true : (rowJenis === jenisVal);
      const matchStatus = (statusVal === 'all') ? true : (rowStatus === statusVal);
      const matchSearch = (!keyword) ? true : rowText.includes(keyword);

      const isShow = matchJenis && matchStatus && matchSearch;

      $row.toggle(isShow);
      if (isShow) shown++;
    });

    $('#reportShownCount').text(shown);
    $('#reportTotalCount').text(total);
  }

  $(document).on('change', '#filterJenisReport, #filterStatusReport', applyReportFilters);
  $(document).on('input', '#reportSearchInput', applyReportFilters);
  $(document).on('click', '#reportSearchBtn', applyReportFilters);

  $(document).on('click', '#reportResetBtn', function(){
    $('#filterJenisReport').val('all');
    $('#filterStatusReport').val('all');
    $('#reportSearchInput').val('');
    applyReportFilters();
  });

  $(document).ready(function(){
    applyReportFilters();
  });

  // Approve flow - LANGSUNG APPROVE TANPA MODAL
  $(document).on('click', '.js-approve', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);
    
    // Hanya proses jika approval masih 'none'
    if ($row.attr('data-approval') !== 'none') return;

    // Update atribut data
    $row.attr('data-approval', 'approved');
    $row.attr('data-process-status', 'open');

    // Update tampilan status
    $row.find('.js-status-cell').html(
      renderProcessStatus('open', 'Pending : PIC')
    );

    // Disable tombol aksi
    $row.find('.js-approve, .js-reject').prop('disabled', true);

    // Refresh filter (untuk konsistensi counter)
    applyReportFilters();
  });

  // Reject
  $(document).on('click', '.js-reject', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);
    if ($row.attr('data-approval') !== 'none') return;

    $row.attr('data-approval', 'rejected');
    $row.attr('data-process-status', 'close');
    $row.find('.js-status-cell').html(renderProcessStatus('close'));

    $row.find('.js-approve, .js-reject').prop('disabled', true);

    applyReportFilters();
  });
</script>
@endpush