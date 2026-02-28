{{-- resources/views/livewire/h-s-e/accident-report.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Kerusakan | Sistem HSE')
@section('menu-accident-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Laporan Kerusakan (Accident)</span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ url('/hse/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Kerusakan</li>
@endsection

@push('styles')
<style>
  .table td, .table th { vertical-align: middle; }

  /* bukti button kecil rapi */
  .bukti-btn{ padding: .25rem .55rem; border-radius: .4rem; }

  /* aksi ala AdminLTE projects (rapi, rounded) */
  .aksi-btn{
    font-weight: 600;
    border-radius: .4rem;
    padding: .35rem .75rem;
  }

  /* teks nama */
  .name-title{ font-weight: 700; margin-bottom: 0; }

  /* badge konsisten */
  .badge-pillish{
    padding: .5rem .75rem;
    border-radius: .4rem;
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-weight: 600;
  }

  /* pagination button konsisten */
  .pager-btn{
    border-radius: .4rem;
    font-weight: 600;
    padding: .35rem .75rem;
  }
  .pager-btn:disabled{
    opacity: .6;
    cursor: not-allowed;
  }

  /* ==========================
     FIX: modal follow-up scroll + footer always visible
     ========================== */
  #modalFollowUp .modal-dialog{
    max-width: 900px;
  }
  #modalFollowUp .modal-content{
    max-height: calc(100vh - 3rem);
    overflow: hidden;
  }
  #modalFollowUp .modal-body{
    overflow-y: auto;
    max-height: calc(100vh - 3rem - 120px); /* header + footer */
  }
  #modalFollowUp .modal-footer{
    position: sticky;
    bottom: 0;
    background: #fff;
    z-index: 2;
    border-top: 1px solid rgba(0,0,0,.1);
  }

  /* ==========================
     Status open note kecil
     ========================== */
  .status-wrap{
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    line-height: 1.1;
    gap: .25rem;
  }
  .status-note{
    font-size: .75rem;
    color: #6c757d;
    font-weight: 600;
    white-space: nowrap;
  }

  /* ==========================
     Palette status (custom)
     open    : #3c8dbc
     close   : #001f3f
     pending : #ff851b
     NOTE: JANGAN pakai class "close" (bentrok bootstrap .close)
     ========================== */
  .badge-status{
    color:#fff !important;
    border: 0 !important;
  }
  .badge-status.pending{ background:#ff851b !important; }
  .badge-status.open{ background:#3c8dbc !important; }
  .badge-status.status-close{ background:#001f3f !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Kerusakan</h3>

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

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0">
          <thead>
            <tr>
              <th style="width: 60px;" class="text-center">No</th>
              <th>Nama</th>
              <th>Jenis Insiden</th>
              <th>Departemen</th>
              <th>Lokasi</th>
              <th style="width: 140px;">Tanggal</th>
              <th style="width: 140px;" class="text-center">Status</th>
              <th style="width: 110px;" class="text-center">Bukti</th>
              <th style="width: 280px;" class="text-right">Aksi</th>
            </tr>
          </thead>

          <tbody>

            {{-- 1) Pending --}}
            <tr data-id="1" data-process-status="pending" data-approval="none">
              <td class="text-center">1</td>

              <td><div class="name-title">Budi Susanto</div></td>
              <td>First Aid</td>
              <td>Workshop</td>
              <td>Area Loading</td>
              <td>24 Nov 2025</td>

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
                  data-target="#modalBukti"
                  data-pdf="{{ asset('storage/bukti/contoh-1.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button
                  type="button"
                  class="btn btn-success btn-sm aksi-btn js-approve"
                  data-id="1"
                  data-nama="Budi Susanto"
                  data-jenis="First Aid"
                  data-lokasi="Area Loading"
                  data-tanggal="24 Nov 2025"
                >
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>

                <button type="button" class="btn btn-danger btn-sm aksi-btn js-reject" data-id="1">
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 2) Open (misal sudah disetujui) --}}
            <tr data-id="2" data-process-status="open" data-approval="approved">
              <td class="text-center">2</td>

              <td><div class="name-title">Siti Rahma</div></td>
              <td>Property Damage</td>
              <td>Produksi</td>
              <td>Gudang</td>
              <td>01 Jan 2026</td>

              <td class="text-center js-status-cell">
                <span class="status-wrap">
                  <span class="badge badge-pillish badge-status open">
                    <i class="fas fa-folder-open"></i> Open
                  </span>
                  <small class="status-note">Menunggu tindak lanjut dari PIC</small>
                </span>
              </td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBukti"
                  data-pdf="{{ asset('storage/bukti/contoh-2.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button type="button" class="btn btn-success btn-sm aksi-btn" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button type="button" class="btn btn-danger btn-sm aksi-btn" disabled>
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 3) Close (misal sudah selesai ditangani) --}}
            <tr data-id="3" data-process-status="close" data-approval="approved">
              <td class="text-center">3</td>

              <td><div class="name-title">Ahmad Fauzi</div></td>
              <td>Nearmiss</td>
              <td>Engineering</td>
              <td>Workshop A</td>
              <td>02 Jan 2026</td>

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
                  data-target="#modalBukti"
                  data-pdf="{{ asset('storage/bukti/contoh-3.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button type="button" class="btn btn-success btn-sm aksi-btn" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button type="button" class="btn btn-danger btn-sm aksi-btn" disabled>
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 4) Close (ditolak) --}}
            <tr data-id="4" data-process-status="close" data-approval="rejected">
              <td class="text-center">4</td>

              <td><div class="name-title">Rina Putri</div></td>
              <td>First Aid</td>
              <td>Workshop</td>
              <td>Area Loading</td>
              <td>03 Jan 2026</td>

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
                  data-target="#modalBukti"
                  data-pdf="{{ asset('storage/bukti/contoh-4.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button type="button" class="btn btn-success btn-sm aksi-btn" disabled>
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button type="button" class="btn btn-danger btn-sm aksi-btn" disabled>
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted">Menampilkan <b>1</b> sampai <b>4</b> dari <b>4</b> data</small>
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary pager-btn" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary pager-btn" disabled>Selanjutnya</button>
        </div>
      </div>

    </div>
  </div>

