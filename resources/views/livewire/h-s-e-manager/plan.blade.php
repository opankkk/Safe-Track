{{-- resources/views/livewire/h-s-e-manager/plan.blade.php --}}
@extends('layouts.app')

@section('title', 'Plan Tindak Lanjut | Sistem HSE')
@section('menu-plan-tindak-lanjut-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">
      Plan Tindak Lanjut
    </span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item">
    <a href="{{ url('/hse-manager/dashboard') }}">Dashboard</a>
  </li>
  <li class="breadcrumb-item active">Plan Tindak Lanjut</li>
@endsection

@section('content')
<div class="page-hse-plan">
<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Plan Tindak Lanjut</h3>

      <div class="card-tools">
        <div class="d-flex align-items-center" style="gap:.5rem;">

          <div class="input-group input-group-sm" style="width:220px;">
            <input type="text" id="planSearchInput" class="form-control" placeholder="Cari...">
            <div class="input-group-append">
              <button class="btn btn-default" id="planSearchBtn" type="button">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="planTable">
          <thead>
            <tr>
              <th class="text-center" style="width:170px;">ID Laporan</th>
              <th style="width:190px;">Tanggal &amp; Waktu</th>
              <th style="width:220px;">Nama PIC</th>
              <th style="width:160px;">No Telp</th>
              <th class="text-center" style="width:150px;">Status</th>
              <th class="text-center" style="width:110px;">Bukti</th>
              <th class="text-right" style="width:280px;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            {{-- 1 Pending --}}
            <tr data-id="1" data-process-status="pending" data-approval="none">
              <td class="text-center font-weight-bold">PL-2026-0001</td>
              <td>02 Mar 2026, 09:15</td>
              <td>Budi Santoso</td>
              <td>0812-3456-7890</td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish st-pending">
                  <i class="fas fa-clock"></i> Pending
                </span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                        type="button"
                        data-toggle="modal"
                        data-target="#modalBuktiPlan"
                        data-pdf="{{ asset('storage/bukti/plan-1.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button
                  class="btn btn-success btn-sm btn-action js-approve"
                  type="button"
                  data-id="1"
                  data-id_laporan="PL-2026-0001"
                  data-tanggal="02 Mar 2026, 09:15"
                  data-pic="Budi Santoso"
                  data-no_telp="0812-3456-7890"
                >
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm btn-action js-reject" type="button" data-id="1">
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 2 Open --}}
            <tr data-id="2" data-process-status="open" data-approval="approved">
              <td class="text-center font-weight-bold">PL-2026-0002</td>
              <td>01 Mar 2026, 14:05</td>
              <td>Siti Rahma</td>
              <td>0821-1111-2222</td>

              <td class="text-center js-status-cell">
                <span class="status-wrap">
                  <span class="badge badge-pillish st-open">
                    <i class="fas fa-folder-open"></i> Open
                  </span>
                  <small class="status-note">Pending : PIC</small>
                </span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                        type="button"
                        data-toggle="modal"
                        data-target="#modalBuktiPlan"
                        data-pdf="{{ asset('storage/bukti/plan-2.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
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
              <td class="text-center font-weight-bold">PL-2026-0003</td>
              <td>28 Feb 2026, 10:30</td>
              <td>Andi Pratama</td>
              <td>0857-9999-0000</td>

              <td class="text-center js-status-cell">
                <span class="badge badge-pillish st-close">
                  <i class="fas fa-lock"></i> Close
                </span>
              </td>

              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm btn-round-sm js-open-pdf"
                        type="button"
                        data-toggle="modal"
                        data-target="#modalBuktiPlan"
                        data-pdf="{{ asset('storage/bukti/plan-3.pdf') }}">
                  <i class="far fa-file-pdf"></i>
                </button>
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
          Menampilkan <b id="planShownCount">3</b> data dari <b id="planTotalCount">3</b> data
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

{{-- Modal Bukti PDF --}}
<div class="modal fade" id="modalBuktiPlan" tabindex="-1" role="dialog" aria-labelledby="modalBuktiPlanLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBuktiPlanLabel">
          <i class="far fa-file-pdf mr-1"></i> Bukti Plan (PDF)
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-0" style="height:80vh;">
        <iframe id="pdfFramePlan" src="" style="border:0;width:100%;height:100%;"></iframe>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Follow Up (approve flow) --}}
