<div>
@section('title', 'Laporan Kecelakaan | Sistem HSE')
@section('menu-accident-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Manager Area</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Monitoring Laporan Kecelakaan</span>
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
  <li class="breadcrumb-item">
    <a href="{{ url('/hse-manager/dashboard') }}">Dashboard</a>
  </li>
  <li class="breadcrumb-item active">Laporan Kecelakaan</li>
@endsection

<div class="page-hse-accident">
<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Kecelakaan</h3>

      <div class="card-tools">
        <div class="d-flex align-items-center" style="gap:.5rem;">

          <select wire:model.live="filterStatus"
                  class="form-control form-control-sm filter-select"
                  style="width:150px;">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="open">Open</option>
            <option value="close">Close</option>
          </select>

          <div class="input-group input-group-sm" style="width:220px;">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari...">
            <div class="input-group-append">
              <button class="btn btn-default" type="button">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="accidentTable" style="table-layout: fixed; min-width: 1150px;">
          <thead class="bg-light">
            <tr>
              <th class="text-center" style="width:120px; vertical-align:middle;">No Laporan</th>
              <th class="text-center" style="width:140px; vertical-align:middle;">Jenis Insiden</th>
              <th class="text-center" style="width:160px; vertical-align:middle;">Tanggal &amp; Waktu</th>
              <th class="text-center" style="width:140px; vertical-align:middle;">Lokasi Kejadian</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Bukti</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Plan</th>
              <th class="text-center" style="width:80px; vertical-align:middle;">Report</th>
              <th class="text-center" style="width:140px; vertical-align:middle;">Status</th>
              <th class="text-center" style="width:210px; vertical-align:middle;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($this->reports as $report)
            <tr>
              <td class="text-center font-weight-bold">{{ $report->report_number }}</td>
              <td class="text-center">
                <span class="badge badge-secondary px-2 py-1" style="border-radius:.4rem; font-size:.7rem;">
                  {{ $report->accidentDetail->jenis_insiden ?? '-' }}
                </span>
              </td>
              <td class="text-center">{{ $report->created_at ? \Carbon\Carbon::parse($report->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}</td>
              <td class="text-center">{{ $report->accidentDetail->tempat ?? '-' }}</td>

              {{-- Bukti awal --}}
              <td class="text-center">
                @if($report->attachments && $report->attachments->count() > 0)
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

              <td class="text-center">
                @php
                  $sub = $report->sub_status;
                  $badgeClass = ($sub === 'closed') ? 'badge-status-closed' : 'badge-status-open';
                  $badgeText = ($sub === 'closed') ? 'CLOSED' : 'OPEN';
                  $badgeIcon = ($sub === 'closed') ? 'fa-lock' : 'fa-folder-open';
                  
                  $subLabel = '';
                  $subColor = 'text-grey-status';
                  
                  if ($sub === 'plan_verification') {
                      $subLabel = 'Pending : Verifikasi Plan';
                      $subColor = 'text-orange-status';
                  } elseif ($sub === 'report_verification_manager') {
                      $subLabel = 'Pending : Verifikasi Hasil';
                      $subColor = 'text-orange-status';
                  } elseif ($sub === 'report_verification_hse') {
                      $subLabel = 'Verifikasi Hasil HSE';
                      $subColor = 'text-grey-status';
                  } elseif (in_array($sub, ['pic_working', 'plan_approved_manager'])) {
                      $subLabel = 'Dalam Proses PIC';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'waiting_pic') {
                      $subLabel = 'Dalam Proses (HSE)';
                      $subColor = 'text-grey-status';
                  } elseif (in_array($sub, ['plan_rejected_manager', 'report_rejected_manager', 'report_rejected_hse'])) {
                      $subLabel = 'Ditolak/Revisi';
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
                @if($report->attachments && $report->attachments->count() > 0)
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

              <td class="text-center">
                <div class="d-flex justify-content-center flex-nowrap" style="gap:.5rem;">
                @if($report->sub_status === 'plan_verification')
                  <button class="btn btn-success btn-sm btn-action" style="white-space:nowrap;" wire:click="openApproveModal({{ $report->id }})">
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button class="btn btn-danger btn-sm btn-action" style="white-space:nowrap;" wire:click="openRejectModal({{ $report->id }})">
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                @elseif(in_array($report->sub_status, ['report_verification_manager', 'report_pending_hse']))
                  <button class="btn btn-success btn-sm btn-action" style="white-space:nowrap;" wire:click="approveResult({{ $report->id }})">
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button class="btn btn-danger btn-sm btn-action" style="white-space:nowrap;" wire:click="openRejectReportModal({{ $report->id }})">
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                @else
                  <button class="btn btn-success btn-sm btn-action" style="white-space:nowrap;" disabled>
                    <i class="fas fa-check mr-1"></i> Setujui
                  </button>
                  <button class="btn btn-danger btn-sm btn-action" style="white-space:nowrap;" disabled>
                    <i class="fas fa-times mr-1"></i> Tolak
                  </button>
                @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-5">
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
          Menampilkan <b id="showFrom">{{ $this->reports->count() > 0 ? 1 : 0 }}</b> sampai <b id="showTo">{{ $this->reports->count() }}</b>
          dari <b id="totalRows">{{ $this->reports->count() }}</b> data
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

<div class="modal fade" id="modalBuktiAccident" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="far fa-file-pdf mr-1"></i> Bukti Laporan (PDF)
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

@include('livewire.h-s-e-manager.partials._modals-accident')

@push('scripts')
<script>
  function showPdfAccident(url) {
      $('#pdfFrameAccident').attr('src', url);
      $('#modalBuktiAccident').modal('show');
  }

  $('#modalBuktiAccident').on('hidden.bs.modal', function () {
    $('#pdfFrameAccident').attr('src', '');
  });

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