</div>

{{-- Modal Bukti PDF --}}
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog" aria-labelledby="modalBuktiLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBuktiLabel">
          <i class="far fa-file-pdf mr-1"></i> Bukti Laporan (PDF)
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-0" style="height:80vh;">
        <iframe id="pdfFrame" src="" style="border:0;width:100%;height:100%;"></iframe>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tindak Lanjut (REVISI) --}}
<div class="modal fade" id="modalFollowUp"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog"
     aria-labelledby="modalFollowUpLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFollowUpLabel">
          <i class="fas fa-clipboard-list mr-1"></i> Form Tindak Lanjut
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="followUpForm" novalidate>
        <div class="modal-body">

          {{-- Info laporan: dibuat jadi 4 form (read-only) --}}
          <input type="hidden" id="fuId" name="report_id" value="">

          <div class="form-group">
            <label for="fuNama">Nama</label>
            <input type="text" class="form-control" id="fuNama" name="nama" value="" readonly>
          </div>

          <div class="form-group">
            <label for="fuJenis">Jenis Insiden</label>
            <input type="text" class="form-control" id="fuJenis" name="jenis" value="" readonly>
          </div>

          <div class="form-group">
            <label for="fuLokasi">Lokasi</label>
            <input type="text" class="form-control" id="fuLokasi" name="lokasi" value="" readonly>
          </div>

          <div class="form-group">
            <label for="fuTanggal">Tanggal</label>
            <input type="text" class="form-control" id="fuTanggal" name="tanggal" value="" readonly>
          </div>

          <hr class="my-3">

          {{-- Isian follow-up: tinggal 1 field --}}
          <div class="form-group mb-0">
            <label for="fuPengendalian">Pengendalian yang disarankan <span class="text-danger">*</span></label>
            <textarea
              class="form-control"
              id="fuPengendalian"
              name="pengendalian_disarankan"
              rows="4"
              placeholder="Tuliskan pengendalian yang disarankan..."
              required
            ></textarea>
            <div class="invalid-feedback">Harap isi pengendalian yang disarankan.</div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save mr-1"></i> Simpan Tindak Lanjut
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // ======================
  // Bukti PDF
  // ======================
  $(document).on('click', '.js-open-pdf', function () {
    const pdfUrl = $(this).data('pdf');
    $('#pdfFrame').attr('src', pdfUrl);
  });
  $('#modalBukti').on('hidden.bs.modal', function () {
    $('#pdfFrame').attr('src', '');
  });

  // ======================
  // Render status proses (palette custom)
  // ======================
  function renderProcessStatus(status, note = '') {
    if (status === 'pending') {
      return `<span class="badge badge-pillish badge-status pending"><i class="fas fa-clock"></i> Pending</span>`;
    }
    if (status === 'open') {
      return `
        <span class="status-wrap">
          <span class="badge badge-pillish badge-status open">
            <i class="fas fa-folder-open"></i> Open
          </span>
          ${note ? `<small class="status-note">${note}</small>` : ``}
        </span>
      `;
    }
    if (status === 'close') {
      return `<span class="badge badge-pillish badge-status status-close"><i class="fas fa-lock"></i> Close</span>`;
    }
    return '';
  }

  // ======================
  // Setujui: buka modal dulu, JANGAN ubah status sebelum submit
  // ======================
  let __pendingApproveRowId = null;

  $(document).on('click', '.js-approve', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);

    if ($row.attr('data-approval') !== 'none') return;

    __pendingApproveRowId = id;

    // isi 4 form info (read-only)
    $('#fuId').val(id);
    $('#fuNama').val($(this).data('nama') || '');
    $('#fuJenis').val($(this).data('jenis') || '');
    $('#fuLokasi').val($(this).data('lokasi') || '');
    $('#fuTanggal').val($(this).data('tanggal') || '');

    // reset validasi + textarea pengendalian
    $('#followUpForm').removeClass('was-validated');
    $('#fuPengendalian').val('');

    $('#modalFollowUp').modal('show');
  });

  // ======================
  // Tolak: langsung close + disable tombol
  // ======================
  $(document).on('click', '.js-reject', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);

    if ($row.attr('data-approval') !== 'none') return;

    $row.attr('data-approval', 'rejected');
    $row.attr('data-process-status', 'close');
    $row.find('.js-status-cell').html(renderProcessStatus('close'));

    $row.find('.js-approve, .js-reject').prop('disabled', true);
  });

  // ======================
  // Submit follow-up: ubah status jadi OPEN + note
  // (di sini kamu bisa sambungkan AJAX/Livewire untuk simpan DB)
  // ======================
  $('#followUpForm').on('submit', function (e) {
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      e.stopPropagation();
      $(form).addClass('was-validated');
      return;
    }

    const id = __pendingApproveRowId;
    if (!id) return;

    const $row = $(`tr[data-id="${id}"]`);

    // contoh: ambil isi pengendalian (kalau mau dipakai untuk request simpan)
    const pengendalian = ($('#fuPengendalian').val() || '').trim();

    $row.attr('data-approval', 'approved');
    $row.attr('data-process-status', 'open');

    $row.find('.js-status-cell').html(
      renderProcessStatus('open', 'Menunggu tindak lanjut dari PIC')
    );

    $row.find('.js-approve, .js-reject').prop('disabled', true);

    $('#modalFollowUp').modal('hide');

    // NOTE: pengendalian variabel siap kamu kirim ke backend via AJAX/Livewire
    // console.log({ id, pengendalian });
  });

  // ======================
  // Reset form modal
  // ======================
  $('#modalFollowUp').on('hidden.bs.modal', function () {
    const form = document.getElementById('followUpForm');
    form.reset();
    $('#followUpForm').removeClass('was-validated');

    __pendingApproveRowId = null;

    $('#fuId').val('');
    $('#fuNama,#fuJenis,#fuLokasi,#fuTanggal').val('');
    $('#fuPengendalian').val('');
  });
</script>
@endpush