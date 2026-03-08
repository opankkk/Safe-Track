{{-- resources/views/livewire/p-i-c/accident-report.blade.php --}}
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
            <input type="text" id="accidentSearchInput" class="form-control" placeholder="Cari...">
            <div class="input-group-append">
              <button class="btn btn-default" id="accidentSearchBtn" type="button">
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
              <th class="text-center" style="width:160px;">No Laporan</th>
              <th style="width:180px;">Jenis Insiden</th>
              <th style="width:180px;">Tanggal &amp; Waktu</th>
              <th class="text-center" style="width:150px;">Bukti Tindak Lanjut</th>
              <th class="text-center" style="width:150px;">Bukti Perbaikan</th>
              <th class="text-center" style="width:110px;">Bukti</th>
              <th class="text-center" style="width:150px;">Status</th>
              <th class="text-right" style="width:280px;">Aksi</th>
            </tr>
          </thead>

          <tbody>

            {{-- 1 Pending --}}
            <tr data-id="1" data-process-status="pending" data-approval="none">
              <td class="text-center font-weight-bold">AR-2026-0001</td>
              <td>First Aid</td>
              <td>24 Nov 2025, 09:15</td>

              <td class="text-center js-bukti-tindak-lanjut-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="text-center js-bukti-perbaikan-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                        type="button"
                        data-toggle="modal"
                        data-target="#modalBuktiAccident"
                        data-pdf="{{ asset('storage/bukti/accident-1.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish st-pending">
                  <i class="fas fa-clock"></i> Pending
                </span>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm btn-action js-approve"
                        type="button"
                        data-id="1"
                        data-no-laporan="AR-2026-0001"
                        data-jenis="First Aid"
                        data-departemen="Workshop"
                        data-lokasi="Area Loading"
                        data-tanggal="24 Nov 2025, 09:15"
                        data-uraian="Tangan tergores saat pemindahan material, korban langsung dibersihkan dan diberi antiseptik."
                        data-tindak-lanjut="Penanganan P3K">
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action js-reject" type="button" data-id="1">
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 2 Open --}}
            <tr data-id="2" data-process-status="open" data-approval="approved">
              <td class="text-center font-weight-bold">AR-2026-0002</td>
              <td>Property Damage</td>
              <td>01 Jan 2026, 13:40</td>

              <td class="text-center js-bukti-tindak-lanjut-cell">
                <button class="btn btn-outline-primary btn-sm btn-round-sm" type="button" title="Lihat Bukti Tindak Lanjut">
                  <i class="far fa-file-alt"></i>
                </button>
              </td>

              <td class="text-center js-bukti-perbaikan-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                        type="button"
                        data-toggle="modal"
                        data-target="#modalBuktiAccident"
                        data-pdf="{{ asset('storage/bukti/accident-2.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-center js-status-cell">
                <span class="status-wrap">
                  <span class="badge badge-pillish st-open">
                    <i class="fas fa-folder-open"></i> Open
                  </span>
                  <small class="status-note">Pending : PIC</small>
                </span>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm btn-action" type="button" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action" type="button" disabled>
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 3 Close --}}
            <tr data-id="3" data-process-status="close" data-approval="approved">
              <td class="text-center font-weight-bold">AR-2026-0003</td>
              <td>Nearmiss</td>
              <td>02 Jan 2026, 08:05</td>

              <td class="text-center js-bukti-tindak-lanjut-cell">
                <button class="btn btn-outline-primary btn-sm btn-round-sm" type="button" title="Lihat Bukti Tindak Lanjut">
                  <i class="far fa-file-alt"></i>
                </button>
              </td>

              <td class="text-center js-bukti-perbaikan-cell">
                <button class="btn btn-outline-success btn-sm btn-round-sm" type="button" title="Lihat Bukti Perbaikan">
                  <i class="fas fa-tools"></i>
                </button>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                        type="button"
                        data-toggle="modal"
                        data-target="#modalBuktiAccident"
                        data-pdf="{{ asset('storage/bukti/accident-3.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish st-close">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm btn-action" type="button" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action" type="button" disabled>
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 4 Close (Rejected) --}}
            <tr data-id="4" data-process-status="close" data-approval="rejected">
              <td class="text-center font-weight-bold">AR-2026-0004</td>
              <td>First Aid</td>
              <td>03 Jan 2026, 17:20</td>

              <td class="text-center js-bukti-tindak-lanjut-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="text-center js-bukti-perbaikan-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                        type="button"
                        data-toggle="modal"
                        data-target="#modalBuktiAccident"
                        data-pdf="{{ asset('storage/bukti/accident-4.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish st-close">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm btn-action" type="button" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action" type="button" disabled>
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

