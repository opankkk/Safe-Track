{{-- resources/views/livewire/h-s-e/accident-report.blade.php --}}


@section('title', 'Laporan Kecelakaan | Sistem HSE')
@section('menu-accident-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">
      Laporan Kecelakaan (Accident)
    </span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item">
    <a href="{{ url('/hse/dashboard') }}">Dashboard</a>
  </li>
  <li class="breadcrumb-item active">Laporan Kecelakaan</li>
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

<div>
@php $isManager = auth()->check() && auth()->user()->role === 'manager'; @endphp
<div class="page-hse-accident">
<div class="container-fluid">
  {{-- Notifikasi ditangani oleh Toast Global --}}

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Kecelakaan</h3>

      <div class="card-tools">
        <div class="d-flex align-items-center flex-wrap justify-content-end" style="gap:.5rem;">

          <select wire:model.live="filterStatus"
                  class="form-control form-control-sm font-weight-bold"
                  style="width:180px; border-radius:.4rem; height: 31px;">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="open">Open</option>
            <option value="close">Close</option>
          </select>

          <div class="input-group input-group-sm" style="width:220px;">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari..." style="height: 31px;">
            <div class="input-group-append">
              <button class="btn btn-default" type="button" style="height: 31px;">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" 
                  title="Reset filter & pencarian" style="border-radius:.4rem; height: 31px;" 
                  wire:click="$set('filterStatus', 'all'); $set('search', '');">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="accidentTable" style="table-layout: fixed; min-width: 1420px;">
          <thead class="bg-light">
            <tr>
              <th class="text-center" style="width:180px; vertical-align:middle;">No Laporan</th>
              <th class="text-center" style="width:140px; vertical-align:middle;">Jenis Insiden</th>
              <th class="text-center" style="width:160px; vertical-align:middle;">Tanggal &amp; Waktu</th>
              <th class="text-center" style="width:140px; vertical-align:middle;">Lokasi Kejadian</th>
              <th class="text-center" style="width:280px; vertical-align:middle;">Uraian Insiden</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Bukti</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Plan</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Report</th>
              <th class="text-center" style="width:130px; vertical-align:middle;">Status</th>
              <th class="text-center" style="width:150px; vertical-align:middle;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($reports as $report)
            @php
              $sub = $report->sub_status ?? 'pending_hse';
              // Map sub_status → simple status (pending/open/close)
              $processStatus = 'pending';
              if (in_array($sub, ['waiting_pic','plan_verification','plan_rejected_manager','plan_approved_manager','pic_working','report_pending_hse','report_rejected_manager'])) {
                  $processStatus = 'open';
              } elseif ($sub === 'closed') {
                  $processStatus = 'close';
              }

              // Detail accident
              $detail = $report->accidentDetail ?? null;
            @endphp
            <tr data-id="{{ $report->id }}"
                data-process-status="{{ $processStatus }}"
                data-approval="{{ $sub === 'pending_hse' ? 'none' : 'done' }}">

              <td class="text-center font-weight-bold">{{ $report->report_number }}</td>
              <td class="text-center">
                <span class="badge badge-secondary px-2 py-2" style="border-radius:.4rem; font-size:.7rem;">
                  {{ $detail?->jenis_insiden ?? '-' }}
                </span>
              </td>
              <td class="text-center">{{ $report->created_at ? $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}</td>
              <td class="text-center">{{ $detail?->tempat ?? '-' }}</td>
              <td><div style="font-size:.85rem; line-height:1.4;">{{ $detail?->uraian_insiden ?? '-' }}</div></td>


              {{-- Bukti Awal PDF --}}
              <td class="text-center">
                @if($report->attachments->count())
                  @php $pdfAt = $report->attachments->where('category', 'pdf_report')->first() ?? $report->attachments->first(); @endphp
                  <a href="{{ asset('storage/' . $pdfAt->file_path) }}" 
                     target="_blank"
                     class="btn btn-outline-danger btn-sm btn-round-sm"
                     title="Lihat Bukti Awal PDF">
                    <i class="far fa-file-pdf"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              {{-- Plan --}}
              <td class="text-center">
                @if($report->plan && $report->plan->file_path)
                  <a href="{{ asset('storage/' . $report->plan->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm btn-round-sm" title="Lihat Plan">
                    <i class="fas fa-file-invoice"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              {{-- Report/Hasil --}}
              <td class="text-center">
                @if($report->action && $report->action->file_path)
                  <a href="{{ asset('storage/' . $report->action->file_path) }}" target="_blank" class="btn btn-outline-success btn-sm btn-round-sm" title="Lihat Hasil PIC">
                    <i class="fas fa-tools"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              {{-- Status --}}
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
              <td class="text-center">
                <div class="d-flex justify-content-center flex-nowrap" style="gap:.5rem;">
                  @if($sub === 'pending_hse' && !$isManager)
                    <button type="button"
                            class="btn btn-success btn-sm font-weight-bold js-approve"
                            style="border-radius:.4rem; white-space:nowrap;"
                            data-wire-id="{{ $report->id }}"
                            data-id="{{ $report->id }}"
                            data-no-laporan="{{ $report->report_number }}"
                            data-jenis="{{ $detail?->jenis_insiden ?? '-' }}"
                            data-departemen="{{ $detail?->departemen ?? '-' }}"
                            data-lokasi-kerja="{{ $detail?->lokasi_kerja ?? '-' }}"
                            data-tempat="{{ $detail?->tempat ?? '-' }}"
                            data-tanggal="{{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}"
                            data-uraian="{{ $detail?->uraian_insiden ?? '-' }}"
                            data-tindak-lanjut="{{ $detail?->tindak_lanjut ?? '-' }}"
                            data-manager-note="{{ $report->plan->manager_note ?? '' }}">
                      <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button type="button"
                            class="btn btn-danger btn-sm font-weight-bold js-reject"
                            style="border-radius:.4rem; white-space:nowrap;"
                            data-id="{{ $report->id }}">
                      <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                  @elseif($sub === 'report_verification_hse' && !$isManager)
                    <button type="button"
                            class="btn btn-primary btn-sm font-weight-bold js-approve-final"
                            style="border-radius:.4rem; white-space:nowrap;"
                            data-id="{{ $report->id }}">
                      <i class="fas fa-check-double mr-1"></i> Setujui Final
                    </button>
                    <button type="button"
                            class="btn btn-warning btn-sm font-weight-bold js-reject-final"
                            style="border-radius:.4rem; white-space:nowrap;"
                            data-id="{{ $report->id }}">
                      <i class="fas fa-undo mr-1"></i> Kembalikan
                    </button>
                  @else
                    <button class="btn btn-success btn-sm {{ $isManager ? 'manager-readonly' : '' }}" style="white-space:nowrap;" disabled>
                      <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button class="btn btn-danger btn-sm {{ $isManager ? 'manager-readonly' : '' }}" style="white-space:nowrap;" disabled>
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
          Menampilkan <b id="accidentShownCount">{{ $reports->count() }}</b> data dari <b id="accidentTotalCount">{{ $reports->count() }}</b> data
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



{{-- Modal Form Approval --}}
<div class="modal fade" id="modalApprovalAccident"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-clipboard-list mr-2"></i>Form Persetujuan Laporan</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="approvalAccidentForm" novalidate>
        <div class="modal-body">
          <input type="hidden" id="aaId" name="report_id" value="">
          
          <div class="form-group mb-0">
            <label>Pengendalian yang Disarankan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="aaPengendalian" rows="4"
                      placeholder="Tuliskan pengendalian / tindak lanjut yang disarankan ke PIC..." required></textarea>
            <div class="invalid-feedback">Harap isi pengendalian yang disarankan.</div>
            
            <div id="aaManagerNoteSection" class="mt-3 p-2 border border-info rounded" style="font-size:13px; background-color: #e7f3ff; display: none;">
              <strong>Catatan Manager:</strong> <span id="aaManagerNoteText"></span>
            </div>

            <small class="text-muted">Catatan ini akan diteruskan ke PIC.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm" id="btnSubmitApproval">
            <i class="fas fa-paper-plane mr-1"></i> Setujui &amp; Teruskan ke PIC
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalRejectAccident" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-times-circle mr-2 text-danger"></i>Tolak Laporan</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin <strong class="text-danger">menolak</strong> laporan ini? Laporan akan ditutup (Close).</p>
        <input type="hidden" id="rejectAccidentId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger btn-sm" id="btnConfirmReject">
          <i class="fas fa-times mr-1"></i> Ya, Tolak
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Kembalikan Hasil Final --}}
<div class="modal fade" id="modalRejectFinalAccident" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-undo mr-2 text-warning"></i>Kembalikan ke PIC</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="rejectFinalAccidentId">
        <div class="form-group mb-0">
          <label>Catatan Pengembalian <span class="text-danger">*</span></label>
          <textarea class="form-control" id="rejectFinalNote" rows="3"
                    placeholder="Tuliskan alasan pengembalian ke PIC..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary btn-sm" id="btnConfirmRejectFinal">
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
  .manager-readonly {
    opacity: 0.4 !important;
    cursor: not-allowed !important;
    pointer-events: none;
  }
