{{-- resources/views/livewire/p-i-c/accident-report.blade.php --}}
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
  <li class="breadcrumb-item"><a href="{{ url('/pic/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Kerusakan</li>
@endsection

@push('styles')
<style>
  .table td, .table th { vertical-align: middle; }
  .bukti-btn{ padding: .25rem .55rem; border-radius: .4rem; }
  .aksi-btn{ font-weight: 600; border-radius: .4rem; padding: .35rem .75rem; }
  .name-title{ font-weight: 700; margin-bottom: 0; }
  .badge-pillish{
    padding: .5rem .75rem;
    border-radius: .4rem;
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-weight: 600;
  }
  .pager-btn{ border-radius: .4rem; font-weight: 600; padding: .35rem .75rem; }
  .pager-btn:disabled{ opacity: .6; cursor: not-allowed; }

  #modalFollowUp .modal-dialog{ max-width: 900px; }
  #modalFollowUp .modal-content{ max-height: calc(100vh - 3rem); overflow: hidden; }
  #modalFollowUp .modal-body{ overflow-y: auto; max-height: calc(100vh - 3rem - 120px); }
  #modalFollowUp .modal-footer{
    position: sticky;
    bottom: 0;
    background: #fff;
    z-index: 2;
    border-top: 1px solid rgba(0,0,0,.1);
  }

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

  .badge-status{ color:#fff !important; border: 0 !important; }
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

          <div class="callout callout-info mb-3">
            <div class="d-flex flex-wrap" style="gap:14px; line-height:1.35;">
              <div><b>Nama:</b> <span id="fuNama">-</span></div>
              <div><b>Jenis:</b> <span id="fuJenis">-</span></div>
              <div><b>Lokasi:</b> <span id="fuLokasi">-</span></div>
              <div><b>Tanggal:</b> <span id="fuTanggal">-</span></div>
            </div>
          </div>

          <input type="hidden" id="fuId" name="report_id" value="">

          <div class="form-group">
            <label for="fuPic">PIC / Penanggung Jawab <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="fuPic" name="pic" placeholder="Misal: Andi / Tim Maintenance" required>
            <div class="invalid-feedback">Harap isi PIC.</div>
          </div>

          <div class="form-group">
            <label for="fuPrioritas">Prioritas <span class="text-danger">*</span></label>
            <select class="form-control" id="fuPrioritas" name="prioritas" required>
              <option value="">-- pilih --</option>
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
            <div class="invalid-feedback">Harap pilih prioritas.</div>
          </div>

          <div class="form-group">
            <label for="fuDueDate">Target Selesai (Due Date) <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fuDueDate" name="due_date" required>
            <div class="invalid-feedback">Harap isi due date.</div>
          </div>

          <div class="form-group">
            <label for="fuAction">Rencana Tindakan (Corrective Action) <span class="text-danger">*</span></label>
            <textarea class="form-control" id="fuAction" name="action_plan" rows="3" placeholder="Tuliskan tindakan yang akan dilakukan..." required></textarea>
            <div class="invalid-feedback">Harap isi rencana tindakan.</div>
          </div>

          <div class="form-group">
            <label for="fuStatus">Status Tindak Lanjut <span class="text-danger">*</span></label>
            <select class="form-control" id="fuStatus" name="followup_status" required>
              <option value="open" selected>Open</option>
              <option value="progress">On Progress</option>
              <option value="done">Done</option>
            </select>
            <div class="invalid-feedback">Harap pilih status tindak lanjut.</div>
          </div>

          <div class="form-group">
            <label for="fuNote">Catatan (Opsional)</label>
            <textarea class="form-control" id="fuNote" name="note" rows="2" placeholder="Catatan tambahan..."></textarea>
          </div>

          <div class="form-group mb-0">
            <label for="fuFile">Upload Bukti Tindak Lanjut (Opsional)</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="fuFile" name="evidence">
              <label class="custom-file-label" for="fuFile">Pilih file...</label>
            </div>
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
  $(document).on('click', '.js-open-pdf', function () {
    const pdfUrl = $(this).data('pdf');
    $('#pdfFrame').attr('src', pdfUrl);
  });
  $('#modalBukti').on('hidden.bs.modal', function () {
    $('#pdfFrame').attr('src', '');
  });

  $(document).on('change', '.custom-file-input', function (e) {
    const fileName = e.target.files?.[0]?.name || 'Pilih file...';
    $(this).next('.custom-file-label').html(fileName);
  });

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

  let __pendingApproveRowId = null;

  $(document).on('click', '.js-approve', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);
    if ($row.attr('data-approval') !== 'none') return;

    __pendingApproveRowId = id;

    $('#fuId').val(id);
    $('#fuNama').text($(this).data('nama') || '-');
    $('#fuJenis').text($(this).data('jenis') || '-');
    $('#fuLokasi').text($(this).data('lokasi') || '-');
    $('#fuTanggal').text($(this).data('tanggal') || '-');

    $('#followUpForm').removeClass('was-validated');
    $('#modalFollowUp').modal('show');
  });

  $(document).on('click', '.js-reject', function () {
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);
    if ($row.attr('data-approval') !== 'none') return;

    $row.attr('data-approval', 'rejected');
    $row.attr('data-process-status', 'close');
    $row.find('.js-status-cell').html(renderProcessStatus('close'));
    $row.find('.js-approve, .js-reject').prop('disabled', true);
  });

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

    $row.attr('data-approval', 'approved');
    $row.attr('data-process-status', 'open');
    $row.find('.js-status-cell').html(renderProcessStatus('open', 'Menunggu tindak lanjut dari PIC'));
    $row.find('.js-approve, .js-reject').prop('disabled', true);

    $('#modalFollowUp').modal('hide');
  });

  $('#modalFollowUp').on('hidden.bs.modal', function () {
    const form = document.getElementById('followUpForm');
    form.reset();
    $('#followUpForm').removeClass('was-validated');

    __pendingApproveRowId = null;

    $('#fuId').val('');
    $('#fuNama,#fuJenis,#fuLokasi,#fuTanggal').text('-');
    $('.custom-file-label[for="fuFile"]').text('Pilih file...');
  });
</script>
@endpush