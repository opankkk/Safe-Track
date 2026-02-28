@extends('layouts.app')

@section('title', 'Accident Report | Sistem HSE')
@section('body-class', 'hold-transition layout-top-nav')
@section('is-auth', true)

@push('styles')
<style>
  :root{
    --hse-primary:#2d6cdf;
    --hse-accent:#6f42c1;
    --hse-bg:#f4f6f9;
    --hse-card:#ffffff;
    --hse-text:#343a40;
    --hse-muted:#6c757d;
  }

  .public-page{
    min-height:100vh;
    background:
      radial-gradient(1200px 600px at 10% -10%, rgba(45,108,223,.18), transparent 55%),
      radial-gradient(900px 500px at 90% 0%, rgba(111,66,193,.18), transparent 55%),
      var(--hse-bg);
    padding: 28px 0 42px;
  }

  /* Hero header */
  .hero{
    background: linear-gradient(135deg, rgba(45,108,223,.12), rgba(111,66,193,.12));
    border:1px solid rgba(0,0,0,.06);
    border-radius: 14px;
    padding: 16px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    box-shadow: 0 8px 24px rgba(0,0,0,.04);
  }
  .hero-left{ display:flex; gap:12px; align-items:center; }
  .hero-badge{
    width:44px;height:44px;border-radius:12px;
    display:grid;place-items:center;
    background: linear-gradient(135deg, var(--hse-primary), var(--hse-accent));
    color:#fff;
    box-shadow: 0 12px 18px rgba(45,108,223,.25);
  }
  .hero-title{ font-weight:900; font-size:18px; margin:0; color:var(--hse-text); }
  .hero-sub{ margin:0; color:var(--hse-muted); font-size:.9rem; }

  /* Stepper */
  .stepper{
    margin-top: 14px;
    display:flex; gap:10px; flex-wrap:wrap;
  }
  .step{
    flex: 1 1 170px;
    background: rgba(255,255,255,.82);
    border:1px solid rgba(0,0,0,.06);
    border-radius: 12px;
    padding: 10px 12px;
    display:flex; align-items:center; gap:10px;
    transition:.18s ease;
  }
  .step:hover{
    transform: translateY(-1px);
    box-shadow:0 10px 22px rgba(0,0,0,.05);
  }
  .step-dot{
    width:30px;height:30px;border-radius:11px;
    display:grid;place-items:center;
    background: rgba(45,108,223,.12);
    color: var(--hse-primary);
    font-weight:900;
  }
  .step-text b{ display:block; font-size:.92rem; color:var(--hse-text); }
  .step-text small{ color:var(--hse-muted); }

  /* Main wrapper card */
  .main-card{
    border-radius: 14px;
    overflow:hidden;
    box-shadow: 0 14px 38px rgba(0,0,0,.06);
    border:1px solid rgba(0,0,0,.06);
    background: var(--hse-card);
  }
  .main-card .card-header{
    background:#fff;
    border-bottom:1px solid rgba(0,0,0,.06);
  }
  .main-card .card-title{ font-weight:900; }

  /* Section cards */
  .section-card{
    border-radius: 14px;
    overflow:hidden;
    border:1px solid rgba(0,0,0,.06);
    background: var(--hse-card);
  }
  .section-card .card-header{
    background: linear-gradient(135deg, rgba(45,108,223,.08), rgba(111,66,193,.08));
    border-bottom:1px solid rgba(0,0,0,.06);
    padding: 14px 16px;
  }
  .section-title{
    font-weight:900;
    margin:0;
    color:var(--hse-text);
    letter-spacing:.1px;
  }
  .section-hint{
    margin:.25rem 0 0;
    color:var(--hse-muted);
    font-size:.88rem;
  }
  .required::after{
    content:" *";
    color:#dc3545;
    font-weight:900;
  }

  /* Inputs */
  label{ font-weight:800; color:var(--hse-text); }
  .form-control, .custom-file-label, select.form-control, textarea.form-control{
    border-radius: 12px;
    border-color: rgba(0,0,0,.12);
  }
  .form-control:focus, select.form-control:focus, textarea.form-control:focus{
    border-color: rgba(45,108,223,.45);
    box-shadow: 0 0 0 .2rem rgba(45,108,223,.12);
  }

  /* Buttons */
  .btn{
    border-radius: 12px;
    padding: .55rem .9rem;
    font-weight:800;
  }
  .btn-primary{
    background: linear-gradient(135deg, var(--hse-primary), var(--hse-accent));
    border:none;
  }
  .btn-info-match{
    background-color: #17a2b8;   /* warna bootstrap alert-info */
    border: 1px solid #17a2b8;
    color: #fff;
  }

  .btn-info-match:hover{
    background-color: #138496;
    border-color: #117a8b;
    color: #fff;
  }

  .btn-info-match:focus{
    box-shadow: 0 0 0 .2rem rgba(23, 162, 184, .25);
  }
  
  /* Footer */
  .card-footer{
    background:#fff;
    border-top:1px solid rgba(0,0,0,.06);
  }
  /* Alert */
  .alert{
    border-radius: 12px;
    border:1px solid rgba(0,0,0,.06);
  }

  /* Small tweaks */
  .custom-control-label{ cursor:pointer; }
  .text-muted{ color: var(--hse-muted) !important; }