</style>
@endif
<script>
  @if($isManager) var IS_MANAGER = true; @else var IS_MANAGER = false; @endif

    let __pendingApproveId  = null;
  let __pendingWireId     = null;

  $(document).on('click', '.js-approve', function () {
    if (IS_MANAGER) return;
    const $btn = $(this);
    __pendingApproveId = $btn.data('id');
    __pendingWireId    = $btn.data('wire-id');

    $('#aaId').val(__pendingApproveId);
    $('#aaNoLaporan').val($btn.data('no-laporan') || '');
    $('#aaJenis').val($btn.data('jenis') || '');
    $('#aaDepartemen').val($btn.data('departemen') || '');
    $('#aaLokasiKerja').val($btn.data('lokasi-kerja') || '');
    $('#aaTempat').val($btn.data('tempat') || '');
    $('#aaTanggal').val($btn.data('tanggal') || '');
    $('#aaUraian').val($btn.data('uraian') || '');
    $('#aaTindakLanjut').val($btn.data('tindak-lanjut') || '');
    
    const managerNote = $btn.data('manager-note');
    if (managerNote) {
      $('#aaManagerNoteText').text(managerNote);
      $('#aaManagerNoteSection').show();
    } else {
      $('#aaManagerNoteSection').hide();
    }

    $('#aaPengendalian').val('');
    $('#approvalAccidentForm').removeClass('was-validated');
    $('#modalApprovalAccident').modal('show');
  });

  $('#approvalAccidentForm').on('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) {
      e.stopPropagation();
      $(this).addClass('was-validated');
      return;
    }

    const pengendalian = $('#aaPengendalian').val();
    
    $('#modalApprovalAccident').modal('hide');

    // Inject ke Livewire component
    window.Livewire.dispatch('hseApproveAccident', { id: parseInt(__pendingWireId), note: pengendalian });
  });

  $('#modalApprovalAccident').on('hidden.bs.modal', function () {
    $('#approvalAccidentForm')[0].reset();
    $('#approvalAccidentForm').removeClass('was-validated');
    __pendingApproveId = null;
    __pendingWireId    = null;
  });

  // ── Modal Tolak ────────────────────────────────────────────────────────────
  $(document).on('click', '.js-reject', function () {
    if (IS_MANAGER) return;
    $('#rejectAccidentId').val($(this).data('id'));
    $('#modalRejectAccident').modal('show');
  });

  $('#btnConfirmReject').on('click', function () {
    const id = $('#rejectAccidentId').val();
    window.Livewire.dispatch('hseRejectAccident', { id: parseInt(id) });
    $('#modalRejectAccident').modal('hide');
  });

  // ── Modal Setujui Final ────────────────────────────────────────────────────
  $(document).on('click', '.js-approve-final', function () {
    if (IS_MANAGER) return;
    const id = $(this).data('id');
    window.Livewire.dispatch('hseApproveFinalAccident', { id: parseInt(id) });
  });

  // ── Modal Kembalikan (Reject Final) ───────────────────────────────────────
  $(document).on('click', '.js-reject-final', function () {
    $('#rejectFinalAccidentId').val($(this).data('id'));
    $('#rejectFinalNote').val('');
    $('#modalRejectFinalAccident').modal('show');
  });

  $('#btnConfirmRejectFinal').on('click', function () {
    const id   = $('#rejectFinalAccidentId').val();
    const note = $('#rejectFinalNote').val().trim();
    if (!note) { alert('Catatan pengembalian wajib diisi.'); return; }
    window.Livewire.dispatch('hseRejectFinalAccident', { id: parseInt(id), note: note });
    $('#modalRejectFinalAccident').modal('hide');
  });

  // ── Livewire page reload setelah aksi ─────────────────────────────────────
</script>
</div>
@endpush