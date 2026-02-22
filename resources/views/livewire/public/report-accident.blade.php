@extends('layouts.app')

@section('title', 'Accident Report | Sistem HSE')
@section('body-class', 'hold-transition layout-top-nav')
@section('is-auth', true)

@push('styles')
<style>
  /* bikin halaman publik lebih enak dilihat */
  .public-page {
    min-height: 100vh;
    background: #f4f6f9;
    padding: 24px 0;
  }
  .public-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }
  .public-brand {
    font-weight: 800;
    font-size: 20px;
    color: #343a40;
  }
</style>
@endpush

@section('content')
<div class="public-page">
  <div class="container">

    {{-- Header --}}
    <div class="public-header mb-3">
      <div>
        <div class="public-brand"><i class="fas fa-shield-alt mr-1"></i> Sistem HSE</div>
        <small class="text-muted">Form Pelaporan Kecelakaan (Accident Report)</small>
      </div>
      <div>
        <a href="#" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-home mr-1"></i> Beranda
        </a>
      </div>
    </div>

    {{-- Card Form --}}
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-notes-medical mr-1"></i> Accident Report
        </h3>
      </div>

      {{-- sementara front-end: action="#" --}}
      <form action="#" method="post" enctype="multipart/form-data">
        <div class="card-body">

          {{-- IDENTITAS PELAPOR --}}
          <div class="mb-3">
            <h5 class="mb-1">Identitas Pelapor</h5>
            <small class="text-muted">Silakan isi data pelapor untuk kebutuhan tindak lanjut.</small>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Nama Pelapor <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Contoh: Budi Santoso">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Email (Gmail) <span class="text-danger">*</span></label>
                <input type="email" class="form-control" placeholder="contoh@gmail.com">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>No. Telepon/HP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="08xxxxxxxxxx">
              </div>
            </div>
          </div>

          <hr>

          {{-- DETAIL KEJADIAN --}}
          <div class="mb-3">
            <h5 class="mb-1">Detail Kejadian</h5>
            <small class="text-muted">Isi informasi kejadian kecelakaan secara singkat dan jelas.</small>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Tanggal Kejadian <span class="text-danger">*</span></label>
                <input type="date" class="form-control">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Waktu Kejadian</label>
                <input type="time" class="form-control">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Lokasi/Area <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Contoh: Workshop A / Gudang / Area Loading">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Jenis Kecelakaan <span class="text-danger">*</span></label>
                <select class="form-control">
                  <option selected disabled>Pilih jenis</option>
                  <option>Cedera Ringan</option>
                  <option>Cedera Sedang</option>
                  <option>Cedera Berat</option>
                  <option>Nyaris Celaka (Near Miss)</option>
                  <option>Kerusakan Properti</option>
                  <option>Lainnya</option>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Korban/Orang Terlibat</label>
                <input type="text" class="form-control" placeholder="Nama korban / jumlah orang (opsional)">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Potensi Dampak</label>
                <select class="form-control">
                  <option selected disabled>Pilih (opsional)</option>
                  <option>Low</option>
                  <option>Medium</option>
                  <option>High</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Deskripsi Kejadian <span class="text-danger">*</span></label>
            <textarea class="form-control" rows="4" placeholder="Jelaskan kronologi singkat kejadian..."></textarea>
          </div>

          <div class="form-group">
            <label>Tindakan Segera yang Dilakukan (jika ada)</label>
            <textarea class="form-control" rows="3" placeholder="Contoh: menghentikan mesin, memberi P3K, memasang rambu..."></textarea>
          </div>

          <hr>

          {{-- LAMPIRAN --}}
          <div class="mb-3">
            <h5 class="mb-1">Lampiran</h5>
            <small class="text-muted">Unggah foto/berkas pendukung (opsional).</small>
          </div>

          <div class="form-group">
            <label>Upload Foto / Dokumen</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="evidence" multiple>
              <label class="custom-file-label" for="evidence">Pilih file</label>
            </div>
            <small class="text-muted d-block mt-1">Format umum: JPG, PNG, PDF. (Statis dulu)</small>
          </div>

          <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            Setelah submit, sistem akan membuat nomor laporan dan HSE akan melakukan verifikasi.
          </div>

        </div>

        <div class="card-footer d-flex justify-content-between">
          <a href="#" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
          </a>

          {{-- sementara statis: submit dummy --}}
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane mr-1"></i> Kirim Laporan
          </button>
        </div>
      </form>
    </div>

    <div class="text-center text-muted mt-3">
      <small>© {{ date('Y') }} Sistem HSE</small>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  // AdminLTE custom-file-input label update (tanpa plugin)
  document.addEventListener('change', function (e) {
    if (e.target && e.target.classList.contains('custom-file-input')) {
      const input = e.target;
      const label = input.nextElementSibling;
      if (label && input.files && input.files.length > 0) {
        if (input.files.length === 1) {
          label.textContent = input.files[0].name;
        } else {
          label.textContent = input.files.length + ' files selected';
        }
      }
    }
  });
</script>
@endpush