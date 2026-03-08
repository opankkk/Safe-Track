{{-- resources/views/livewire/p-i-c/incident-report.blade.php --}}
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
  <li class="breadcrumb-item"><a href="{{ url('/pic/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Temuan</li>
@endsection

@section('content')
<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Temuan</h3>

      <div class="card-tools">
        <div class="d-flex align-items-center flex-wrap justify-content-end" style="gap:.5rem;">

          <select id="filterJenisIncident" class="form-control form-control-sm font-weight-bold" style="width:190px; border-radius:.4rem;">
            <option value="all">Semua Jenis</option>
            <option value="ua">Unsafe Action</option>
            <option value="uc">Unsafe Condition</option>
          </select>

          <select id="filterStatusIncident" class="form-control form-control-sm font-weight-bold" style="width:180px; border-radius:.4rem;">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="open">Open</option>
            <option value="close">Close</option>
          </select>

          <div class="input-group input-group-sm" style="width:220px;">
            <input type="text" id="incidentSearchInput" class="form-control" placeholder="Cari...">
            <div class="input-group-append">
              <button type="button" class="btn btn-default" id="incidentSearchBtn">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" id="incidentResetBtn"
                  title="Reset filter & pencarian" style="border-radius:.4rem;">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="incidentTable">
          <thead>
            <tr>
              <th style="width:160px;" class="text-center">No Laporan</th>
              <th style="width:240px;">Temuan</th>
              <th style="width:190px;">Jenis</th>
              <th style="width:170px;">Tanggal &amp; Waktu</th>
              <th style="width:165px;" class="text-center">Bukti Tindak Lanjut</th>
              <th style="width:150px;" class="text-center">Bukti Perbaikan</th>
              <th style="width:110px;" class="text-center">Bukti</th>
              <th style="width:140px;" class="text-center">Status</th>
              <th style="width:280px;" class="text-right">Aksi</th>
            </tr>
          </thead>

          <tbody>

            {{-- 1) Pending + UA --}}
            <tr data-id="1" data-jenis="ua" data-approval="none" data-process-status="pending">
              <td class="text-center font-weight-bolder" style="letter-spacing:.2px;">UA-2026-0001</td>

              <td>
                <div class="clamp-2" title="Temuan APD tidak digunakan saat pekerjaan berlangsung.">
                  Temuan APD tidak digunakan saat pekerjaan berlangsung.
                </div>
              </td>

              <td>
                <span class="badge badge-warning px-3 py-2" style="border-radius:.4rem; display:inline-flex; align-items:center; gap:.35rem; font-weight:700;">
                  <i class="fas fa-user-shield"></i> Unsafe Action
                </span>
              </td>

              <td>24 Nov 2025 08:30</td>

              <td class="text-center js-bukti-tindak-lanjut-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="col-bukti-perbaikan js-bukti-perbaikan-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="text-center">
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-open-pdf"
                        style="padding:.25rem .55rem; border-radius:.4rem;"
                        title="Lihat Bukti PDF"
                        data-toggle="modal"
                        data-target="#modalBuktiIncident"
                        data-pdf="{{ asset('storage/bukti/incident-1.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-center js-status-cell">
                <span class="badge px-3 py-2" style="border-radius:.4rem; color:#fff; font-weight:700; background:#ff851b; border:0;">
                  <i class="fas fa-clock"></i> Pending
                </span>
              </td>

              <td class="text-right">
                <div class="d-flex justify-content-end" style="gap:.5rem;">
                  <button type="button"
                          class="btn btn-success btn-sm font-weight-bold js-approve"
                          style="border-radius:.4rem; padding:.35rem .75rem;"
                          data-id="1"
                          data-id_laporan="UA-2026-0001"
                          data-jenis="Unsafe Action"
                          data-temuan="Temuan APD tidak digunakan saat pekerjaan berlangsung."
                          data-departemen="Workshop"
                          data-lokasi="Workshop A"
                          data-tanggal="24 Nov 2025 08:30"
                          data-dampak="Potensi cedera kepala/anggota tubuh karena tidak menggunakan APD."
                          data-perbaikan="Briefing ulang wajib APD, berikan APD yang sesuai, dan lakukan pengawasan rutin.">
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>

                  <button type="button"
                          class="btn btn-danger btn-sm font-weight-bold js-reject"
                          style="border-radius:.4rem; padding:.35rem .75rem;"
                          data-id="1">
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>

            {{-- 2) Open + UC --}}
            <tr data-id="2" data-jenis="uc" data-approval="approved" data-process-status="open">
              <td class="text-center font-weight-bolder" style="letter-spacing:.2px;">UC-2026-0002</td>

              <td>
                <div class="clamp-2" title="Lantai licin tanpa rambu peringatan di jalur operasional.">
                  Lantai licin tanpa rambu peringatan di jalur operasional.
                </div>
              </td>

              <td>
                <span class="badge badge-info px-3 py-2" style="border-radius:.4rem; display:inline-flex; align-items:center; gap:.35rem; font-weight:700;">
                  <i class="fas fa-exclamation-triangle"></i> Unsafe Condition
                </span>
              </td>

              <td>01 Jan 2026 09:10</td>


              <td class="text-center js-bukti-tindak-lanjut-cell">
                <button type="button" class="btn btn-outline-primary btn-sm" style="border-radius:.4rem;" title="Lihat Bukti Tindak Lanjut">
                  <i class="far fa-file-alt"></i>
                </button>
              </td>

              <td class="text-center js-bukti-perbaikan-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="text-center">
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-open-pdf"
                        style="padding:.25rem .55rem; border-radius:.4rem;"
                        title="Lihat Bukti PDF"
                        data-toggle="modal"
                        data-target="#modalBuktiIncident"
                        data-pdf="{{ asset('storage/bukti/incident-2.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-center js-status-cell">
                <div class="d-flex flex-column align-items-center" style="gap:.25rem;">
                  <span class="badge px-3 py-2" style="border-radius:.4rem; color:#fff; font-weight:700; background:#3c8dbc; border:0;">
                    <i class="fas fa-folder-open"></i> Open
                  </span>
                  <small class="text-muted font-weight-bold" style="font-size:.75rem; white-space:nowrap;">Pending : PIC</small>
                </div>
              </td>

              <td class="text-right">
                <div class="d-flex justify-content-end" style="gap:.5rem;">
                  <button type="button" class="btn btn-success btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" disabled>
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button type="button" class="btn btn-danger btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" disabled>
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>

            {{-- 3) Close + UC --}}
            <tr data-id="3" data-jenis="uc" data-approval="approved" data-process-status="close">
              <td class="text-center font-weight-bolder" style="letter-spacing:.2px;">UC-2026-0003</td>

              <td>
                <div class="clamp-2" title="Kabel berserakan di jalur pejalan kaki (trip hazard).">
                  Kabel berserakan di jalur pejalan kaki (trip hazard).
                </div>
              </td>

              <td>
                <span class="badge badge-info px-3 py-2" style="border-radius:.4rem; display:inline-flex; align-items:center; gap:.35rem; font-weight:700;">
                  <i class="fas fa-exclamation-triangle"></i> Unsafe Condition
                </span>
              </td>

              <td>02 Jan 2026 10:05</td>

              <td class="text-center js-bukti-tindak-lanjut-cell">
                <button type="button" class="btn btn-outline-primary btn-sm" style="border-radius:.4rem;" title="Lihat Bukti Tindak Lanjut">
                  <i class="far fa-file-alt"></i>
                </button>
              </td>

              <td class="text-center js-bukti-perbaikan-cell">
                <button type="button" class="btn btn-outline-success btn-sm" style="border-radius:.4rem;" title="Lihat Bukti Perbaikan">
                  <i class="fas fa-tools"></i>
                </button>
              </td>

              <td class="text-center">
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-open-pdf"
                        style="padding:.25rem .55rem; border-radius:.4rem;"
                        title="Lihat Bukti PDF"
                        data-toggle="modal"
                        data-target="#modalBuktiIncident"
                        data-pdf="{{ asset('storage/bukti/incident-3.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-center js-status-cell">
                <span class="badge px-3 py-2" style="border-radius:.4rem; color:#fff; font-weight:700; background:#001f3f; border:0;">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-right">
                <div class="d-flex justify-content-end" style="gap:.5rem;">
                  <button type="button" class="btn btn-success btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" disabled>
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button type="button" class="btn btn-danger btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" disabled>
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>

            {{-- 4) Close + UA (Rejected) --}}
            <tr data-id="4" data-jenis="ua" data-approval="rejected" data-process-status="close">
              <td class="text-center font-weight-bolder" style="letter-spacing:.2px;">UA-2026-0004</td>

              <td>
                <div class="clamp-2" title="Tidak memakai helm di area kerja saat berada di bawah aktivitas lifting.">
                  Tidak memakai helm di area kerja saat berada di bawah aktivitas lifting.
                </div>
              </td>

              <td>
                <span class="badge badge-warning px-3 py-2" style="border-radius:.4rem; display:inline-flex; align-items:center; gap:.35rem; font-weight:700;">
                  <i class="fas fa-user-shield"></i> Unsafe Action
                </span>
              </td>

              <td>03 Jan 2026 14:20</td>

              <td class="col-bukti-tl js-bukti-tindak-lanjut-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="col-bukti-perbaikan js-bukti-perbaikan-cell">
                <span class="text-muted small">-</span>
              </td>

              <td class="text-center">
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-open-pdf"
                        style="padding:.25rem .55rem; border-radius:.4rem;"
                        title="Lihat Bukti PDF"
                        data-toggle="modal"
                        data-target="#modalBuktiIncident"
                        data-pdf="{{ asset('storage/bukti/incident-4.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-center js-status-cell">
                <span class="badge px-3 py-2" style="border-radius:.4rem; color:#fff; font-weight:700; background:#001f3f; border:0;">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-right">
                <div class="d-flex justify-content-end" style="gap:.5rem;">
                  <button type="button" class="btn btn-success btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" disabled>
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button type="button" class="btn btn-danger btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" disabled>
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted">
          Menampilkan <b id="incidentShownCount">4</b> data dari <b id="incidentTotalCount">4</b> data
        </small>

        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary" style="border-radius:.4rem; font-weight:600; padding:.35rem .75rem;" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary" style="border-radius:.4rem; font-weight:600; padding:.35rem .75rem;" disabled>Selanjutnya</button>
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