<div class="modal fade" id="modalFollowUpPlan"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog"
     aria-labelledby="modalFollowUpPlanLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalFollowUpPlanLabel">
          <i class="fas fa-clipboard-list mr-1"></i> Form Plan Tindak Lanjut
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="followUpPlanForm" novalidate>
        <div class="modal-body">

          <input type="hidden" id="ppuId" name="plan_id" value="">

          <div class="form-group">
            <label for="ppuIdLaporan">ID Laporan</label>
            <input type="text" class="form-control" id="ppuIdLaporan" name="id_laporan" value="" readonly>
          </div>

          <div class="form-group">
            <label for="ppuTanggal">Tanggal & Waktu</label>
            <input type="text" class="form-control" id="ppuTanggal" name="tanggal" value="" readonly>
          </div>

          <div class="form-group">
            <label for="ppuPic">Nama PIC</label>
            <input type="text" class="form-control" id="ppuPic" name="pic" value="" readonly>
          </div>

          <div class="form-group">
            <label for="ppuNoTelp">No Telp</label>
            <input type="text" class="form-control" id="ppuNoTelp" name="no_telp" value="" readonly>
          </div>

          <hr class="my-3">

          <div class="form-group mb-0">
            <label for="ppuCatatan">Catatan plan tindak lanjut <span class="text-danger">*</span></label>
            <textarea
              class="form-control"
              id="ppuCatatan"
              name="catatan_tindak_lanjut"
              rows="4"
              placeholder="Tuliskan plan catatan tindak lanjut..."
              required
            ></textarea>
            <div class="invalid-feedback">Harap isi catatan tindak lanjut.</div>
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
  // Bukti PDF
  $(document).on('click', '.js-open-pdf', function () {
    $('#pdfFramePlan').attr('src', $(this).data('pdf'));
  });

  $('#modalBuktiPlan').on('hidden.bs.modal', function () {
    $('#pdfFramePlan').attr('src', '');
  });

  // Filter/Search sederhana
  function normalizeText(s){
    return (s || '').toString().toLowerCase().trim();
  }

  function applyPlanSearch(){
    const keyword = normalizeText($('#planSearchInput').val());
    const $rows = $('#planTable tbody tr');
    const total = $rows.length;
    let shown = 0;

    $rows.each(function(){
      const $row = $(this);
      const rowText = normalizeText($row.text());
      const isShow = (!keyword) ? true : rowText.includes(keyword);

      $row.toggle(isShow);
      if (isShow) shown++;
    });

    $('#planShownCount').text(shown);
    $('#planTotalCount').text(total);
  }

  $(document).on('input', '#planSearchInput', applyPlanSearch);
  $(document).on('click', '#planSearchBtn', applyPlanSearch);
  $(document).on('click', '#planResetBtn', function(){
    $('#planSearchInput').val('');
    applyPlanSearch();
  });

  $(document).ready(function(){
    applyPlanSearch();
  });

  // Render status
  function renderStatus(status, note = ''){
    if (status === 'pending') {
      return `
        <span class="badge badge-pillish st-pending">
          <i class="fas fa-clock"></i> Pending
        </span>
      `;
    }

    if (status === 'open') {
      return `
        <span class="status-wrap">
          <span class="badge badge-pillish st-open">
            <i class="fas fa-folder-open"></i> Open
          </span>
          ${note ? `<small class="status-note">${note}</small>` : ``}
        </span>
      `;
    }

    return `
      <span class="badge badge-pillish st-close">
        <i class="fas fa-lock"></i> Close
      </span>
    `;
  }

  // Approve flow via modal
  let __pendingApprovePlanRowId = null;

  $(document).on('click', '.js-approve', function(){
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);
    if ($row.attr('data-approval') !== 'none') return;

    __pendingApprovePlanRowId = id;

    $('#ppuId').val(id);
    $('#ppuIdLaporan').val($(this).data('id_laporan') || '');
    $('#ppuTanggal').val($(this).data('tanggal') || '');
    $('#ppuPic').val($(this).data('pic') || '');
    $('#ppuNoTelp').val($(this).data('no_telp') || '');

    $('#followUpPlanForm').removeClass('was-validated');
    $('#ppuCatatan').val('');

    $('#modalFollowUpPlan').modal('show');
  });

  // Reject
  $(document).on('click', '.js-reject', function(){
    const id = $(this).data('id');
    const $row = $(`tr[data-id="${id}"]`);
    if ($row.attr('data-approval') !== 'none') return;

    $row.attr('data-approval', 'rejected');
    $row.attr('data-process-status', 'close');
    $row.find('.js-status-cell').html(renderStatus('close'));
    $row.find('.js-approve, .js-reject').prop('disabled', true);

    applyPlanSearch();
  });

  // Submit follow-up -> OPEN + note
  $('#followUpPlanForm').on('submit', function(e){
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      e.stopPropagation();
      $(form).addClass('was-validated');
      return;
    }

    const id = __pendingApprovePlanRowId;
    if (!id) return;

    const $row = $(`tr[data-id="${id}"]`);

    $row.attr('data-approval', 'approved');
    $row.attr('data-process-status', 'open');

    $row.find('.js-status-cell').html(
      renderStatus('open', 'Pending : PIC')
    );

    $row.find('.js-approve, .js-reject').prop('disabled', true);

    $('#modalFollowUpPlan').modal('hide');
    applyPlanSearch();
  });

  // Reset modal
  $('#modalFollowUpPlan').on('hidden.bs.modal', function () {
    const form = document.getElementById('followUpPlanForm');
    form.reset();
    $('#followUpPlanForm').removeClass('was-validated');

    __pendingApprovePlanRowId = null;

    $('#ppuId').val('');
    $('#ppuIdLaporan, #ppuTanggal, #ppuPic, #ppuNoTelp').val('');
    $('#ppuCatatan').val('');
  });
</script>
@endpush