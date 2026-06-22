<div>
@section('title', 'Laporan Temuan | Sistem HSE')
@section('menu-incident-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Manager Area</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Monitoring Laporan Ketidaksesuaian</span>
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
  <li class="breadcrumb-item active">Laporan Temuan</li>
@endsection

<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Temuan</h3>

      <div class="card-tools">
        {{-- Inline utilities ala tailwind --}}
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
                  title="Reset filter & pencarian" style="border-radius:.4rem;" 
                  wire:click="$set('filterJenis', 'all'); $set('filterStatus', 'all'); $set('search', '');">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="incidentTable" style="table-layout: fixed; min-width: 1330px;">
          <thead class="bg-light">
            <tr>
              <th class="text-center" style="width:120px; vertical-align:middle;">No Laporan</th>
              <th class="text-center" style="width:140px; vertical-align:middle;">Jenis</th>
              <th class="text-center" style="min-width:250px; vertical-align:middle;">Temuan</th>
              <th class="text-center" style="width:160px; vertical-align:middle;">Tanggal &amp; Waktu</th>
              <th class="text-center" style="width:140px; vertical-align:middle;">Lokasi Kejadian</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Bukti</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Plan</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Report</th>
              <th class="text-center" style="width:130px; vertical-align:middle;">Status</th>
              <th class="text-center" style="width:150px; vertical-align:middle;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($this->reports as $report)
            <tr>
              <td class="text-center font-weight-bolder" style="letter-spacing:.2px;">{{ $report->report_number }}</td>

              <td class="text-center">
                @if($report->type === 'unsafe_action')
                  <span class="badge badge-warning px-3 py-2" style="border-radius:.4rem; display:inline-flex; align-items:center; gap:.35rem; font-weight:700; font-size:.7rem; color:#000 !important;">
                    <i class="fas fa-user-shield"></i> Unsafe Action
                  </span>
                @else
                  <span class="badge badge-info px-3 py-2" style="border-radius:.4rem; display:inline-flex; align-items:center; gap:.35rem; font-weight:700; font-size:.7rem; color:#000 !important;">
                    <i class="fas fa-exclamation-triangle"></i> Unsafe Condition
                  </span>
                @endif
              </td>

              <td>
                <div style="font-size: .85rem; line-height: 1.4;">
                  {{ $report->unsafeDetail->deskripsi_pengamatan ?? '-' }}
                </div>
              </td>

              <td class="text-center">{{ $report->created_at ? \Carbon\Carbon::parse($report->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}</td>
              <td class="text-center">{{ $report->unsafeDetail->lokasi ?? '-' }}</td>

              {{-- Bukti awal --}}
              <td class="text-center">
                @if($report->attachments && $report->attachments->count() > 0)
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
              <td class="text-center">
                @if($report->plan && $report->plan->file_path)
                  <a href="{{ asset('storage/' . $report->plan->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm btn-round-sm" title="Lihat Plan">
                    <i class="fas fa-file-invoice"></i>
                  </a>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              {{-- Report --}}
              <td class="text-center">
                @if($report->action && $report->action->file_path)
                  <a href="{{ asset('storage/' . $report->action->file_path) }}" target="_blank" class="btn btn-outline-success btn-sm btn-round-sm" title="Lihat Hasil PIC">
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
                  } elseif (str_contains($sub, 'rejected')) {
                      $subColor = 'text-red-status';
                  }
                  
                  if ($sub === 'report_verification_hse') {
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
                @if($report->sub_status === 'plan_verification')
                  <button class="btn btn-success btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" wire:click="openApproveModal({{ $report->id }})">
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button class="btn btn-danger btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" wire:click="openRejectModal({{ $report->id }})">
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                @elseif(in_array($report->sub_status, ['report_verification_manager', 'report_pending_hse']))
                  <button class="btn btn-success btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" wire:click="approveResult({{ $report->id }})">
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button class="btn btn-danger btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" wire:click="openRejectReportModal({{ $report->id }})">
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                @else
                  <button class="btn btn-success btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" disabled>
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button class="btn btn-danger btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem;" disabled>
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
                <div class="mt-2">Belum ada laporan masuk.</div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted">
          Menampilkan <b id="incidentShownCount">{{ $this->reports->count() }}</b> data dari <b id="incidentTotalCount">{{ $this->reports->count() }}</b> data
        </small>

        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary" style="border-radius:.4rem; font-weight:600; padding:.35rem .75rem;" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary" style="border-radius:.4rem; font-weight:600; padding:.35rem .75rem;" disabled>Selanjutnya</button>
        </div>
      </div>

    </div>
  </div>

</div>

@include('livewire.h-s-e-manager.partials._modals-accident')

@push('scripts')
<script>
  document.addEventListener('open-modal', (event) => { 
      $('#' + event.detail.modal).modal('show'); 
  });
  document.addEventListener('close-modal', (event) => { 
      $('#' + event.detail.modal).modal('hide'); 
      $('.modal-backdrop').remove(); 
  });
</script>
@endpush

</div>
