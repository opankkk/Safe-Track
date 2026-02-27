{{-- resources/views/livewire/h-s-e/accident-report.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Kerusakan | Sistem HSE')
@section('menu-accident-active', 'active')
@section('hide-navbar', true)

@section('page-title')
  <div class="d-flex flex-column">
    <small class="text-muted">Approval</small>
    <span class="font-weight-bold" style="font-size: 1.6rem;">Laporan Kerusakan (Accident)</span>
  </div>
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ url('/hse/dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Kerusakan</li>
@endsection

@push('styles')
<style>
  .table td, .table th { vertical-align: middle; }

  /* bukti button kecil rapi */
  .bukti-btn{ padding: .25rem .55rem; border-radius: .4rem; }

  /* aksi ala AdminLTE projects (rapi, rounded) */
  .aksi-btn{
    font-weight: 600;
    border-radius: .4rem;
    padding: .35rem .75rem;
  }

  /* biar kolom nama mirip "projects" (judul + subtext) */
  .name-title{ font-weight: 700; margin-bottom: 2px; }
  .name-sub{ font-size: 12px; color: #6c757d; }

  /* avatar dummy kecil (seperti contoh awal) */
  .avatar-mini{
    width: 30px;
    height: 30px;
    border-radius: 999px;
    background: #f4f6f9;
    border: 1px solid #e5e7eb;
    display: inline-block;
    margin-right: .5rem;
    vertical-align: middle;
  }
</style>
@endpush

@section('content')
<div class="container-fluid">

  <div class="card" style="border-radius:.75rem;">
    <div class="card-header">
      <h3 class="card-title">Laporan Kerusakan</h3>

      <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 220px;">
          <input type="text" name="table_search" class="form-control float-right" placeholder="Cari...">
          <div class="input-group-append">
            <button type="button" class="btn btn-default">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped projects mb-0">
          <thead>
            <tr>
              <th style="width: 60px;" class="text-center">No</th>
              <th>Nama</th>
              <th>Departemen</th>
              <th>Lokasi</th>
              <th style="width: 140px;">Tanggal</th>
              <th style="width: 110px;" class="text-center">Bukti</th>
              <th style="width: 260px;" class="text-right">Aksi</th>
            </tr>
          </thead>

          <tbody>

            {{-- 1) Pending (punya tombol Setujui/Tolak) --}}
            <tr>
              <td class="text-center">1</td>

              <td>
                <span class="avatar-mini"></span>
                <span class="name-title">Budi Susanto</span>
                <div class="name-sub">Accident: First Aid</div>
              </td>

              <td>Workshop</td>
              <td>Area Loading</td>
              <td>24 Nov 2025</td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBukti"
                  data-pdf="{{ asset('storage/bukti/contoh-1.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm aksi-btn">
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm aksi-btn">
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 2) Pending (punya tombol Setujui/Tolak) --}}
            <tr>
              <td class="text-center">2</td>

              <td>
                <span class="avatar-mini"></span>
                <span class="name-title">Siti Rahma</span>
                <div class="name-sub">Accident: Property Damage</div>
              </td>

              <td>Produksi</td>
              <td>Gudang</td>
              <td>01 Jan 2026</td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBukti"
                  data-pdf="{{ asset('storage/bukti/contoh-2.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <button class="btn btn-success btn-sm aksi-btn">
                  <i class="fas fa-check mr-1"></i> Setujui
                </button>
                <button class="btn btn-danger btn-sm aksi-btn">
                  <i class="fas fa-times mr-1"></i> Tolak
                </button>
              </td>
            </tr>

            {{-- 3) Sudah Disetujui (aksi jadi badge) --}}
            <tr>
              <td class="text-center">3</td>

              <td>
                <span class="avatar-mini"></span>
                <span class="name-title">Ahmad Fauzi</span>
                <div class="name-sub">Accident: Nearmiss</div>
              </td>

              <td>Engineering</td>
              <td>Workshop A</td>
              <td>02 Jan 2026</td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBukti"
                  data-pdf="{{ asset('storage/bukti/contoh-3.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <span class="badge badge-success p-2" style="border-radius:.4rem;">
                  <i class="fas fa-check mr-1"></i> Disetujui
                </span>
              </td>
            </tr>

            {{-- 4) Sudah Ditolak (aksi jadi badge) --}}
            <tr>
              <td class="text-center">4</td>

              <td>
                <span class="avatar-mini"></span>
                <span class="name-title">Rina Putri</span>
                <div class="name-sub">Accident: First Aid</div>
              </td>

              <td>Workshop</td>
              <td>Area Loading</td>
              <td>03 Jan 2026</td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm bukti-btn js-open-pdf"
                  title="Lihat Bukti PDF"
                  data-toggle="modal"
                  data-target="#modalBukti"
                  data-pdf="{{ asset('storage/bukti/contoh-4.pdf') }}"
                >
                  <i class="far fa-file-pdf"></i>
                </button>
              </td>

              <td class="text-right">
                <span class="badge badge-danger p-2" style="border-radius:.4rem;">
                  <i class="fas fa-times mr-1"></i> Ditolak
                </span>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center p-3">
        <small class="text-muted">Menampilkan <b>1</b> sampai <b>4</b> dari <b>4</b> data</small>
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary" disabled>Sebelumnya</button>
          <button class="btn btn-outline-secondary" disabled>Selanjutnya</button>
        </div>
      </div>

    </div>
  </div>

</div>

{{-- Modal Bukti PDF --}}
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog" aria-labelledby="modalBuktiLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBuktiLabel">
          <i class="far fa-file-pdf mr-1"></i> Bukti Laporan (PDF)
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-0" style="height:80vh;">
        <iframe id="pdfFrame" src="" style="border:0;width:100%;height:100%;"></iframe>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Klik icon bukti -> set src iframe
  $(document).on('click', '.js-open-pdf', function () {
    const pdfUrl = $(this).data('pdf');
    $('#pdfFrame').attr('src', pdfUrl);
  });

  // Saat modal ditutup, kosongkan src biar stop loading
  $('#modalBukti').on('hidden.bs.modal', function () {
    $('#pdfFrame').attr('src', '');
  });
</script>
@endpush