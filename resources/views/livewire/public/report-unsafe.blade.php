@extends('layouts.app')

@section('title', 'Report Unsafe | Sistem HSE')
@section('body-class', 'hold-transition layout-top-nav')
@section('is-auth', true)

@push('styles')
<style>
  :root{
    --hse-primary:#2d6cdf;
    --hse-accent:#6f42c1;
    --hse-bg:#f4f6f9;
    --hse-text:#343a40;
    --hse-muted:#6c757d;
  }

  .public-page{
    min-height:100vh;
    background:
      radial-gradient(1200px 600px at 10% -10%, rgba(45,108,223,.18), transparent 55%),
      radial-gradient(900px 500px at 90% 0%, rgba(111,66,193,.18), transparent 55%),
      var(--hse-bg);
    padding: 30px 0 42px;
  }

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
    margin-bottom: 14px;
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

  .menu-card{ cursor:pointer; }
  .menu-card .small-box{
    border-radius:16px;
    box-shadow:0 14px 38px rgba(0,0,0,.08);
    transition:.2s ease;
    overflow:hidden;
  }
  .menu-card .small-box:hover{
    transform:translateY(-4px);
    box-shadow:0 20px 45px rgba(0,0,0,.12);
  }
  .menu-card .small-box .inner p{
    margin-bottom:0;
    opacity:.95;
  }
  .menu-card .small-box-footer{
    border-bottom-left-radius:16px;
    border-bottom-right-radius:16px;
  }

  /* Modal styling */
  .modal-content{
    border-radius:16px;
    border:1px solid rgba(0,0,0,.06);
    box-shadow:0 20px 55px rgba(0,0,0,.18);
    overflow:hidden;
  }
  .modal-header{
    background: linear-gradient(135deg, rgba(45,108,223,.10), rgba(111,66,193,.10));
    border-bottom:1px solid rgba(0,0,0,.06);
  }
  .modal-title{ font-weight:900; }

  /* Form styling */
  label{ font-weight:800; color:var(--hse-text); }
  .required::after{ content:" *"; color:#dc3545; font-weight:900; }

  .form-control, .custom-file-label, select.form-control, textarea.form-control{
    border-radius:12px;
    border-color: rgba(0,0,0,.12);
  }
  .form-control:focus, select.form-control:focus, textarea.form-control:focus{
    border-color: rgba(45,108,223,.45);
    box-shadow:0 0 0 .2rem rgba(45,108,223,.12);
  }

  .section-card{
    border-radius:14px;
    border:1px solid rgba(0,0,0,.06);
    overflow:hidden;
    background:#fff;
    margin-bottom:12px;
  }
  .section-card .section-head{
    padding:12px 14px;
    background: linear-gradient(135deg, rgba(45,108,223,.07), rgba(111,66,193,.07));
    border-bottom:1px solid rgba(0,0,0,.06);
  }
  .section-card .section-head .t{ margin:0; font-weight:900; color:var(--hse-text); }
  .section-card .section-head .s{ margin:2px 0 0; color:var(--hse-muted); font-size:.88rem; }

  .btn{
    border-radius:12px;
    font-weight:800;
    padding:.55rem .9rem;
  }

  .btn-gradient{
    background: linear-gradient(135deg, var(--hse-primary), var(--hse-accent));
    border:none;
    color:#fff;
  }
  .btn-gradient:hover{ filter:brightness(.98); color:#fff; }

  .hint{
    color:var(--hse-muted);
    font-size:.86rem;
  }
</style>
@endpush

@section('content')
<div class="public-page">
  <div class="container">

    {{-- Hero Header --}}
    <div class="hero">
      <div class="hero-left">
        <div class="hero-badge"><i class="fas fa-shield-alt"></i></div>
        <div>
          <p class="hero-title">Pelaporan Unsafe</p>
          <p class="hero-sub">Laporkan Unsafe Action atau Unsafe Condition. Tanpa login.</p>
        </div>
      </div>
      <a href="#" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-home mr-1"></i> Beranda
      </a>
    </div>

    {{-- Menu --}}
    <div class="row">
      <div class="col-md-6 mb-3 menu-card">
        <div class="small-box bg-warning" data-toggle="modal" data-target="#modalUnsafeAction">
          <div class="inner">
            <h4 class="font-weight-bold mb-1">Unsafe Action</h4>
            <p>Melaporkan tindakan tidak aman (perilaku/aksi).</p>
          </div>
          <div class="icon"><i class="fas fa-user-times"></i></div>
          <a href="javascript:void(0)" class="small-box-footer">
            Buat Laporan <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <div class="col-md-6 mb-3 menu-card">
        <div class="small-box bg-info" data-toggle="modal" data-target="#modalUnsafeCondition">
          <div class="inner">
            <h4 class="font-weight-bold mb-1">Unsafe Condition</h4>
            <p>Melaporkan kondisi/lingkungan tidak aman.</p>
          </div>
          <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
          <a href="javascript:void(0)" class="small-box-footer">
            Buat Laporan <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>

    {{-- Info --}}
    <div class="alert alert-light border" style="border-radius:14px;">
      <i class="fas fa-info-circle mr-1"></i>
      Setelah laporan terkirim, tim HSE akan melakukan verifikasi dan tindak lanjut.
    </div>

  </div>
</div>

{{-- ==========================================
  MODAL: UNSAFE ACTION (sesuai PDF)
========================================== --}}
<div class="modal fade" id="modalUnsafeAction" tabindex="-1" role="dialog" aria-labelledby="modalUnsafeActionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUnsafeActionLabel">
          <i class="fas fa-user-times text-warning mr-1"></i> Laporan: Unsafe Action
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="#" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">

          {{-- Data Pengamatan --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Data Pengamatan</p>
              <p class="s">Isi tanggal, waktu, dan identitas pengamat.</p>
            </div>
            <div class="card-body">

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Tanggal Pengamatan</label>
                    <input type="date" class="form-control" name="ua_tanggal_pengamatan">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Waktu Pengamatan</label>
                    <input type="time" class="form-control" name="ua_waktu_pengamatan">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Nama Anda</label>
                    <input type="text" class="form-control" name="ua_nama" placeholder="Nama lengkap">
                  </div>
                </div>
              </div>

            </div>
          </div>

          {{-- Status + Departemen --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Status & Unit</p>
              <p class="s">Pilih status dan departemen/subkontraktor.</p>
            </div>
            <div class="card-body">

              <div class="form-group">
                <label class="required">Status</label>
                <div class="d-flex flex-wrap" style="gap:14px;">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="ua_status_pamitra" name="ua_status" value="Karyawan Pamitra">
                    <label class="custom-control-label" for="ua_status_pamitra">Karyawan Pamitra</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="ua_status_sub" name="ua_status" value="Karyawan Sub-Kontraktor">
                    <label class="custom-control-label" for="ua_status_sub">Karyawan Sub-Kontraktor</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="ua_status_tamu" name="ua_status" value="Tamu">
                    <label class="custom-control-label" for="ua_status_tamu">Tamu</label>
                  </div>
                </div>
                <small class="hint d-block mt-1">Jika Sub-Kontraktor/Tamu, isi nama perusahaan di bawah.</small>
              </div>

              @php
                $departemen = [
                  'Produksi','Workshop','Supply Chain','Engineering','Finance Accounting Tax',
                  'Quality Control - Quality Assurance','Health Safety Environment','Human Capital & Facility Care',
                  'Information & Technology','Business Development','Legal','Sub Kontraktor/Tamu'
                ];
              @endphp

              <div class="form-group">
                <label class="required">Nama Departemen/Subkontraktor</label>
                <div class="row">
                  @foreach($departemen as $i => $d)
                    <div class="col-md-6">
                      <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" type="radio" id="ua_dept_{{ $i }}" name="ua_departemen" value="{{ $d }}">
                        <label class="custom-control-label" for="ua_dept_{{ $i }}">{{ $d }}</label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Nama Perusahaan (Untuk Sub Kontraktor/Tamu)</label>
                <input type="text" class="form-control" name="ua_perusahaan" placeholder="Opsional">
              </div>

            </div>
          </div>

          {{-- Detail Unsafe --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Detail Unsafe Action</p>
              <p class="s">Mengikuti format form PDF (perilaku diamati, lokasi, dampak, perbaikan, foto).</p>
            </div>
            <div class="card-body">

              <div class="form-group">
                <label class="required">Perilaku yang Diamati</label>
                <textarea class="form-control" rows="3" name="ua_perilaku" placeholder="Jelaskan unsafe action yang diamati..."></textarea>
              </div>

              <div class="form-group">
                <label class="required">Gambar/Foto (Before & After)</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="ua_foto" name="ua_foto[]" multiple>
                  <label class="custom-file-label" for="ua_foto">Tambahkan file</label>
                </div>
                <small class="hint d-block mt-1">Saran: batasi max 5 file (validasi server).</small>
              </div>

              <div class="form-group">
                <label class="required">Lokasi</label>
                <input type="text" class="form-control" name="ua_lokasi" placeholder="Contoh: Workshop A / Gudang">
              </div>

              <div class="form-group">
                <label class="required">Dampak yang Anda Yakini dapat Terjadi</label>
                <textarea class="form-control" rows="2" name="ua_dampak" placeholder="Contoh: terpeleset, cidera tangan, kebakaran..."></textarea>
              </div>

              <div class="form-group mb-0">
                <label class="required">Perbaikan dan Pencegahan yang Dilakukan</label>
                <textarea class="form-control" rows="2" name="ua_perbaikan" placeholder="Contoh: pasang rambu, rapikan kabel, briefing APD..."></textarea>
              </div>

            </div>
          </div>

          {{-- Email Atasan --}}
          <div class="section-card mb-0">
            <div class="section-head">
              <p class="t">Approval</p>
              <p class="s">Pilih email atasan/sponsor.</p>
            </div>
            <div class="card-body">
              <div class="form-group mb-0">
                <label class="required">Email Atasan/Sponsor</label>
                <select class="form-control" name="ua_email_atasan" required>
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
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-warning">
            <i class="fas fa-paper-plane mr-1"></i> Kirim Unsafe Action
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ==========================================
  MODAL: UNSAFE CONDITION (sesuai PDF)
========================================== --}}
<div class="modal fade" id="modalUnsafeCondition" tabindex="-1" role="dialog" aria-labelledby="modalUnsafeConditionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUnsafeConditionLabel">
          <i class="fas fa-exclamation-triangle text-info mr-1"></i> Laporan: Unsafe Condition
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="#" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">

          {{-- Data Pengamatan --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Data Pengamatan</p>
              <p class="s">Isi tanggal, waktu, dan identitas pengamat.</p>
            </div>
            <div class="card-body">

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Tanggal Pengamatan</label>
                    <input type="date" class="form-control" name="uc_tanggal_pengamatan">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Waktu Pengamatan</label>
                    <input type="time" class="form-control" name="uc_waktu_pengamatan">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Nama Anda</label>
                    <input type="text" class="form-control" name="uc_nama" placeholder="Nama lengkap">
                  </div>
                </div>
              </div>

            </div>
          </div>

          {{-- Status + Departemen --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Status & Unit</p>
              <p class="s">Pilih status dan departemen/subkontraktor.</p>
            </div>
            <div class="card-body">

              <div class="form-group">
                <label class="required">Status</label>
                <div class="d-flex flex-wrap" style="gap:14px;">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="uc_status_pamitra" name="uc_status" value="Karyawan Pamitra">
                    <label class="custom-control-label" for="uc_status_pamitra">Karyawan Pamitra</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="uc_status_sub" name="uc_status" value="Karyawan Sub-Kontraktor">
                    <label class="custom-control-label" for="uc_status_sub">Karyawan Sub-Kontraktor</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="uc_status_tamu" name="uc_status" value="Tamu">
                    <label class="custom-control-label" for="uc_status_tamu">Tamu</label>
                  </div>
                </div>
                <small class="hint d-block mt-1">Jika Sub-Kontraktor/Tamu, isi nama perusahaan di bawah.</small>
              </div>

              @php
                $departemen2 = [
                  'Produksi','Workshop','Supply Chain','Engineering','Finance Accounting Tax',
                  'Quality Control - Quality Assurance','Health Safety Environment','Human Capital & Facility Care',
                  'Information & Technology','Business Development','Legal','Sub Kontraktor/Tamu'
                ];
              @endphp

              <div class="form-group">
                <label class="required">Nama Departemen/Subkontraktor</label>
                <div class="row">
                  @foreach($departemen2 as $i => $d)
                    <div class="col-md-6">
                      <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" type="radio" id="uc_dept_{{ $i }}" name="uc_departemen" value="{{ $d }}">
                        <label class="custom-control-label" for="uc_dept_{{ $i }}">{{ $d }}</label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Nama Perusahaan (Untuk Sub Kontraktor/Tamu)</label>
                <input type="text" class="form-control" name="uc_perusahaan" placeholder="Opsional">
              </div>

            </div>
          </div>

          {{-- Detail Unsafe --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Detail Unsafe Condition</p>
              <p class="s">Mengikuti format form PDF (kondisi diamati, lokasi, dampak, perbaikan, foto).</p>
            </div>
            <div class="card-body">

              <div class="form-group">
                <label class="required">Kondisi yang Diamati</label>
                <textarea class="form-control" rows="3" name="uc_kondisi" placeholder="Jelaskan unsafe condition yang diamati..."></textarea>
              </div>

              <div class="form-group">
                <label class="required">Gambar/Foto (Before & After)</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="uc_foto" name="uc_foto[]" multiple>
                  <label class="custom-file-label" for="uc_foto">Tambahkan file</label>
                </div>
                <small class="hint d-block mt-1">Saran: batasi max 5 file (validasi server).</small>
              </div>

              <div class="form-group">
                <label class="required">Lokasi</label>
                <input type="text" class="form-control" name="uc_lokasi" placeholder="Contoh: Area Loading / Gudang">
              </div>

              <div class="form-group">
                <label class="required">Dampak yang Anda Yakini dapat Terjadi</label>
                <textarea class="form-control" rows="2" name="uc_dampak" placeholder="Contoh: terpeleset, tersandung, kebakaran..."></textarea>
              </div>

              <div class="form-group mb-0">
                <label class="required">Perbaikan dan Pencegahan yang Dilakukan</label>
                <textarea class="form-control" rows="2" name="uc_perbaikan" placeholder="Contoh: pasang anti-slip, rapikan kabel, perbaiki guard..."></textarea>
              </div>

            </div>
          </div>

          {{-- Email Atasan --}}
          <div class="section-card mb-0">
            <div class="section-head">
              <p class="t">Approval</p>
              <p class="s">Pilih email atasan/sponsor.</p>
            </div>
            <div class="card-body">
              <div class="form-group mb-0">
                <label class="required">Email Atasan/Sponsor</label>
                <select class="form-control" name="uc_email_atasan" required>
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
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-gradient">
            <i class="fas fa-paper-plane mr-1"></i> Kirim Unsafe Condition
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Update label custom-file-input (AdminLTE style)
  document.addEventListener('change', function (e) {
    if (e.target && e.target.classList.contains('custom-file-input')) {
      const input = e.target;
      const label = input.nextElementSibling;
      if (label && input.files && input.files.length > 0) {
        label.textContent = input.files.length === 1
          ? input.files[0].name
          : (input.files.length + ' files selected');
      }
    }
  });
</script>
@endpush