{{-- Modal Upload Dokumen --}}
<div class="modal fade" id="modalFollowUpIncident"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog"
     aria-labelledby="modalFollowUpIncidentLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalFollowUpIncidentLabel">
          <i class="fas fa-file-upload mr-1"></i> Form Upload Dokumen Laporan Temuan
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="followUpIncidentForm" novalidate>
        <div class="modal-body">
          <input type="hidden" id="ifuId" name="report_id" value="">

          <div class="form-group">
            <label for="ifuIdLaporan">ID Laporan</label>
            <input type="text" class="form-control bg-light" id="ifuIdLaporan" name="id_laporan" readonly>
          </div>

          <div class="form-group">
            <label for="ifuJenis">Jenis</label>
            <input type="text" class="form-control bg-light" id="ifuJenis" name="jenis" readonly>
          </div>

          <div class="form-group">
            <label for="ifuDepartemen">Departemen</label>
            <input type="text" class="form-control bg-light" id="ifuDepartemen" name="departemen" readonly>
          </div>

          <div class="form-group">
            <label for="ifuLokasi">Lokasi</label>
            <input type="text" class="form-control bg-light" id="ifuLokasi" name="lokasi" readonly>
          </div>

          <div class="form-group">
            <label for="ifuTanggal">Tanggal &amp; Waktu</label>
            <input type="text" class="form-control bg-light" id="ifuTanggal" name="tanggal" readonly>
          </div>

          <div class="form-group">
            <label for="ifuTemuan">Temuan</label>
            <textarea class="form-control bg-light" id="ifuTemuan" name="temuan" rows="2" readonly></textarea>
          </div>

          <div class="form-group">
            <label for="ifuDampak">Dampak</label>
            <textarea class="form-control bg-light" id="ifuDampak" name="dampak" rows="2" readonly></textarea>
          </div>

          <div class="form-group">
            <label for="ifuPerbaikan">Perbaikan</label>
            <textarea class="form-control bg-light" id="ifuPerbaikan" name="perbaikan" rows="2" readonly></textarea>
          </div>

          <hr class="my-3">

          <div class="form-group mb-0">
            <label for="ifuDokumen">Upload Dokumen <span class="text-danger">*</span></label>
            <div class="custom-file">
              <input type="file"
                     class="custom-file-input"
                     id="ifuDokumen"
                     name="dokumen"
                     accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                     required>
              <label class="custom-file-label" for="ifuDokumen">Pilih dokumen...</label>
            </div>

            <div class="invalid-feedback d-block" id="ifuDokumenError" style="display:none !important;">
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
            <i class="fas fa-save mr-1"></i> Upload &amp; Simpan
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Bukti PDF
  $(document).on('click', '.js-open-pdf', function () {
    $('#pdfFrameIncident').attr('src', $(this).data('pdf'));
  });

  $('#modalBuktiIncident').on('hidden.bs.modal', function () {
    $('#pdfFrameIncident').attr('src', '');
  });

  // Filter
  function normalizeText(s){
    return (s || '').toString().toLowerCase().trim();
  }

  function applyIncidentFilters(){
    const jenisVal  = $('#filterJenisIncident').val();
    const statusVal = $('#filterStatusIncident').val();
    const keyword   = normalizeText($('#incidentSearchInput').val());

    const $rows = $('#incidentTable tbody tr');
    const total = $rows.length;
    let shown = 0;

    $rows.each(function(){
      const $row = $(this);
      const rowJenis  = normalizeText($row.attr('data-jenis'));
      const rowStatus = normalizeText($row.attr('data-process-status'));
      const rowText   = normalizeText($row.text());

      const okJenis  = (jenisVal === 'all') ? true : (rowJenis === jenisVal);
      const okStatus = (statusVal === 'all') ? true : (rowStatus === statusVal);
      const okSearch = (!keyword) ? true : rowText.includes(keyword);

      const isShow = okJenis && okStatus && okSearch;
      $row.toggle(isShow);
      if (isShow) shown++;
    });

    $('#incidentShownCount').text(shown);
    $('#incidentTotalCount').text(total);
  }

  $(document).on('change', '#filterJenisIncident, #filterStatusIncident', applyIncidentFilters);
  $(document).on('input', '#incidentSearchInput', applyIncidentFilters);
  $(document).on('click', '#incidentSearchBtn', applyIncidentFilters);
  $(document).on('click', '#incidentResetBtn', function(){
    $('#filterJenisIncident').val('all');
    $('#filterStatusIncident').val('all');
    $('#incidentSearchInput').val('');
    applyIncidentFilters();
  });

  $(document).ready(function(){
    applyIncidentFilters();
  });

  // Approve -> modal upload dokumen
  let __pendingApproveRowId = null;

  $(document).on('click', '.js-approve', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);
    if ($row.attr('data-approval') !== 'none') return;

    __pendingApproveRowId = id;

    $('#ifuId').val(id);
    $('#ifuIdLaporan').val($(this).data('id_laporan') || '');
    $('#ifuJenis').val($(this).data('jenis') || '');
    $('#ifuDepartemen').val($(this).data('departemen') || '');
    $('#ifuLokasi').val($(this).data('lokasi') || '');
    $('#ifuTanggal').val($(this).data('tanggal') || '');
    $('#ifuTemuan').val($(this).data('temuan') || '');
    $('#ifuDampak').val($(this).data('dampak') || '');
    $('#ifuPerbaikan').val($(this).data('perbaikan') || '');

    $('#followUpIncidentForm').removeClass('was-validated');
    $('#ifuDokumen').val('');
    $('#ifuDokumen').next('.custom-file-label').text('Pilih dokumen...');
    $('#ifuDokumenError').hide();

    $('#modalFollowUpIncident').modal('show');
  });

  // Tampilkan nama file
  $(document).on('change', '#ifuDokumen', function () {
    const fileName = this.files && this.files.length ? this.files[0].name : 'Pilih dokumen...';
    $(this).next('.custom-file-label').text(fileName);

    if (this.files && this.files.length) {
      $('#ifuDokumenError').hide();
    }
  });

  // Reject -> close + disable
  $(document).on('click', '.js-reject', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);
    if ($row.attr('data-approval') !== 'none') return;

    $row.attr('data-approval', 'rejected');
    $row.attr('data-process-status', 'close');

    $row.find('.js-status-cell').html(`
      <span class="badge px-3 py-2" style="border-radius:.4rem; color:#fff; font-weight:700; background:#001f3f; border:0;">
        <i class="fas fa-lock"></i> Close
      </span>
    `);

    $row.find('.js-approve, .js-reject').prop('disabled', true);
    applyIncidentFilters();
  });

  // Submit upload -> open + note
  $('#followUpIncidentForm').on('submit', function (e) {
    e.preventDefault();

    const id = __pendingApproveRowId;
    if (!id) return;

    const form = this;
    const fileInput = document.getElementById('ifuDokumen');

    if (!fileInput.files || !fileInput.files.length) {
      $(form).addClass('was-validated');
      $('#ifuDokumenError').show();
      return;
    }

    const $row = $(`tr[data-id="${id}"]`);
    const uploadedFileName = fileInput.files[0].name;

    $row.attr('data-approval', 'approved');
    $row.attr('data-process-status', 'open');
    $row.attr('data-uploaded-file', uploadedFileName);

    $row.find('.js-bukti-tindak-lanjut-cell').html(`
      <button type="button" class="btn btn-outline-primary btn-sm" style="border-radius:.4rem;" title="${uploadedFileName}">
        <i class="far fa-file-alt"></i>
      </button>
    `);

    $row.find('.js-status-cell').html(`
      <div class="d-flex flex-column align-items-center" style="gap:.25rem;">
        <span class="badge px-3 py-2" style="border-radius:.4rem; color:#fff; font-weight:700; background:#3c8dbc; border:0;">
          <i class="fas fa-folder-open"></i> Open
        </span>
        <small class="text-muted font-weight-bold" style="font-size:.75rem; white-space:nowrap;">Pending : PIC</small>
      </div>
    `);

    $row.find('.js-approve, .js-reject').prop('disabled', true);
    $('#modalFollowUpIncident').modal('hide');
    applyIncidentFilters();
  });

  // Reset modal
  $('#modalFollowUpIncident').on('hidden.bs.modal', function () {
    document.getElementById('followUpIncidentForm').reset();
    $('#followUpIncidentForm').removeClass('was-validated');
    __pendingApproveRowId = null;

    $('#ifuId').val('');
    $('#ifuIdLaporan,#ifuJenis,#ifuDepartemen,#ifuLokasi,#ifuTanggal').val('');
    $('#ifuTemuan,#ifuDampak,#ifuPerbaikan').val('');
    $('#ifuDokumen').val('');
    $('#ifuDokumen').next('.custom-file-label').text('Pilih dokumen...');
    $('#ifuDokumenError').hide();
  });
</script>
@endpush