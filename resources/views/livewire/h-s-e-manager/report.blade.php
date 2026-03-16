<div>
@section('title', 'Laporan Penanganan | Sistem HSE')
@section('menu-perbaikan-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Manager Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Verifikasi Hasil Perbaikan</span>
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
  <li class="breadcrumb-item"><a href="{{ url('/hse-manager/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Penanganan</li>
@endsection

<div class="container-fluid">

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Laporan Penanganan</h3>

      <div class="card-tools">
        {{-- Filter + Search --}}
        <div class="filter-bar">

          <select wire:model.live="filterJenis" class="form-control form-control-sm" style="width: 210px;">
            <option value="all">Semua Jenis</option>
            <option value="accident">Accident</option>
            <option value="ua">Unsafe Action</option>
            <option value="uc">Unsafe Condition</option>
          </select>

          <select wire:model.live="filterStatus" class="form-control form-control-sm" style="width: 180px;">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="open">Open</option>
            <option value="close">Close</option>
          </select>

          <div class="input-group input-group-sm" style="width: 220px;">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control float-right" placeholder="Cari by ID/PIC...">
            <div class="input-group-append">
              <button type="button" class="btn btn-default">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="$set('filterJenis', 'all'); $set('filterStatus', 'all'); $set('search', '');" title="Reset filter & pencarian">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      
      @if(session()->has('successMsg'))
        <div class="alert alert-success m-3 alert-dismissible fade show" role="alert">
          {{ session('successMsg') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if(session()->has('errorMsg'))
        <div class="alert alert-success m-3 alert-dismissible fade show" role="alert">
          {{ session('errorMsg') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if($successMsg)
        <div class="alert alert-success m-3 alert-dismissible fade show" role="alert">
          {{ $successMsg }}
          <button type="button" class="close" wire:click="$set('successMsg', null)">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if($errorMsg)
        <div class="alert alert-success m-3 alert-dismissible fade show" role="alert">
          {{ $errorMsg }}
          <button type="button" class="close" wire:click="$set('errorMsg', null)">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="reportTable" style="table-layout: fixed; min-width: 1030px;">
          <thead>
              <th class="text-center" style="width:130px; white-space:nowrap;">No Laporan</th>
              <th class="text-center" style="width:160px; white-space:nowrap;">Jenis Laporan</th>
              <th class="text-center" style="width:180px; white-space:nowrap;">Tanggal & Waktu</th>
              <th class="text-center" style="width:110px;">Bukti</th>
              <th class="text-center" style="width:110px;">Report</th>
              <th class="text-center" style="width:130px;">Status</th>
              <th class="text-center" style="width:210px;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($this->reports as $report)
            <tr>
              <td class="text-center id-laporan font-weight-bold">{{ $report->report_number }}</td>

              <td class="text-center">
                @if($report->type === 'unsafe_action')
                  <span class="badge badge-warning badge-pillish" style="color: #000 !important;">
                    <i class="fas fa-user-shield"></i> Unsafe Action
                  </span>
                @elseif($report->type === 'unsafe_condition')
                  <span class="badge badge-info badge-pillish">
                    <i class="fas fa-exclamation-triangle"></i> Unsafe Condition
                  </span>
                @else
                  <span class="badge badge-pillish badge-accident" style="background:#dc3545; color:#fff;">
                    <i class="fas fa-ambulance"></i> Accident
                  </span>
                @endif
              </td>

              <td class="text-center">{{ $report->plan ? \Carbon\Carbon::parse($report->plan->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}</td>

              <td class="text-center">
                @if($report->attachments && $report->attachments->count() > 0)
                  @php $pdfAt = $report->attachments->where('category', 'pdf_report')->first() ?? $report->attachments->first(); @endphp
                  <a href="{{ asset('storage/' . $pdfAt->file_path) }}" 
                     target="_blank"
                     class="btn btn-outline-danger btn-sm btn-round-sm"
                     title="Lihat Bukti PDF">
                    <i class="far fa-file-pdf"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              <td class="text-center">
                @if($report->action && $report->action->file_path)
                  <a href="{{ asset('storage/' . $report->action->file_path) }}" target="_blank" class="btn btn-outline-success btn-sm btn-round-sm" title="Lihat Dokumen Hasil">
                    <i class="fas fa-tools"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              <td class="text-center">
                @php
                  $sub = $report->sub_status;
                  $badgeClass = ($sub === 'closed') ? 'badge-status-closed' : 'badge-status-open';
                  $badgeText = ($sub === 'closed') ? 'CLOSED' : 'OPEN';
                  $badgeIcon = ($sub === 'closed') ? 'fa-lock' : 'fa-folder-open';
                  
                  $subLabel = \App\Models\Report::subStatusLabel($sub);
                  $subColor = 'text-grey-status';
                  if (str_contains($sub, 'pending') || str_contains($sub, 'verification')) {
                      $subColor = 'text-orange-status';
                  }
                  if ($sub === 'report_verification_hse' || $sub === 'report_rejected_manager' || $sub === 'report_rejected_hse') {
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
                  @if(in_array($report->sub_status, ['report_verification_manager', 'report_pending_hse']))
                    <button
                      type="button"
                      class="btn btn-success btn-sm aksi-btn"
                      style="white-space:nowrap;"
                      wire:click="approveResult({{ $report->id }})"
                    >
                      <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button type="button" class="btn btn-danger btn-sm aksi-btn" style="white-space:nowrap;" wire:click="openRejectModal({{ $report->id }})">
                      <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                  @else
                    <button type="button" class="btn btn-success btn-sm aksi-btn" style="white-space:nowrap;" disabled>
                      <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button type="button" class="btn btn-danger btn-sm aksi-btn" style="white-space:nowrap;" disabled>
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
          Menampilkan <b id="reportShownCount">{{ $this->reports->count() > 0 ? 1 : 0 }}</b> sampai <b id="reportTotalCount">{{ $this->reports->count() }}</b> dari {{ $this->reports->count() }} data
        </small>
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary pager-btn" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary pager-btn" disabled>Selanjutnya</button>
        </div>
      </div>

    </div>
  </div>

</div>



{{-- Modal Reject Report --}}
<div class="modal fade" id="modalRejectReport"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" wire:ignore.self>

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-times-circle text-danger mr-1"></i> Tolak Laporan Hasil Perbaikan
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form wire:submit.prevent="submitRejectResult">
        <div class="modal-body">
          @if($selectedReportData)
            <div class="mb-3 text-center">
              <span class="badge badge-info py-2 px-3" style="font-size:14px; border-radius:10px;">
                <i class="fas fa-file-alt mr-1"></i> No Laporan: <strong>{{ $selectedReportData->report_number }}</strong>
              </span>
            </div>
            @if($selectedReportData->action && $selectedReportData->action->manager_note)
            <div class="mb-3">
              <strong>Catatan Manager (Sebelumnya):</strong><br>
              <div class="p-2 bg-info-light border border-info rounded" style="font-size:14px; background-color: #e7f3ff;">
                {{ $selectedReportData->action->manager_note }}
              </div>
            </div>
            @endif
          @endif

          <div class="form-group mb-0">
            <label>Catatan Penolakan <span class="text-danger">*</span></label>
            <textarea
              class="form-control @error('managerRejectNote') is-invalid @enderror"
              wire:model="managerRejectNote"
              rows="4"
              placeholder="Tulis alasan mengapa bukti ditolak..."
              required
            ></textarea>
            @error('managerRejectNote')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-danger btn-sm">
            <i class="fas fa-times-circle mr-1"></i> Tolak Bukti
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
</div>

@push('scripts')
<script>

  document.addEventListener('open-modal', (event) => {
      $('#' + event.detail.modal).modal('show');
  });
  document.addEventListener('close-modal', (event) => {
      $('#' + event.detail.modal).modal('hide');
      $('.modal-backdrop').remove();
      $('body').removeClass('modal-open');
  });
</script>
@endpush