</style>
@endpush

@section('content')
<div class="public-page">
  <div class="container">

    {{-- Stepper --}}
    <div class="stepper mb-3">
      <div class="step">
        <div class="step-dot">1</div>
        <div class="step-text">
          <b>Jenis Insiden</b>
          <small>Pilih kategori</small>
        </div>
      </div>
      <div class="step">
        <div class="step-dot">2</div>
        <div class="step-text">
          <b>Dokumen</b>
          <small>Lampiran</small>
        </div>
      </div>
      <div class="step">
        <div class="step-dot">3</div>
        <div class="step-text">
          <b>Detail Kejadian</b>
          <small>Waktu & lokasi</small>
        </div>
      </div>
      <div class="step">
        <div class="step-dot">4</div>
        <div class="step-text">
          <b>Korban & Tindak Lanjut</b>
          <small>Kondisi & aksi</small>
        </div>
      </div>
    </div>

    {{-- Wrapper Card --}}
    <div class="card card-outline card-primary main-card">
      <div class="card-header">
        <h3 class="card-title mb-0">
          <i class="fas fa-notes-medical mr-1"></i> Accident Report
        </h3>
      </div>

      <form action="#" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">

          {{-- 1) JENIS INSIDEN --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Jenis Insiden</p>
              <p class="section-hint">Pilih salah satu kategori insiden.</p>
            </div>
            <div class="card-body pt-3">
              @php
                $jenisInsiden = [
                  'Nearmiss',
                  'Gangguan Kesehatan',
                  'First Aid',
                  'Medical Aid',
                  'Heavy Accident',
                  'Fatality',
                  'Loss Mandays',
                  'Property Damage',
                ];
              @endphp

              <div class="row">
                @foreach($jenisInsiden as $i => $label)
                  <div class="col-md-6">
                    <div class="custom-control custom-radio mb-2">
                      <input class="custom-control-input" type="radio" id="jenis_{{ $i }}" name="jenis_insiden" value="{{ $label }}">
                      <label class="custom-control-label" for="jenis_{{ $i }}">{{ $label }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          {{-- 2) LAMPIRAN DOKUMEN --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title">Lampiran Dokumen</p>
              <p class="section-hint">Unggah dokumen pendukung (jika ada).</p>
            </div>
            <div class="card-body pt-3">

              <div class="form-group">
                <label>Formulir Pelaporan Kecelakaan (Sertakan Lampiran Dokumentasi)</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="lampiran_pelaporan" name="lampiran_pelaporan">
                  <label class="custom-file-label" for="lampiran_pelaporan">Tambahkan file</label>
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Formulir Investigasi Kecelakaan (Sertakan Lampiran Daftar Hadir)</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="lampiran_investigasi" name="lampiran_investigasi">
                  <label class="custom-file-label" for="lampiran_investigasi">Tambahkan file</label>
                </div>
              </div>

            </div>
          </div>

          {{-- 3) IDENTITAS PELAPOR --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title">Identitas Pelapor</p>
              <p class="section-hint">Isi data pelapor untuk kebutuhan tindak lanjut.</p>
            </div>
            <div class="card-body pt-3">

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Nama Pelapor</label>
                    <input type="text" class="form-control" name="nama_pelapor" placeholder="Nama lengkap">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Jenis Kelamin</label>
                    <div class="d-flex flex-wrap" style="gap:14px;">
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="jk_l" name="jenis_kelamin" value="Laki-laki">
                        <label class="custom-control-label" for="jk_l">Laki-laki</label>
                      </div>
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="jk_p" name="jenis_kelamin" value="Perempuan">
                        <label class="custom-control-label" for="jk_p">Perempuan</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Lokasi Kerja</label>
                    <input type="text" class="form-control" name="lokasi_kerja" placeholder="Contoh: Site A / Workshop / Gudang">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Department/Bagian</label>
                    <input type="text" class="form-control" name="departemen" placeholder="Contoh: Produksi / Maintenance / HSE">
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Nama Korban (Jika ada)</label>
                <input type="text" class="form-control" name="nama_korban" placeholder="Opsional">
              </div>

            </div>
          </div>

          {{-- 4) TEMPAT & WAKTU --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title">Tempat dan Waktu Terjadinya Insiden</p>
              <p class="section-hint">Isi lokasi, tanggal, dan waktu insiden terjadi.</p>
            </div>
            <div class="card-body pt-3">

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Tempat</label>
                    <input type="text" class="form-control" name="tempat" placeholder="Contoh: Area Loading / Workshop A">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Pukul</label>
                    <input type="time" class="form-control" name="pukul">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="required">Uraian Terjadinya Insiden</label>
                <textarea class="form-control" rows="4" name="uraian_insiden" placeholder="Jelaskan kronologi singkat kejadian..."></textarea>
              </div>

              <div class="form-group">
                <label class="required">Gambar/Foto</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="foto_insiden" name="foto_insiden" accept="image/*,application/pdf">
                  <label class="custom-file-label" for="foto_insiden">Tambahkan file</label>
                </div>
                <small class="text-muted d-block mt-1">Format umum: JPG/PNG/PDF.</small>
              </div>

              <div class="form-group mb-0">
                <label class="required">Korban Memakai Alat Pelindung Diri (Jika tidak berikan alasannya)</label>
                <div class="d-flex flex-wrap" style="gap:14px;">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="apd_ya" name="apd" value="Ya">
                    <label class="custom-control-label" for="apd_ya">Ya</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="apd_lain" name="apd" value="Tidak / Lainnya">
                    <label class="custom-control-label" for="apd_lain">Yang lain:</label>
                  </div>
                </div>
                <input type="text" class="form-control mt-2" name="apd_alasan" placeholder="Jika tidak memakai APD, jelaskan alasannya...">
              </div>

            </div>
          </div>

          {{-- 5) KONDISI KORBAN --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title">Kondisi Korban/Penderita (jika ada)</p>
              <p class="section-hint">Centang kondisi yang sesuai.</p>
            </div>
            <div class="card-body pt-3">
              @php
                $kondisi = [
                  'Luka bakar','Luka terbuka','Patah tulang','Luka sayat','Perdarahan','Sesak nafas','Luka memar','Luka tusuk',
                  'Luka amputasi (partial/total)','Snake bite (gigitan ular)','Insect bite (gigitan serangga)','Iritasi mata','Iritasi kulit',
                  'Cedera tulang belakang (kecetit)',
                ];
              @endphp

              <div class="row">
                @foreach($kondisi as $i => $label)
                  <div class="col-md-6">
                    <div class="custom-control custom-checkbox mb-2">
                      <input class="custom-control-input" type="checkbox" id="kondisi_{{ $i }}" name="kondisi_korban[]" value="{{ $label }}">
                      <label class="custom-control-label" for="kondisi_{{ $i }}">{{ $label }}</label>
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="form-group mb-0 mt-2">
                <label>Yang lain</label>
                <input type="text" class="form-control" name="kondisi_lain" placeholder="Isi jika ada kondisi lain...">
              </div>
            </div>
          </div>

          {{-- 6) KERUSAKAN PROPERTI --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Ada Kerusakan Property? (Jika ada jelaskan)</p>
              <p class="section-hint">Tuliskan detail kerusakan jika terjadi.</p>
            </div>
            <div class="card-body pt-3">
              <textarea class="form-control" rows="3" name="kerusakan_property" placeholder="Jelaskan kerusakan, aset terdampak, dsb..."></textarea>
            </div>
          </div>

          {{-- 7) PENCEMARAN LINGKUNGAN --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Apakah Ada Pencemaran Lingkungan? (Jika ada jelaskan)</p>
              <p class="section-hint">Contoh: tumpahan oli, limbah, asap, dll.</p>
            </div>
            <div class="card-body pt-3">
              <textarea class="form-control" rows="3" name="pencemaran_lingkungan" placeholder="Jelaskan pencemaran & penanganan awal..."></textarea>
            </div>
          </div>

          {{-- 8) TINDAK LANJUT KORBAN --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Tindak Lanjut Korban</p>
              <p class="section-hint">Pilih tindak lanjut yang dilakukan.</p>
            </div>
            <div class="card-body pt-3">
              <div class="custom-control custom-radio mb-2">
                <input class="custom-control-input" type="radio" id="tl_p3k" name="tindak_lanjut" value="Penanganan P3K">
                <label class="custom-control-label" for="tl_p3k">Penanganan P3K</label>
              </div>
              <div class="custom-control custom-radio mb-2">
                <input class="custom-control-input" type="radio" id="tl_faskes" name="tindak_lanjut" value="Dirujuk ke Faskes">
                <label class="custom-control-label" for="tl_faskes">Dirujuk ke Faskes</label>
              </div>
              <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="tl_lain" name="tindak_lanjut" value="Lainnya">
                <label class="custom-control-label" for="tl_lain">Yang lain</label>
              </div>
              <input type="text" class="form-control mt-2" name="tindak_lanjut_lain" placeholder="Jika lainnya, jelaskan...">
            </div>
          </div>

          {{-- 9) EMAIL ATASAN --}}
          <div class="card section-card mb-0">
            <div class="card-header">
              <p class="section-title required">Email Atasan</p>
              <p class="section-hint">Pilih email atasan terkait (sesuaikan data master di sistem).</p>
            </div>
            <div class="card-body pt-3">
              <select class="form-control" name="email_atasan" required>
                <option selected disabled value="">Pilih</option>
                <option value="shaffan_zain@pamitra.co.id">Shaffan_Zain@pamitra.co.id</option>
                <option value="hendrakurniajaya@pamitra.co.id">hendrakurniajaya@pamitra.co.id</option>
                <option value="rahmad.erwan@pamitra.co.id">rahmad.erwan@pamitra.co.id</option>
                <option value="guruh.alvianda@pamitra.co.id">guruh.alvianda@pamitra.co.id</option>
                <option value="indra.setiawan@pamitra.co.id">indra.setiawan@pamitra.co.id</option>
                <option value="septian.iskandar@pamitra.co.id">septian.iskandar@pamitra.co.id</option>
                <option value="lukman@pamitra.co.id">lukman@pamitra.co.id</option>
                <option value="anggatrilaksonoputro@pamitra.co.id">anggatrilaksonoputro@pamitra.co.id</option>
                <option value="erik.dewantara@pamitra.co.id">erik.dewantara@pamitra.co.id</option>
                <option value="it@pamitra.co.id">it@pamitra.co.id</option>
                <option value="faiq@pamitra.co.id">faiq@pamitra.co.id</option>
                <option value="rudianto@pamitra.co.id">rudianto@pamitra.co.id</option>
                <option value="zaenal.masqur@pamitra.co.id">zaenal.masqur@pamitra.co.id</option>
                <option value="daerubbi@pamitra.co.id">daerubbi@pamitra.co.id</option>
                <option value="fajar@pamitra.co.id">fajar@pamitra.co.id</option>
                <option value="eko.wardiyanto@pamitra.co.id">eko.wardiyanto@pamitra.co.id</option>
                <option value="gilanggusti@pamitra.co.id">gilanggusti@pamitra.co.id</option>
                <option value="antariksa@pamitra.co.id">antariksa@pamitra.co.id</option>
              </select>
              <small class="text-muted d-block mt-2">
                *Sebaiknya sumber data email atasan dari master user/atasan agar tidak hardcode.
              </small>
            </div>
          </div>

          <div class="alert alert-info mt-3 mb-0">
            <i class="fas fa-info-circle mr-1"></i>
            Setelah submit, sistem akan membuat nomor laporan dan HSE akan melakukan verifikasi.
          </div>

        </div>

        <div class="card-footer d-flex justify-content-between">
          <a href="#" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
          </a>
          <button type="submit" class="btn btn-info-match">
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
        label.textContent = (input.files.length === 1)
          ? input.files[0].name
          : input.files.length + ' files selected';
      }
    }
  });
</script>
@endpush