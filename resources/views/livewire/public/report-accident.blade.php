<div>
@section('title', 'Accident Report | Sistem HSE')
@section('body-class', 'hold-transition layout-top-nav')
@section('is-auth', true)

@push('styles')
@endpush

@include('layouts.partials.report-accident-styles')
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

      <form wire:submit="save">
        
        @if (session()->has('success'))
          <div class="alert alert-success m-3">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          </div>
        @endif
        @if (session()->has('error'))
          <div class="alert alert-danger m-3">
            <i class="fas fa-times-circle mr-1"></i> {{ session('error') }}
          </div>
        @endif

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
                      <input class="custom-control-input" type="radio" id="jenis_{{ $i }}" name="jenis_insiden" wire:model.live="jenis_insiden" value="{{ $label }}">
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
                  <input type="file" class="custom-file-input" id="lampiran_pelaporan" wire:model="lampiran_pelaporan">
                  <label class="custom-file-label" for="lampiran_pelaporan">
                    {{ $lampiran_pelaporan && is_object($lampiran_pelaporan) ? $lampiran_pelaporan->getClientOriginalName() : 'Tambahkan file' }}
                  </label>
                </div>
                <div wire:loading wire:target="lampiran_pelaporan" class="text-info mt-1 small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</div>
              </div>

              <div class="form-group mb-0">
                <label>Formulir Investigasi Kecelakaan (Sertakan Lampiran Daftar Hadir)</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="lampiran_investigasi" wire:model="lampiran_investigasi">
                  <label class="custom-file-label" for="lampiran_investigasi">
                    {{ $lampiran_investigasi && is_object($lampiran_investigasi) ? $lampiran_investigasi->getClientOriginalName() : 'Tambahkan file' }}
                  </label>
                </div>
                <div wire:loading wire:target="lampiran_investigasi" class="text-info mt-1 small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</div>
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
                    <input type="text" class="form-control" wire:model="nama_pelapor" placeholder="Nama lengkap">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">No Handphone</label>
                    <input type="text" class="form-control" wire:model="no_handphone" placeholder="Contoh: 081234567890">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Jenis Kelamin</label>
                    <div class="d-flex flex-wrap" style="gap:14px;">
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="jk_l" name="jenis_kelamin" wire:model.live="jenis_kelamin" value="Laki-laki">
                        <label class="custom-control-label" for="jk_l">Laki-laki</label>
                      </div>
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="jk_p" name="jenis_kelamin" wire:model.live="jenis_kelamin" value="Perempuan">
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
                    <input type="text" class="form-control" wire:model="lokasi_kerja" placeholder="Contoh: Site A / Workshop / Gudang">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Department/Bagian</label>
                    <input type="text" class="form-control" wire:model="departemen" placeholder="Contoh: Produksi / Maintenance / HSE">
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Nama Korban (Jika ada)</label>
                <input type="text" class="form-control" wire:model="nama_korban" placeholder="Opsional">
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
                    <input type="text" class="form-control" wire:model="tempat" placeholder="Contoh: Area Loading / Workshop A">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Tanggal</label>
                    <input type="date" class="form-control" wire:model="tanggal">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Pukul</label>
                    <input type="time" class="form-control" wire:model="pukul">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="required">Uraian Terjadinya Insiden</label>
                <textarea class="form-control" rows="4" wire:model="uraian_insiden" placeholder="Jelaskan kronologi singkat kejadian..."></textarea>
              </div>

              <div class="form-group">
                <label class="required">Gambar/Foto</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="foto_insiden" wire:model="foto_insiden" accept="image/*,application/pdf">
                  <label class="custom-file-label" for="foto_insiden">
                    {{ $foto_insiden && is_object($foto_insiden) ? $foto_insiden->getClientOriginalName() : 'Tambahkan file' }}
                  </label>
                </div>
                <div wire:loading wire:target="foto_insiden" class="text-info mt-1 small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</div>
                <small class="text-muted d-block mt-1">Format umum: JPG/PNG/PDF.</small>
              </div>

              <div class="form-group mb-0">
                <label class="required">Korban Memakai Alat Pelindung Diri (Jika tidak berikan alasannya)</label>
                <div class="d-flex flex-wrap" style="gap:14px;">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="apd_ya" name="apd" wire:model.live="apd" value="Ya">
                    <label class="custom-control-label" for="apd_ya">Ya</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="apd_lain" name="apd" wire:model.live="apd" value="Tidak / Lainnya">
                    <label class="custom-control-label" for="apd_lain">Yang lain:</label>
                  </div>
                </div>
                @if($apd === 'Tidak / Lainnya')
                <input type="text" class="form-control mt-2" wire:model="apd_alasan" placeholder="Jika tidak memakai APD, jelaskan alasannya...">
                @endif
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
                  'Cedera tulang belakang (kecetit)', 'Yang lain'
                ];
              @endphp

              <div class="row">
                @foreach($kondisi as $i => $label)
                  <div class="col-md-6">
                    <div class="custom-control custom-radio mb-2">
                      <input class="custom-control-input" type="radio" id="kondisi_{{ $i }}" name="kondisi_korban" wire:model.live="kondisi_korban" value="{{ $label }}">
                      <label class="custom-control-label" for="kondisi_{{ $i }}">{{ $label }}</label>
                    </div>
                  </div>
                @endforeach
              </div>

              @if($kondisi_korban === 'Yang lain')
              <div class="form-group mb-0 mt-2">
                <label>Jelaskan Kondisi Lainnya</label>
                <input type="text" class="form-control" wire:model="kondisi_lain" placeholder="Isi jika ada kondisi lain...">
              </div>
              @endif
            </div>
          </div>

          {{-- 6) KERUSAKAN PROPERTI --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Ada Kerusakan Property? (Jika ada jelaskan)</p>
              <p class="section-hint">Tuliskan detail kerusakan jika terjadi.</p>
            </div>
            <div class="card-body pt-3">
              <textarea class="form-control" rows="3" wire:model="kerusakan_property" placeholder="Jelaskan kerusakan, aset terdampak, dsb..."></textarea>
            </div>
          </div>

          {{-- 7) PENCEMARAN LINGKUNGAN --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Apakah Ada Pencemaran Lingkungan? (Jika ada jelaskan)</p>
              <p class="section-hint">Contoh: tumpahan oli, limbah, asap, dll.</p>
            </div>
            <div class="card-body pt-3">
              <textarea class="form-control" rows="3" wire:model="pencemaran_lingkungan" placeholder="Jelaskan pencemaran & penanganan awal..."></textarea>
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
                <input class="custom-control-input" type="radio" id="tl_p3k" name="tindak_lanjut" wire:model.live="tindak_lanjut" value="Penanganan P3K">
                <label class="custom-control-label" for="tl_p3k">Penanganan P3K</label>
              </div>
              <div class="custom-control custom-radio mb-2">
                <input class="custom-control-input" type="radio" id="tl_faskes" name="tindak_lanjut" wire:model.live="tindak_lanjut" value="Dirujuk ke Faskes">
                <label class="custom-control-label" for="tl_faskes">Dirujuk ke Faskes</label>
              </div>
              <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="tl_lain" name="tindak_lanjut" wire:model.live="tindak_lanjut" value="Lainnya">
                <label class="custom-control-label" for="tl_lain">Yang lain</label>
              </div>
              @if($tindak_lanjut === 'Lainnya')
              <input type="text" class="form-control mt-2" wire:model="tindak_lanjut_lain" placeholder="Jika lainnya, jelaskan...">
              @endif
            </div>
          </div>

          {{-- 9) EMAIL ATASAN --}}
          <div class="card section-card mb-0">
            <div class="card-header">
              <p class="section-title required">Email Atasan</p>
              <p class="section-hint">Pilih email atasan terkait (sesuaikan data master di sistem).</p>
            </div>
            <div class="card-body pt-3">
              <select class="form-control" wire:model="email_atasan" required>
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
</div>

@push('scripts')
<script>
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