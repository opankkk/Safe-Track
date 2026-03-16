<div>
@section('title', 'Laporan Temuan | Sistem HSE')
@section('menu-incident-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
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
  <li class="breadcrumb-item"><a href="{{ url('/pic/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Temuan</li>
@endsection

<div class="container-fluid">
  {{-- Notifikasi ditangani oleh Toast Global --}}

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Temuan</h3>

      <div class="card-tools">
        <div class="d-flex align-items-center flex-wrap justify-content-md-end" style="gap:.75rem;">

          <div style="min-width: 160px;">
            <select wire:model.live="filterType" class="form-control form-control-sm font-weight-bold" style="border-radius:.4rem; height: 31px;">
              <option value="all">Semua Jenis</option>
              <option value="unsafe_action">Unsafe Action</option>
              <option value="unsafe_condition">Unsafe Condition</option>
            </select>
          </div>

          <div style="min-width: 150px;">
            <select wire:model.live="filterStatus" class="form-control form-control-sm font-weight-bold" style="border-radius:.4rem; height: 31px;">
              <option value="all">Semua Status</option>
              <option value="pending">Pending</option>
              <option value="open">Open</option>
              <option value="close">Close</option>
            </select>
          </div>

          <div class="input-group input-group-sm" style="width:210px;">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" style="border-radius:.4rem 0 0 .4rem;" placeholder="Cari...">
            <div class="input-group-append">
              <button type="button" class="btn btn-default" style="border-radius:0 .4rem .4rem 0; background: #f8f9fa;">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" 
                  title="Reset filter & pencarian" style="border-radius:.4rem; height: 31px;" 
                  wire:click="$set('filterType', 'all'); $set('filterStatus', 'all'); $set('search', '');">
            <i class="fas fa-undo mr-1"></i> Reset
          </button>

        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0" id="incidentTable" style="table-layout: fixed; min-width: 1940px;">
          <thead class="bg-light">
            <tr>
              <th class="text-center" style="width:150px; vertical-align:middle;">No Laporan</th>
              <th class="text-center" style="width:150px; vertical-align:middle;">Jenis</th>
              <th class="text-left"   style="width:250px; vertical-align:middle; padding-left:1rem;">Temuan</th>
              <th class="text-center" style="width:160px; vertical-align:middle;">Tanggal &amp; Waktu</th>
              <th class="text-left"   style="width:250px; vertical-align:middle; padding-left:1rem;">Catatan Manager</th>
              <th class="text-left"   style="width:250px; vertical-align:middle; padding-left:1rem;">Catatan HSE</th>
              <th class="text-center" style="width:100px; vertical-align:middle;">Bukti</th>
              <th class="text-center" style="width:100px; vertical-align:middle;">Plan</th>
              <th class="text-center" style="width:100px; vertical-align:middle;">Report</th>
              <th class="text-center" style="width:150px; vertical-align:middle;">Status</th>
              <th class="text-center" style="width:220px; vertical-align:middle;">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($this->reports as $report)
              <tr>
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
                <td style="vertical-align:middle; padding-left:1rem;">
                  <div style="font-size: .85rem; line-height: 1.4; white-space:normal; word-break:break-word;">
                    {{ $report->unsafeDetail->deskripsi_pengamatan ?? '-' }}
                  </div>
                </td>
                <td class="text-center" style="vertical-align:middle;">{{ $report->updated_at ? \Carbon\Carbon::parse($report->updated_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}</td>
                <td style="vertical-align:middle; padding-left:1rem;">
                   <div style="font-size: .85rem; line-height: 1.4; white-space:normal; word-break:break-word;">
                      @if($report->plan && $report->plan->manager_note)
                        <div class="mb-1"><b>Plan :</b> {{ $report->plan->manager_note }}</div>
                      @endif
                      @if($report->action && $report->action->manager_note)
                        <div class="mb-1"><b>Report :</b> {{ $report->action->manager_note }}</div>
                      @endif
                      @if(!$report->plan?->manager_note && !$report->action?->manager_note)
                        <span class="text-muted">-</span>
                      @endif
                   </div>
                </td>
                <td style="vertical-align:middle; padding-left:1rem;">
                  <div style="font-size: .85rem; line-height: 1.4; white-space:normal; word-break:break-word;">
                    @if($report->hse_tindak_lanjut)
                      <div class="mb-1"><b>Tindak Lanjut :</b> {{ $report->hse_tindak_lanjut }}</div>
                    @endif
                    @if($report->hse_note)
                      <div class="mb-1"><b>Report :</b> {{ $report->hse_note }}</div>
                    @endif
                    @if(!$report->hse_tindak_lanjut && !$report->hse_note)
                      <span class="text-muted">-</span>
                    @endif
                  </div>
                </td>

                {{-- Bukti awal --}}
                <td class="text-center" style="vertical-align:middle;">
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

                <td class="text-center" style="vertical-align:middle;">
                  @if($report->plan)
                    <a href="{{ asset('storage/' . $report->plan->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm" style="border-radius:.4rem;" title="Lihat Bukti Tindak Lanjut">
                      <i class="fas fa-file-invoice"></i>
                    </a>
                  @else
                    <span class="text-muted small">-</span>
                  @endif
                </td>

                <td class="text-center" style="vertical-align:middle;">
                  @if($report->action)
                    <a href="{{ asset('storage/' . $report->action->file_path) }}" target="_blank" class="btn btn-outline-success btn-sm" style="border-radius:.4rem;" title="Lihat Bukti Perbaikan">
                      <i class="fas fa-tools"></i>
                    </a>
                  @else
                    <span class="text-muted small">-</span>
                  @endif
                </td>

                <td class="text-center" style="vertical-align:middle;">
                  @php
                    $sub = $report->sub_status;
                    $badgeClass = ($sub === 'closed') ? 'badge-status-closed' : 'badge-status-open';
                    $badgeText = ($sub === 'closed') ? 'CLOSED' : 'OPEN';
                    $badgeIcon = ($sub === 'closed') ? 'fa-lock' : 'fa-folder-open';
                    
                    $subLabel = '';
                    $subColor = 'text-grey-status';
                    
                  if ($sub === 'waiting_pic') {
                      $subLabel = 'Dalam Proses PIC';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'plan_rejected_manager') {
                      $subLabel = 'Revisi : Plan Ditolak';
                      $subColor = 'text-red-status';
                  } elseif ($sub === 'plan_verification') {
                      $subLabel = 'Verifikasi Plan : Manager';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'plan_approved_manager') {
                      $subLabel = 'Pending : Mulai Pengerjaan';
                      $subColor = 'text-orange-status';
                  } elseif ($sub === 'pic_working') {
                      $subLabel = 'Dalam Pengerjaan PIC';
                      $subColor = 'text-grey-status';
                  } elseif (in_array($sub, ['report_verification_manager', 'report_pending_hse'])) {
                      $subLabel = 'Verifikasi Hasil Manager';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'report_verification_hse') {
                      $subLabel = 'Verifikasi Hasil HSE';
                      $subColor = 'text-grey-status';
                  } elseif ($sub === 'report_rejected_manager') {
                      $subLabel = 'Report: Ditolak Manager';
                      $subColor = 'text-red-status';
                  } elseif ($sub === 'report_rejected_hse') {
                      $subLabel = 'Report: Ditolak HSE';
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

                <td class="text-center" style="vertical-align:middle;">
                  <div class="d-flex justify-content-center flex-nowrap" style="gap:.5rem;">

                    {{-- Upload Plan (waiting_pic) --}}
                    @if($report->sub_status === 'waiting_pic')
                      <button class="btn btn-success btn-sm font-weight-bold" 
                              style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" 
                              wire:click="openUploadPlanModal({{ $report->id }})"
                              {{ auth()->user()->role === 'manager' ? 'disabled' : '' }}>
                        <i class="fas fa-check mr-1"></i> Setujui
                      </button>
                    @endif

                    {{-- Upload Ulang Plan (plan_rejected_manager) --}}
                    @if($report->sub_status === 'plan_rejected_manager')
                      <button class="btn btn-warning btn-sm font-weight-bold" 
                              style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" 
                              wire:click="openUploadPlanModal({{ $report->id }})"
                              {{ auth()->user()->role === 'manager' ? 'disabled' : '' }}>
                        <i class="fas fa-upload mr-1"></i> Upload Ulang
                      </button>
                    @endif

                    {{-- Plan Approved -> Mulai Pengerjaan --}}
                    @if($report->sub_status === 'plan_approved_manager')
                      <button class="btn btn-info btn-sm font-weight-bold" 
                              style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" 
                              wire:click="startWorking({{ $report->id }})"
                              {{ auth()->user()->role === 'manager' ? 'disabled' : '' }}>
                        <i class="fas fa-play mr-1"></i> Mulai Dikerjakan
                      </button>
                    @endif

                    {{-- PIC Working -> Upload Hasil --}}
                    @if($report->sub_status === 'pic_working')
                      <button class="btn btn-primary btn-sm font-weight-bold" 
                              style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" 
                              wire:click="openUploadResultModal({{ $report->id }})"
                              {{ auth()->user()->role === 'manager' ? 'disabled' : '' }}>
                        <i class="fas fa-upload mr-1"></i> Upload Report
                      </button>
                    @endif

                    {{-- Report Rejected -> Direct Upload --}}
                    @if(in_array($report->sub_status, ['report_rejected_manager', 'report_rejected_hse']))
                      <button class="btn btn-warning btn-sm font-weight-bold" 
                              style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" 
                              wire:click="openUploadResultModal({{ $report->id }})"
                              {{ auth()->user()->role === 'manager' ? 'disabled' : '' }}>
                        <i class="fas fa-upload mr-1"></i> Upload Report
                      </button>
                    @endif

                    {{-- Readonly --}}
                    @if(in_array($report->sub_status, ['plan_verification', 'report_verification_manager', 'report_verification_hse', 'closed']))
                      <button class="btn btn-success btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" type="button" disabled>
                        <i class="fas fa-check mr-1"></i> Setujui
                      </button>
                      <button class="btn btn-danger btn-sm font-weight-bold" style="border-radius:.4rem; padding:.35rem .75rem; white-space:nowrap;" type="button" disabled>
                        <i class="fas fa-times mr-1"></i> Tolak
                      </button>
                    @endif

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11" class="text-center text-muted py-5">
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
          Menampilkan <b>{{ $this->reports->count() }}</b> dari <b>{{ $this->reports->count() }}</b> data
        </small>

        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary" style="border-radius:.4rem; font-weight:600; padding:.35rem .75rem;" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary" style="border-radius:.4rem; font-weight:600; padding:.35rem .75rem;" disabled>Selanjutnya</button>
        </div>
      </div>

    </div>
  </div>
</div>



{{-- MODAL UPLOAD PLAN --}}
@php
  $selectedPlan = null;
  if($uploadPlanReportId) {
      $selectedPlan = $this->reports->firstWhere('id', $uploadPlanReportId);
  }
@endphp
<div class="modal fade" id="modalUploadPlan" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" wire:ignore.self>
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFollowUpIncidentLabel">
          <i class="fas fa-file-upload mr-1"></i> Form Upload Dokumen Plan Tindak Lanjut
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="$set('uploadPlanReportId', null)">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form wire:submit.prevent="submitUploadPlan">
        <div class="modal-body">

          <div class="form-group mb-0">
            <label>Upload Dokumen <span class="text-danger">*</span></label>
            <div class="custom-file">
              <input type="file" class="custom-file-input @error('planFile') is-invalid @enderror" wire:model="planFile" id="planFileInputInc" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="document.getElementById('planFileLabelInc').innerText = this.files[0] ? this.files[0].name : 'Pilih dokumen...'">
              <label class="custom-file-label" for="planFileInputInc" id="planFileLabelInc">
                @if($planFile && method_exists($planFile, 'getClientOriginalName'))
                  {{ $planFile->getClientOriginalName() }}
                @else
                  Pilih dokumen...
                @endif
              </label>
            </div>
            @error('planFile')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            <small class="form-text text-muted" wire:loading.remove wire:target="planFile">
              Format yang diperbolehkan: PDF, DOC, DOCX, JPG, JPEG, PNG.
            </small>
            <div wire:loading wire:target="planFile" class="text-info small mt-1">
              <span class="spinner-border spinner-border-sm"></span> Sedang memproses file...
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" wire:click="$set('uploadPlanReportId', null)">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled" wire:target="submitUploadPlan, planFile">
            <span wire:loading wire:target="submitUploadPlan" class="spinner-border spinner-border-sm mr-1"></span>
            <i class="fas fa-save mr-1" wire:loading.remove wire:target="submitUploadPlan"></i> Upload &amp; Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL UPLOAD HASIL --}}
@php
  $selectedResult = null;
  if($uploadResultReportId) {
      $selectedResult = $this->reports->firstWhere('id', $uploadResultReportId);
  }
@endphp
<div class="modal fade" id="modalUploadResult" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" wire:ignore.self>
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-file-upload mr-1"></i> Form Upload Dokumen Hasil Perbaikan
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="$set('uploadResultReportId', null)">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form wire:submit.prevent="submitUploadResult">
        <div class="modal-body">

          <div class="form-group mb-0">
            <label>Upload Dokumen <span class="text-danger">*</span></label>
            <div class="custom-file">
              <input type="file" class="custom-file-input @error('resultFile') is-invalid @enderror" wire:model="resultFile" id="resultFileInputInc" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="document.getElementById('resultFileLabelInc').innerText = this.files[0] ? this.files[0].name : 'Pilih dokumen...'">
              <label class="custom-file-label" for="resultFileInputInc" id="resultFileLabelInc">
                @if($resultFile && method_exists($resultFile, 'getClientOriginalName'))
                  {{ $resultFile->getClientOriginalName() }}
                @else
                  Pilih dokumen...
                @endif
              </label>
            </div>
            @error('resultFile')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            <small class="form-text text-muted" wire:loading.remove wire:target="resultFile">
              Format yang diperbolehkan: PDF, DOC, DOCX, JPG, JPEG, PNG.
            </small>
            <div wire:loading wire:target="resultFile" class="text-info small mt-1">
              <span class="spinner-border spinner-border-sm"></span> Sedang memproses file...
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" wire:click="$set('uploadResultReportId', null)">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled" wire:target="submitUploadResult, resultFile">
            <span wire:loading wire:target="submitUploadResult" class="spinner-border spinner-border-sm mr-1"></span>
            <i class="fas fa-save mr-1" wire:loading.remove wire:target="submitUploadResult"></i> Upload &amp; Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>

@push('scripts')
<script>


  window.addEventListener('open-modal', event => {
      $('#' + event.detail.modal).modal('show');
  });

  window.addEventListener('close-modal', event => {
      $('#' + event.detail.modal).modal('hide');
  });
</script>
@endpush