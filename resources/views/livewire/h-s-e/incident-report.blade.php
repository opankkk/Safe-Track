{{-- resources/views/livewire/h-s-e/incident-report.blade.php --}}


@section('title', 'Laporan Temuan | Sistem HSE')
@section('menu-incident-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Laporan Ketidaksesuaian (Unsafe Action/Condition)</span>
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
  <li class="breadcrumb-item active">Laporan Temuan</li>
@endsection

<div>
@php $isManager = auth()->check() && auth()->user()->role === 'manager'; @endphp
<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Temuan</h3>

      <div class="card-tools">
        <div class="d-flex align-items-center flex-wrap justify-content-end" style="gap:.5rem;">

          <select wire:model.live="filterJenis" class="form-control form-control-sm font-weight-bold" style="width:190px; border-radius:.4rem;">
            <option value="all">Semua Jenis</option>
            <option value="ua">Unsafe Action</option>
            <option value="uc">Unsafe Condition</option>
          </select>

          <select wire:model.live="filterStatus" class="form-control form-control-sm font-weight-bold" style="width:180px; border-radius:.4rem;">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="open">Open</option>
            <option value="close">Close</option>
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
                  wire:click="$set('filterJenis', 'all'); $set('filterStatus', 'all'); $set('search', '');">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="incidentTable" style="table-layout: fixed; min-width: 1510px;">
          <thead class="bg-light">
            <tr>
              <th class="text-center" style="width:150px; vertical-align:middle;">No Laporan</th>
              <th class="text-center" style="width:150px; vertical-align:middle;">Jenis</th>
              <th class="text-left"   style="width:200px; vertical-align:middle; padding-left:1.5rem;">Temuan</th>
              <th class="text-center" style="width:150px; vertical-align:middle;">Tanggal &amp; Waktu</th>
              <th class="text-center" style="width:180px; vertical-align:middle;">Lokasi Kejadian</th>
              <th class="text-center" style="width:100px; vertical-align:middle;">Bukti</th>
              <th class="text-center" style="width:100px; vertical-align:middle;">Plan</th>
              <th class="text-center" style="width:100px; vertical-align:middle;">Report</th>
              <th class="text-center" style="width:180px; vertical-align:middle;">Status</th>
              <th class="text-center" style="width:200px; vertical-align:middle;">Aksi</th>
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
              $detail  = $report->unsafeDetail ?? null;
              $jenis   = $report->type === 'unsafe_action' ? 'ua' : 'uc';
            @endphp
            <tr data-id="{{ $report->id }}"
                data-jenis="{{ $jenis }}"
                data-process-status="{{ $processStatus }}"
                data-approval="{{ $sub === 'pending_hse' ? 'none' : 'done' }}">

              <td class="text-center font-weight-bolder" style="letter-spacing:.2px; vertical-align:middle;">{{ $report->report_number }}</td>

              <td class="text-center" style="vertical-align:middle;">
                @if($report->type === 'unsafe_action')
                  <span class="badge badge-warning px-3 py-2" style="border-radius:.4rem; display:inline-flex; align-items:center; gap:.35rem; font-weight:700; font-size:.7rem; color:#000 !important;">
                    <i class="fas fa-user-shield"></i> Unsafe Action
                  </span>
                @else
                  <span class="badge badge-info px-3 py-2" style="border-radius:.4rem; display:inline-flex; align-items:center; gap:.35rem; font-weight:700; font-size:.7rem;">
                    <i class="fas fa-exclamation-triangle"></i> Unsafe Condition
                  </span>
                @endif
              </td>

              <td style="vertical-align:middle; padding-left:1.5rem;">
                <div style="font-size:.85rem; line-height:1.4; white-space:normal; word-break:break-word;">
                  {{ $detail?->deskripsi_pengamatan ?? '-' }}
                </div>
              </td>

              <td class="text-center" style="vertical-align:middle;">{{ $report->created_at ? $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}</td>
              <td class="text-center" style="vertical-align:middle;">{{ $detail?->lokasi ?? '-' }}</td>

              {{-- Bukti --}}
              <td class="text-center" style="vertical-align:middle;">
                @if($report->attachments->count())
                  @php $pdfAt = $report->attachments->where('category', 'pdf_report')->first() ?? $report->attachments->first(); @endphp
                  <a href="{{ asset('storage/' . $pdfAt->file_path) }}"
                     target="_blank"
                     class="btn btn-outline-danger btn-sm"
                     style="padding:.25rem .55rem; border-radius:.4rem;"
                     title="Lihat Bukti PDF">
                    <i class="far fa-file-pdf"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              {{-- Plan --}}
              <td class="text-center" style="vertical-align:middle;">
                @if($report->plan && $report->plan->file_path)
                  <a href="{{ asset('storage/' . $report->plan->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm btn-round-sm" title="Lihat Plan">
                    <i class="fas fa-file-invoice"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              {{-- Report --}}
              <td class="text-center" style="vertical-align:middle;">
                @if($report->action && $report->action->file_path)
                  <a href="{{ asset('storage/' . $report->action->file_path) }}" target="_blank" class="btn btn-outline-success btn-sm btn-round-sm" title="Lihat Hasil PIC">
                    <i class="fas fa-tools"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              {{-- Status --}}
              <td class="text-center js-status-cell" style="vertical-align:middle;">
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
                      $subLabel = 'Report : Ditolak Manager';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'report_rejected_hse') {
                      $subLabel = 'Report : Ditolak HSE';
                      $subColor = 'text-grey-status';
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

              {{-- Aksi --}}
              <td class="text-center" style="vertical-align:middle;">
                <div class="d-flex justify-content-center flex-nowrap" style="gap:.5rem;">
                  @if($sub === 'pending_hse' && !$isManager)
                    <button type="button"
                            class="btn btn-success btn-sm font-weight-bold js-approve"
                            style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;"
                            data-id="{{ $report->id }}"
                            data-id_laporan="{{ $report->report_number }}"
                            data-jenis="{{ $report->type === 'unsafe_action' ? 'Unsafe Action' : 'Unsafe Condition' }}"
                            data-temuan="{{ $detail?->deskripsi_pengamatan ?? '-' }}"
                            data-departemen="{{ $detail?->departemen ?? '-' }}"
                            data-lokasi="{{ $detail?->lokasi ?? '-' }}"
                            data-tanggal="{{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}"
                            data-dampak="{{ $detail?->dampak ?? '-' }}"
                            data-perbaikan="{{ $detail?->perbaikan ?? '-' }}"
                            data-manager-note="{{ $report->plan->manager_note ?? '' }}">
                      <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button type="button"
                            class="btn btn-danger btn-sm font-weight-bold js-reject"
                            style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;"
                            data-id="{{ $report->id }}">
                      <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                  @elseif($sub === 'report_verification_hse' && !$isManager)
                    <button type="button"
                            class="btn btn-success btn-sm font-weight-bold js-approve-final"
                            style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;"
                            data-id="{{ $report->id }}">
                      <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button type="button"
                            class="btn btn-danger btn-sm font-weight-bold js-reject-final"
                            style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;"
                            data-id="{{ $report->id }}">
                      <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                  @else
                    <button type="button" class="btn btn-success btn-sm font-weight-bold {{ $isManager ? 'manager-readonly' : '' }}"
                            style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" disabled>
                      <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button type="button" class="btn btn-danger btn-sm font-weight-bold {{ $isManager ? 'manager-readonly' : '' }}"
                            style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" disabled>
                      <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-5">
                <i class="fas fa-inbox" style="font-size:2.5rem; opacity:.3;"></i>
                <div class="mt-2 text-bold">Belum ada laporan masuk.</div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted">
          Menampilkan <b id="incidentShownCount">{{ $reports->count() }}</b> data dari <b id="incidentTotalCount">{{ $reports->count() }}</b> data
        </small>
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary" style="border-radius:.4rem; font-weight:600; padding:.35rem .75rem;" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary" style="border-radius:.4rem; font-weight:600; padding:.35rem .75rem;" disabled>Selanjutnya</button>
        </div>
      </div>
    </div>
  </div>

</div>



{{-- Modal Tindak Lanjut (Approve) --}}
<div class="modal fade" id="modalFollowUpIncident"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-clipboard-list mr-1"></i> Form Tindak Lanjut Temuan</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="followUpIncidentForm" novalidate>
        <div class="modal-body">
          <input type="hidden" id="ifuId" name="report_id" value="">
          
          <div class="form-group mb-0">
            <label>Pengendalian yang disarankan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="ifuPengendalian" rows="4"
                      placeholder="Tuliskan pengendalian yang disarankan..." required></textarea>
            <div class="invalid-feedback">Harap isi pengendalian yang disarankan.</div>
            
            <div id="ifuManagerNoteSection" class="mt-3 p-2 border border-info rounded" style="font-size:13px; background-color: #e7f3ff; display: none;">
              <strong>Catatan Manager:</strong> <span id="ifuManagerNoteText"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-paper-plane mr-1"></i> Setujui &amp; Teruskan ke PIC
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalRejectIncident" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-times-circle mr-2 text-danger"></i>Tolak Laporan</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin <strong class="text-danger">menolak</strong> laporan ini? Laporan akan ditutup.</p>
        <input type="hidden" id="rejectIncidentId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger btn-sm" id="btnConfirmRejectIncident">
          <i class="fas fa-times mr-1"></i> Ya, Tolak
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Kembalikan Final --}}
<div class="modal fade" id="modalRejectFinalIncident" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kembalikan ke PIC</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="rejectFinalIncidentId">
        <div class="form-group mb-0">
          <label>Catatan Pengembalian Hasi <span class="text-danger">*</span></label>
          <textarea class="form-control" id="rejectFinalIncidentNote" rows="3"
                    placeholder="Tuliskan alasan pengembalian..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary btn-sm" id="btnConfirmRejectFinalIncident">
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

  let __pendingApproveRowId = null;

  $(document).on('click', '.js-approve', function () {
    if (IS_MANAGER) return;
    const $btn = $(this);
    __pendingApproveRowId = $btn.data('id');
    $('#ifuId').val(__pendingApproveRowId);
    $('#ifuIdLaporan').val($btn.data('id_laporan') || '');
    $('#ifuJenis').val($btn.data('jenis') || '');
    $('#ifuDepartemen').val($btn.data('departemen') || '');
    $('#ifuLokasi').val($btn.data('lokasi') || '');
    $('#ifuTanggal').val($btn.data('tanggal') || '');
    $('#ifuTemuan').val($btn.data('temuan') || '');
    $('#ifuDampak').val($btn.data('dampak') || '');
    $('#ifuPerbaikan').val($btn.data('perbaikan') || '');

    // Manager Note
    const managerNote = $btn.data('manager-note');
    if (managerNote) {
      $('#ifuManagerNoteText').text(managerNote);
      $('#ifuManagerNoteSection').show();
    } else {
      $('#ifuManagerNoteSection').hide();
    }

    $('#ifuPengendalian').val('');
    $('#followUpIncidentForm').removeClass('was-validated');
    $('#modalFollowUpIncident').modal('show');
  });

  // Submit follow-up → Livewire
  $('#followUpIncidentForm').on('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) {
      e.stopPropagation();
      $(this).addClass('was-validated');
      return;
    }
    const pengendalian = $('#ifuPengendalian').val();
    $('#modalFollowUpIncident').modal('hide');
    window.Livewire.dispatch('hseApproveIncident', { id: parseInt(__pendingApproveRowId), note: pengendalian });
  });

  $('#modalFollowUpIncident').on('hidden.bs.modal', function () {
    document.getElementById('followUpIncidentForm').reset();
    $('#followUpIncidentForm').removeClass('was-validated');
    __pendingApproveRowId = null;
  });

  // Reject
  $(document).on('click', '.js-reject', function () {
    if (IS_MANAGER) return;
    $('#rejectIncidentId').val($(this).data('id'));
    $('#modalRejectIncident').modal('show');
  });

  $('#btnConfirmRejectIncident').on('click', function () {
    const id = $('#rejectIncidentId').val();
    window.Livewire.dispatch('hseRejectIncident', { id: parseInt(id) });
    $('#modalRejectIncident').modal('hide');
  });

  // Approve Final
  $(document).on('click', '.js-approve-final', function () {
    window.Livewire.dispatch('hseApproveFinalIncident', { id: parseInt($(this).data('id')) });
  });

  // Reject Final
  $(document).on('click', '.js-reject-final', function () {
    $('#rejectFinalIncidentId').val($(this).data('id'));
    $('#rejectFinalIncidentNote').val('');
    $('#modalRejectFinalIncident').modal('show');
  });

  $('#btnConfirmRejectFinalIncident').on('click', function () {
    const id   = $('#rejectFinalIncidentId').val();
    const note = $('#rejectFinalIncidentNote').val().trim();
    if (!note) { alert('Catatan pengembalian wajib diisi.'); return; }
    window.Livewire.dispatch('hseRejectFinalIncident', { id: parseInt(id), note: note });
    $('#modalRejectFinalIncident').modal('hide');
  });

</script>
@endpush