{{-- ================= MODAL BUKTI PDF ================= --}}
<div class="modal fade" id="modalBuktiAccident" tabindex="-1" role="dialog" aria-labelledby="modalBuktiAccidentLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBuktiAccidentLabel">
          <i class="far fa-file-pdf mr-1"></i> Bukti Laporan Kerusakan (PDF)
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-0" style="height:80vh;">
        <iframe id="pdfFrameAccident" src="" style="border:0;width:100%;height:100%;"></iframe>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

{{-- ================= MODAL UPLOAD DOKUMEN ================= --}}
<div class="modal fade" id="modalApprovalAccident"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog"
     aria-labelledby="modalApprovalAccidentLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalApprovalAccidentLabel">
          <i class="fas fa-file-upload mr-2"></i>Form Upload Dokumen Laporan Kerusakan
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="approvalAccidentForm" novalidate>
        <div class="modal-body">
          <input type="hidden" id="aaId" name="report_id" value="">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="aaNoLaporan">No Laporan</label>
                <input type="text" class="form-control bg-light" id="aaNoLaporan" name="no_laporan" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="aaJenis">Jenis Insiden</label>
                <input type="text" class="form-control bg-light" id="aaJenis" name="jenis" readonly>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="aaDepartemen">Departemen</label>
                <input type="text" class="form-control bg-light" id="aaDepartemen" name="departemen" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="aaLokasi">Lokasi</label>
                <input type="text" class="form-control bg-light" id="aaLokasi" name="lokasi" readonly>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="aaTanggal">Tanggal & Waktu Insiden</label>
            <input type="text" class="form-control bg-light" id="aaTanggal" name="tanggal" readonly>
          </div>

          <div class="form-group">
            <label for="aaUraian">Uraian Insiden</label>
            <textarea class="form-control bg-light" id="aaUraian" name="uraian" rows="2" readonly></textarea>
          </div>

          <div class="form-group">
            <label for="aaTindakLanjut">Tindak Lanjut Korban</label>
            <textarea class="form-control bg-light" id="aaTindakLanjut" name="tindak_lanjut" rows="2" readonly></textarea>
          </div>

          <hr class="my-4">

          <div class="form-group mb-0">
            <label for="aaDokumen">Upload Dokumen <span class="text-danger">*</span></label>
            <div class="custom-file">
              <input type="file"
                     class="custom-file-input"
                     id="aaDokumen"
                     name="dokumen"
                     accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                     required>
              <label class="custom-file-label" for="aaDokumen">Pilih dokumen...</label>
            </div>
            <div class="invalid-feedback d-block" id="aaDokumenError" style="display:none !important;">
              Harap upload dokumen terlebih dahulu.
            </div>
            <small class="form-text text-muted">
              Format yang diperbolehkan: PDF, DOC, DOCX, JPG, JPEG, PNG.
            </small>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save mr-1"></i> Upload & Simpan
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function normalizeText(s){
    return (s || '').toString().toLowerCase().trim();
  }

  let __pendingApproveRowId = null;

  $(document).on('click', '.js-open-pdf', function () {
    const pdfUrl = $(this).data('pdf');
    $('#pdfFrameAccident').attr('src', pdfUrl);
  });

  $('#modalBuktiAccident').on('hidden.bs.modal', function () {
    $('#pdfFrameAccident').attr('src', '');
  });

  $(document).on('change', '#aaDokumen', function () {
    const fileName = this.files && this.files.length ? this.files[0].name : 'Pilih dokumen...';
    $(this).next('.custom-file-label').text(fileName);

    if (this.files && this.files.length) {
      $('#aaDokumenError').hide();
    }
  });

  $(document).on('click', '.js-approve', function () {
    const $btn = $(this);
    const id = $btn.data('id');
    const $row = $(`tr[data-id="${id}"]`);

    if ($row.attr('data-approval') !== 'none' || $row.attr('data-process-status') !== 'pending') {
      return;
    }

    __pendingApproveRowId = id;

    $('#aaId').val(id);
    $('#aaNoLaporan').val($btn.data('no-laporan') || '');
    $('#aaJenis').val($btn.data('jenis') || '');
    $('#aaDepartemen').val($btn.data('departemen') || '');
    $('#aaLokasi').val($btn.data('lokasi') || '');
    $('#aaTanggal').val($btn.data('tanggal') || '');
    $('#aaUraian').val($btn.data('uraian') || '');
    $('#aaTindakLanjut').val($btn.data('tindak-lanjut') || '');

    $('#approvalAccidentForm').removeClass('was-validated');
    $('#aaDokumen').val('');
    $('#aaDokumen').next('.custom-file-label').text('Pilih dokumen...');
    $('#aaDokumenError').hide();

    $('#modalApprovalAccident').modal('show');
  });

  $('#approvalAccidentForm').on('submit', function (e) {
    e.preventDefault();

    const form = this;
    const id = __pendingApproveRowId;
    const fileInput = document.getElementById('aaDokumen');

    if (!fileInput.files || !fileInput.files.length) {
      $(form).addClass('was-validated');
      $('#aaDokumenError').show();
      return;
    }

    if (!id) return;

    const $row = $(`tr[data-id="${id}"]`);
    const uploadedFileName = fileInput.files[0].name;

    $row.attr('data-approval', 'approved');
    $row.attr('data-process-status', 'open');
    $row.attr('data-uploaded-file', uploadedFileName);

    $row.find('.js-bukti-tindak-lanjut-cell').html(`
      <button class="btn btn-outline-primary btn-sm btn-round-sm" type="button" title="${uploadedFileName}">
        <i class="far fa-file-alt"></i>
      </button>
    `);

    $row.find('.js-status-cell').html(`
      <div class="d-flex flex-column align-items-center" style="gap:.25rem;">
        <span class="badge badge-pillish st-open">
          <i class="fas fa-folder-open"></i> Open
        </span>
        <small class="text-muted" style="font-size:.7rem;">Pending : PIC</small>
      </div>
    `);

    $row.find('.js-approve, .js-reject').prop('disabled', true);

    $('#modalApprovalAccident').modal('hide');
    applyAccidentFilters();
  });

  $('#modalApprovalAccident').on('hidden.bs.modal', function () {
    const form = document.getElementById('approvalAccidentForm');
    form.reset();

    $('#approvalAccidentForm').removeClass('was-validated');
    $('#aaDokumen').next('.custom-file-label').text('Pilih dokumen...');
    $('#aaDokumenError').hide();

    __pendingApproveRowId = null;

    $('#aaId').val('');
    $('#aaNoLaporan').val('');
    $('#aaJenis').val('');
    $('#aaDepartemen').val('');
    $('#aaLokasi').val('');
    $('#aaTanggal').val('');
    $('#aaUraian').val('');
    $('#aaTindakLanjut').val('');
  });

  $(document).on('click', '.js-reject', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);

    if ($row.attr('data-approval') !== 'none') return;

    $row.attr('data-approval', 'rejected');
    $row.attr('data-process-status', 'close');

    $row.find('.js-status-cell').html(`
      <span class="badge badge-pillish st-close">
        <i class="fas fa-lock"></i> Close
      </span>
    `);

    $row.find('.js-approve, .js-reject').prop('disabled', true);

    applyAccidentFilters();
  });

  function applyAccidentFilters(){
    const statusVal = $('#statusFilter').val();
    const keyword = normalizeText($('#accidentSearchInput').val());
    const $rows = $('#accidentTable tbody tr');
    const total = $rows.length;

    let shown = 0;
    let from = 0;
    let to = 0;

    $rows.each(function(i){
      const $row = $(this);
      const rowStatus = normalizeText($row.attr('data-process-status'));
      const rowText = normalizeText($row.text());

      const okStatus = (statusVal === 'all') ? true : (rowStatus === statusVal);
      const okSearch = (!keyword) ? true : rowText.includes(keyword);
      const isShow = okStatus && okSearch;

      $row.toggle(isShow);

      if (isShow) {
        shown++;
        if (from === 0) from = i + 1;
        to = i + 1;
      }
    });

    $('#showFrom').text(shown > 0 ? from : 0);
    $('#showTo').text(shown > 0 ? to : 0);
    $('#totalRows').text(total);
    $('#filterHint').text(statusVal !== 'all' ? ` (Filter: ${statusVal})` : '');
  }

  $(document).on('change', '#statusFilter', applyAccidentFilters);
  $(document).on('input', '#accidentSearchInput', applyAccidentFilters);
  $(document).on('click', '#accidentSearchBtn', applyAccidentFilters);

  $(document).ready(function () {
    applyAccidentFilters();
  });
</script>
@endpush