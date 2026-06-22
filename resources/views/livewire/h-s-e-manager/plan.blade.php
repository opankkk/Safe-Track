<div>
@section('title', 'Plan Tindak Lanjut | Sistem HSE')
@section('menu-plan-tindak-lanjut-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Manager Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Verifikasi Plan Tindak Lanjut</span>
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
  <li class="breadcrumb-item active">Plan Tindak Lanjut</li>
@endsection

<div class="page-hse-plan">
<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Plan Tindak Lanjut</h3>

      <div class="card-tools">
        <div class="d-flex align-items-center flex-wrap justify-content-end" style="gap:.5rem;">

          <select wire:model.live="filterType" class="form-control form-control-sm font-weight-bold" style="width:200px; border-radius:.4rem;">
            <option value="all">Semua Jenis</option>
            <option value="accident">Accident</option>
            <option value="unsafe_action">Unsafe Action</option>
            <option value="unsafe_condition">Unsafe Condition</option>
          </select>

          <div class="input-group input-group-sm" style="width:220px;">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari by ID/PIC...">
            <div class="input-group-append">
              <button class="btn btn-default" type="button">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" 
                  title="Reset filter & pencarian" style="border-radius:.4rem;" 
                  wire:click="$set('filterType', 'all'); $set('search', '');">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      
      {{-- Notifikasi sekarang ditangani oleh Sistem Toast Global (SweetAlert2) --}}

      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="planTable" style="table-layout: fixed; min-width: 1030px;">
          <thead>
              <th class="text-center" style="width:130px; white-space:nowrap;">No Laporan</th>
              <th class="text-center" style="width:160px; white-space:nowrap;">Jenis Laporan</th>
              <th class="text-center" style="width:180px; white-space:nowrap;">Tanggal &amp; Waktu</th>
              <th class="text-center" style="width:110px;">Bukti Awal</th>
              <th class="text-center" style="width:110px;">Plan</th>
              <th class="text-center" style="width:130px;">Status</th>
              <th class="text-center" style="width:210px;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($this->reports as $report)
            <tr>
              <td class="text-center font-weight-bold">{{ $report->report_number }}</td>
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
                @if($report->plan && $report->plan->file_path)
                  <a href="{{ asset('storage/' . $report->plan->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm btn-round-sm">
                    <i class="fas fa-file-invoice"></i>
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
                  
                  // Specific color for Verifikasi Hasil HSE (should be grey as it's with HSE)
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
              <td colspan="8" class="text-center text-muted py-5">
                <i class="fas fa-inbox" style="font-size:2.5rem; opacity:.3;"></i>
                <div class="mt-2">Belum ada plan masuk.</div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted">
          Menampilkan <b id="planShownCount">{{ $this->reports->count() > 0 ? 1 : 0 }}</b> sampai <b id="planTotalCount">{{ $this->reports->count() }}</b> dari {{ $this->reports->count() }} data
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



{{-- Modal Follow Up (approve flow) --}}
<div class="modal fade" id="modalFollowUpPlan"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" wire:ignore.self>

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-clipboard-list mr-1"></i> Form Setujui Plan Tindak Lanjut
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form wire:submit.prevent="submitApprovePlan">
        <div class="modal-body">
          <div class="form-group mb-0">
            <label>Catatan Plan Tindak Lanjut <span class="text-danger">*</span></label>
            <textarea
              class="form-control @error('managerNote') is-invalid @enderror"
              wire:model="managerNote"
              rows="4"
              placeholder="Tuliskan plan catatan tindak lanjut..."
              required
            ></textarea>
            @error('managerNote')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save mr-1"></i> Simpan Persetujuan
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

{{-- Modal Reject Plan --}}
<div class="modal fade" id="modalRejectPlan"
     data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" wire:ignore.self>

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-times-circle text-danger mr-1"></i> Tolak Plan Tindak Lanjut
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form wire:submit.prevent="submitRejectPlan">
        <div class="modal-body">
          <div class="form-group mb-0">
            <label>Catatan Penolakan <span class="text-danger">*</span></label>
            <textarea
              class="form-control @error('managerRejectNote') is-invalid @enderror"
              wire:model="managerRejectNote"
              rows="4"
              placeholder="Tulis alasan mengapa plan ditolak..."
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
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-times-circle mr-1"></i> Tolak Plan
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
 
 {{-- Modal Reject Report/Result --}}
 <div class="modal fade" id="modalRejectReport"
      data-backdrop="static" data-keyboard="false"
      tabindex="-1" role="dialog" wire:ignore.self>
 
   <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
 
       <div class="modal-header bg-danger text-white">
         <h5 class="modal-title">
           <i class="fas fa-times-circle mr-1"></i> Tolak Hasil Tindak Lanjut
         </h5>
         <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
 
       <form wire:submit.prevent="submitRejectResult">
         <div class="modal-body">
 
           <div class="form-group mb-0">
             <label>Catatan Penolakan Hasil <span class="text-danger">*</span></label>
             <textarea
               class="form-control @error('managerRejectNote') is-invalid @enderror"
               wire:model="managerRejectNote"
               rows="4"
               placeholder="Jelaskan mengapa hasil tidak sesuai..."
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
           <button type="submit" class="btn btn-primary btn-sm">
             <i class="fas fa-times-circle mr-1"></i> Tolak Hasil
           </button>
         </div>
       </form>
 
     </div>
   </div>
 </div>
 </div>

@push('scripts')
<script>


  document.addEventListener('livewire:load', function () {
      Livewire.on('open-modal', function (data) {
          $('#' + data.modal).modal('show');
      });
      Livewire.on('close-modal', function (data) {
          $('#' + data.modal).modal('hide');
          $('.modal-backdrop').remove();
          $('body').removeClass('modal-open');
      });
  });
  
  // Also register event listeners for Livewire v3
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