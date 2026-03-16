{{-- resources/views/livewire/h-s-e/report.blade.php --}}


@section('title', 'Laporan Penanganan | Sistem HSE')
@section('menu-perbaikan-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Laporan Hasil Penanganan</span>
  </div>
@endsection

@push('styles')
<style>
  .badge-status {
    border-radius: .4rem;
    padding: .4rem .8rem;
    font-weight: 700;
    font-size: .75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-transform: uppercase;
    border: none;
  }
  .badge-status-open { background: #007bff; color: white; }
  .badge-status-closed { background: #343a40; color: white; }
  .status-text {
    display: block;
    font-weight: 700;
    font-size: .72rem;
    margin-top: 0.25rem;
    white-space: nowrap;
  }
  .text-orange-status { color: #fd7e14 !important; }
  .text-red-status { color: #dc3545 !important; }
  .text-grey-status { color: #6c757d !important; }
</style>
@endpush

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ url('/hse/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Penanganan</li>
@endsection

<div>
@php $isManager = auth()->check() && auth()->user()->role === 'manager'; @endphp
<div class="container-fluid">

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Laporan Penanganan</h3>

      <div class="card-tools">
        <div class="filter-bar">

          <select wire:model.live="filterJenis" class="form-control form-control-sm font-weight-bold" style="width:210px; border-radius:.4rem;">
            <option value="all">Semua Jenis</option>
            <option value="accident">Accident</option>
            <option value="ua">Unsafe Action</option>
            <option value="uc">Unsafe Condition</option>
          </select>

          <div class="input-group input-group-sm" style="width:220px;">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari...">
            <div class="input-group-append">
              <button type="button" class="btn btn-default">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" 
                  title="Reset filter &amp; pencarian" style="border-radius:.4rem;"
                  wire:click="$set('filterJenis', 'all'); $set('search', '');">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="reportTable">
          <thead>
            <tr>
              <th class="text-center" style="width:150px; white-space:nowrap;">No Laporan</th>
              <th class="text-center" style="width:190px; white-space:nowrap;">Jenis Laporan</th>
              <th class="text-center" style="width:190px; white-space:nowrap;">Tanggal &amp; Waktu</th>
              <th class="text-center" style="width:100px;">Bukti</th>
              <th class="text-center" style="width:100px;">Report</th>
              <th class="text-center" style="width:150px;">Status</th>
              <th class="text-center" style="width:210px;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($reports as $report)
            @php
              $sub = $report->sub_status ?? 'pending_hse';
              $processStatus = 'pending';
              if (in_array($sub, ['waiting_pic','plan_verification','plan_rejected_manager','plan_approved_manager','pic_working','report_pending_hse','report_rejected_manager'])) {
                  $processStatus = 'open';
              } elseif ($sub === 'closed') {
                  $processStatus = 'close';
              }
              $subLabels = [
                'waiting_pic'            => 'Menunggu PIC',
                'plan_verification'      => 'Verifikasi Plan',
                'plan_rejected_manager'  => 'Plan Ditolak',
                'plan_approved_manager'  => 'Plan Disetujui',
                'pic_working'            => 'PIC Pengerjaan',
                'report_pending_hse'     => 'Pending : HSE',
                'report_rejected_manager'=> 'Report Ditolak',
              ];
              $subNote = $subLabels[$sub] ?? '';

              $jenisStr = match($report->type) {
                'unsafe_action'    => 'ua',
                'unsafe_condition' => 'uc',
                default            => 'accident',
              };
              $jenisBadge = match($report->type) {
                'unsafe_action'    => '<span class="badge badge-warning badge-pillish" style="color: #000 !important;"><i class="fas fa-user-shield"></i> Unsafe Action</span>',
                'unsafe_condition' => '<span class="badge badge-info badge-pillish" style="color: #000 !important;"><i class="fas fa-exclamation-triangle"></i> Unsafe Condition</span>',
                default            => '<span class="badge badge-pillish badge-accident" style="background:#dc3545; color:#fff;"><i class="fas fa-ambulance"></i> Accident</span>',
              };
            @endphp
            <tr data-id="{{ $report->id }}"
                data-jenis="{{ $jenisStr }}"
                data-approval="{{ $sub === 'pending_hse' ? 'none' : 'done' }}"
                data-process-status="{{ $processStatus }}">

              <td class="text-center id-laporan">{{ $report->report_number }}</td>

              <td class="text-center">{!! $jenisBadge !!}</td>

              <td class="text-center">{{ $report->created_at ? $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') : ($report->plan ? $report->plan->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-') }}</td>

              <td class="text-center">
                @if($report->attachments->count())
                  @php $pdfAt = $report->attachments->where('category', 'pdf_report')->first() ?? $report->attachments->first(); @endphp
                  <a href="{{ asset('storage/' . $pdfAt->file_path) }}" 
                     target="_blank"
                     class="btn btn-outline-danger btn-sm bukti-btn"
                     style="padding:.25rem .55rem; border-radius:.4rem;"
                     title="Lihat Bukti Awal PDF">
                    <i class="far fa-file-pdf"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              <td class="text-center">
                @if($report->action && $report->action->file_path)
                  <a href="{{ asset('storage/' . $report->action->file_path) }}" 
                     target="_blank" 
                     class="btn btn-outline-success btn-sm action-btn" 
                     title="Lihat Hasil Laporan PIC"
                     style="padding:.25rem .55rem; border-radius:.4rem;">
                    <i class="fas fa-tools"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              <td class="text-center js-status-cell">
                @php
                  $sub = $report->sub_status ?? 'pending_hse';
                  $badgeClass = ($sub === 'closed') ? 'badge-status-closed' : 'badge-status-open';
                  $badgeText = ($sub === 'closed') ? 'CLOSED' : 'OPEN';
                  $badgeIcon = ($sub === 'closed') ? 'fa-lock' : 'fa-folder-open';
                  
                  $subLabel = '';
                  $subColor = 'text-grey-status';
                  
                  if ($sub === 'pending_hse') {
                      $subLabel = 'Pending : Review HSE';
                      $subColor = 'text-orange-status';
                  } elseif ($sub === 'plan_verification') {
                      $subLabel = 'Verifikasi Plan Manager';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'waiting_pic') {
                      $subLabel = 'Dalam Proses PIC';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'pic_working') {
                      $subLabel = 'Dalam Pengerjaan PIC';
                      $subColor = 'text-grey-status';
                  } elseif (in_array($sub, ['report_verification_manager', 'report_pending_hse'])) {
                      $subLabel = 'Verifikasi Hasil Manager';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'report_verification_hse') {
                      $subLabel = 'Pending : Verifikasi Hasil';
                      $subColor = 'text-orange-status';
                  } elseif ($sub === 'report_rejected_manager') {
                      $subLabel = 'Hasil Ditolak Manager';
                      $subColor = 'text-red-status';
                  } elseif ($sub === 'report_rejected_hse') {
                      $subLabel = 'Hasil Ditolak HSE';
                      $subColor = 'text-red-status';
                  } elseif ($sub === 'closed') {
                      $subLabel = 'Selesai';
                      $subColor = 'text-grey-status';
                  } else {
                      $subLabel = \App\Models\Report::subStatusLabel($sub);
                      $subColor = 'text-grey-status';
                  }
                @endphp
                
                <div class="d-flex flex-column align-items-center">
                  <span class="badge-status {{ $badgeClass }}">
                    <i class="fas {{ $badgeIcon }}"></i> {{ $badgeText }}
                  </span>
                  @if($subLabel)
                    <small class="status-text {{ $subColor }}">
                      {{ $subLabel }}
                    </small>
                  @endif
                </div>
              </td>



              <td class="text-center">
                <div class="d-flex justify-content-center flex-nowrap" style="gap:.5rem;">
                  @if($sub === 'report_verification_hse' && !$isManager)
                    @php
                       $incidentType = $report->type === 'accident' ? ($detail?->jenis_insiden ?? 'Accident') : ($report->type === 'unsafe_action' ? 'Unsafe Action' : 'Unsafe Condition');
                       $incidentLocation = $detail?->lokasi_kerja ?? $detail?->lokasi ?? $detail?->tempat ?? '-';
                       $incidentTime = $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i');
                       $incidentDesc = $detail?->uraian_insiden ?? $detail?->deskripsi_pengamatan ?? '-';
                    @endphp
                    <button type="button"
                            class="btn btn-success btn-sm aksi-btn js-approve-final {{ $isManager ? 'manager-readonly' : '' }}"
                            style="white-space:nowrap;"
                            data-id="{{ $report->id }}"
                            data-no-laporan="{{ $report->report_number }}"
                            data-jenis="{{ $incidentType }}"
                            data-lokasi="{{ $incidentLocation }}"
                            data-tanggal="{{ $incidentTime }}"
                            data-uraian="{{ $incidentDesc }}">
                      <i class="fas fa-check mr-1"></i> Setujui Final
                    </button>
                    <button type="button" class="btn btn-warning btn-sm aksi-btn js-reject-final {{ $isManager ? 'manager-readonly' : '' }}"
                            style="white-space:nowrap;"
                            data-id="{{ $report->id }}"
                            data-no-laporan="{{ $report->report_number }}"
                            data-jenis="{{ $incidentType }}"
                            data-lokasi="{{ $incidentLocation }}"
                            data-tanggal="{{ $incidentTime }}"
                            data-uraian="{{ $incidentDesc }}">
                      <i class="fas fa-undo mr-1"></i> Kembalikan
                    </button>
                  @else
                    <button type="button" class="btn btn-success btn-sm aksi-btn {{ $isManager ? 'manager-readonly' : '' }}" style="white-space:nowrap;" disabled>
                      <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button type="button" class="btn btn-danger btn-sm aksi-btn {{ $isManager ? 'manager-readonly' : '' }}" style="white-space:nowrap;" disabled>
                      <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-5">
                <i class="fas fa-inbox" style="font-size:2.5rem; opacity:.3;"></i>
                <div class="mt-2">Belum ada laporan masuk.</div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted" id="reportCountInfo">
          Menampilkan <b id="reportShownCount">{{ $reports->count() }}</b> data dari <b id="reportTotalCount">{{ $reports->count() }}</b> data
        </small>
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary pager-btn" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary pager-btn" disabled>Selanjutnya</button>
        </div>
      </div>
    </div>
  </div>

</div>



{{-- Modal Setujui Final --}}
<div class="modal fade" id="modalApproveFinalReport" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-check-circle mr-2 text-success"></i>Setujui Laporan Final</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="approveFinalReportId">
        <p class="mb-0 text-muted">Apakah Anda yakin hasil penanganan telah selesai dan setuju untuk menutup laporan ini (Close)?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success btn-sm" id="btnConfirmApproveFinalReport">
          <i class="fas fa-check mr-1"></i> Ya, Setujui & Tutup
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Kembalikan ke PIC --}}
<div class="modal fade" id="modalRejectFinalReport" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-undo mr-2 text-warning"></i>Kembalikan ke PIC</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="rejectFinalReportId">
        <div class="form-group mb-0">
          <label>Catatan Pengembalian <span class="text-danger">*</span></label>
          <textarea class="form-control" id="rejectFinalReportNote" rows="3"
                    placeholder="Tuliskan alasan pengembalian ke PIC..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning btn-sm" id="btnConfirmRejectFinalReport">
          <i class="fas fa-paper-plane mr-1"></i> Kembalikan ke PIC
        </button>
      </div>
    </div>
  </div>
</div>
</div>

@push('scripts')
@if($isManager)
<style>
  .manager-readonly { opacity:0.4 !important; cursor:not-allowed !important; pointer-events:none; }
</style>
@endif
<script>
  @if($isManager) var IS_MANAGER = true; @else var IS_MANAGER = false; @endif

  // Bukti PDF


  function renderProcessStatus(status, note) {
    if (status === 'pending') return `<span class="badge badge-pillish badge-status pending"><i class="fas fa-clock"></i> Pending</span>`;
    if (status === 'open') return `<div class="status-wrap"><span class="badge badge-pillish badge-status open"><i class="fas fa-folder-open"></i> Open</span>${note ? `<small class="status-note">${note}</small>` : ''}</div>`;
    return `<span class="badge badge-pillish badge-status status-close"><i class="fas fa-lock"></i> Close</span>`;
  }

  // Approve Final
  $(document).on('click', '.js-approve-final', function () {
    if (IS_MANAGER) return;
    const btn = $(this);
    $('#approveFinalReportId').val(btn.data('id'));
    $('#approveLaporanNo').text(btn.data('no-laporan'));
    $('#approveLaporanJenis').text(btn.data('jenis'));
    $('#approveLaporanLokasi').text(btn.data('lokasi'));
    $('#approveLaporanTanggal').text(btn.data('tanggal'));
    $('#approveLaporanUraian').text(btn.data('uraian'));
    $('#modalApproveFinalReport').modal('show');
  });

  $('#btnConfirmApproveFinalReport').on('click', function () {
    const id = $('#approveFinalReportId').val();
    window.Livewire.dispatch('hseApproveFinalReport', { id: parseInt(id) });
    $('#modalApproveFinalReport').modal('hide');
  });

  // Reject Final
  $(document).on('click', '.js-reject-final', function () {
    if (IS_MANAGER) return;
    const btn = $(this);
    $('#rejectFinalReportId').val(btn.data('id'));
    $('#rejectLaporanNo').text(btn.data('no-laporan'));
    $('#rejectLaporanJenis').text(btn.data('jenis'));
    $('#rejectLaporanLokasi').text(btn.data('lokasi'));
    $('#rejectLaporanTanggal').text(btn.data('tanggal'));
    $('#rejectLaporanUraian').text(btn.data('uraian'));
    $('#rejectFinalReportNote').val('');
    $('#modalRejectFinalReport').modal('show');
  });

  $('#btnConfirmRejectFinalReport').on('click', function () {
    const id   = $('#rejectFinalReportId').val();
    const note = $('#rejectFinalReportNote').val().trim();
    if (!note) { alert('Catatan pengembalian wajib diisi.'); return; }
    window.Livewire.dispatch('hseRejectFinalReport', { id: parseInt(id), note: note });
    $('#modalRejectFinalReport').modal('hide');
  });

</script>
@endpush