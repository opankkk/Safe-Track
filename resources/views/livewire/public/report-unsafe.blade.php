@extends('layouts.app')

@section('title', 'Report Unsafe | Sistem HSE')
@section('body-class', 'hold-transition layout-top-nav')
@section('is-auth', true)

@push('styles')
<style>
  .public-page { min-height: 100vh; background: #f4f6f9; padding: 24px 0; }
  .menu-card { cursor: pointer; }
  .menu-card .small-box { border-radius: 12px; }
  .menu-card .small-box .inner p { margin-bottom: 0; opacity: .95; }
  .menu-card .small-box-footer { border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; }
</style>
@endpush

@section('content')
<div class="public-page">
  <div class="container">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-3">
      <div>
        <h3 class="mb-0">Pelaporan Unsafe</h3>
        <small class="text-muted">Pilih jenis laporan, lalu isi form pada pop-up (modal). Tanpa login.</small>
      </div>
      <a href="#" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-home mr-1"></i> Beranda
      </a>
    </div>

    {{-- 2 Menu Kotak --}}
    <div class="row">
      {{-- Unsafe Action --}}
      <div class="col-md-6 mb-3 menu-card">
        <div class="small-box bg-warning" data-toggle="modal" data-target="#modalUnsafeAction">
          <div class="inner">
            <h4 class="font-weight-bold">Unsafe Action</h4>
            <p>Melaporkan tindakan tidak aman (perilaku/aksi).</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-times"></i>
          </div>
          <a href="javascript:void(0)" class="small-box-footer">
            Buat Laporan <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      {{-- Unsafe Condition --}}
      <div class="col-md-6 mb-3 menu-card">
        <div class="small-box bg-info" data-toggle="modal" data-target="#modalUnsafeCondition">
          <div class="inner">
            <h4 class="font-weight-bold">Unsafe Condition</h4>
            <p>Melaporkan kondisi/lingkungan tidak aman.</p>
          </div>
          <div class="icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <a href="javascript:void(0)" class="small-box-footer">
            Buat Laporan <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>

    {{-- Info --}}
    <div class="alert alert-light border">
      <i class="fas fa-info-circle mr-1"></i>
      Setelah laporan terkirim, tim HSE akan melakukan verifikasi dan tindak lanjut.
    </div>

  </div>
</div>

{{-- =========================
  MODAL: UNSAFE ACTION
========================= --}}
<div class="modal fade" id="modalUnsafeAction" tabindex="-1" role="dialog" aria-labelledby="modalUnsafeActionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUnsafeActionLabel">
          <i class="fas fa-user-times text-warning mr-1"></i> Form Laporan: Unsafe Action
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      {{-- statis dulu --}}
      <form action="#" method="post" enctype="multipart/form-data">
        <div class="modal-body">

          {{-- Identitas --}}
          <h6 class="text-muted mb-2">Identitas Pelapor</h6>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Nama pelapor">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" placeholder="contoh@gmail.com">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>No. HP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="08xxxxxxxxxx">
              </div>
            </div>
          </div>

          <hr class="my-2">

          {{-- Detail --}}
          <h6 class="text-muted mb-2">Detail Kejadian</h6>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Lokasi/Area <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Contoh: Workshop A">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Tingkat Risiko (opsional)</label>
                <select class="form-control">
                  <option selected disabled>Pilih</option>
                  <option>Low</option>
                  <option>Medium</option>
                  <option>High</option>
                  <option>Critical</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Deskripsi Unsafe Action <span class="text-danger">*</span></label>
            <textarea class="form-control" rows="3" placeholder="Contoh: pekerja tidak menggunakan APD, melanggar SOP..."></textarea>
          </div>

          <div class="form-group">
            <label>Saran Perbaikan (opsional)</label>
            <textarea class="form-control" rows="2" placeholder="Contoh: briefing APD, pasang signage, pengawasan..."></textarea>
          </div>

          <div class="form-group">
            <label>Lampiran (opsional)</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="fileAction" multiple>
              <label class="custom-file-label" for="fileAction">Pilih file</label>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-warning">
            <i class="fas fa-paper-plane mr-1"></i> Kirim
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- =========================
  MODAL: UNSAFE CONDITION
========================= --}}
<div class="modal fade" id="modalUnsafeCondition" tabindex="-1" role="dialog" aria-labelledby="modalUnsafeConditionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUnsafeConditionLabel">
          <i class="fas fa-exclamation-triangle text-info mr-1"></i> Form Laporan: Unsafe Condition
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      {{-- statis dulu --}}
      <form action="#" method="post" enctype="multipart/form-data">
        <div class="modal-body">

          {{-- Identitas --}}
          <h6 class="text-muted mb-2">Identitas Pelapor</h6>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Nama pelapor">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" placeholder="contoh@gmail.com">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>No. HP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="08xxxxxxxxxx">
              </div>
            </div>
          </div>

          <hr class="my-2">

          {{-- Detail --}}
          <h6 class="text-muted mb-2">Detail Kejadian</h6>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Lokasi/Area <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Contoh: Gudang / Area Loading">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Tingkat Risiko (opsional)</label>
                <select class="form-control">
                  <option selected disabled>Pilih</option>
                  <option>Low</option>
                  <option>Medium</option>
                  <option>High</option>
                  <option>Critical</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Deskripsi Unsafe Condition <span class="text-danger">*</span></label>
            <textarea class="form-control" rows="3" placeholder="Contoh: lantai licin, kabel semrawut, guard mesin rusak..."></textarea>
          </div>

          <div class="form-group">
            <label>Saran Perbaikan (opsional)</label>
            <textarea class="form-control" rows="2" placeholder="Contoh: pasang anti-slip, rapikan kabel, perbaiki guard..."></textarea>
          </div>

          <div class="form-group">
            <label>Lampiran (opsional)</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="fileCondition" multiple>
              <label class="custom-file-label" for="fileCondition">Pilih file</label>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-info">
            <i class="fas fa-paper-plane mr-1"></i> Kirim
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Update label custom-file-input (AdminLTE style)
  document.addEventListener('change', function (e) {
    if (e.target && e.target.classList.contains('custom-file-input')) {
      const input = e.target;
      const label = input.nextElementSibling;
      if (label && input.files && input.files.length > 0) {
        label.textContent = input.files.length === 1 ? input.files[0].name : (input.files.length + ' files selected');
      }
    }
  });
</script>
@endpush