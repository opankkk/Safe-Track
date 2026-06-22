<div>
@section('title', 'Public Report | Sistem HSE')
@section('body-class', 'hold-transition layout-top-nav')
@section('is-auth', true)


@include('layouts.partials.report-accident-styles')
<div class="public-page">
  <div class="container">

    {{-- Menu --}}
    <div class="row">
      <div class="col-md-4 mb-3 menu-card">
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

      <div class="col-md-4 mb-3 menu-card">
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

      <div class="col-md-4 mb-3 menu-card">
        <div class="small-box" style="background-color: #DC3545; color: #fff;" data-toggle="modal" data-target="#modalAccidentReport">
          <div class="inner">
            <h4 class="font-weight-bold mb-1">Accident Report</h4>
            <p>Melaporkan insiden atau kecelakaan kerja.</p>
          </div>
          <div class="icon"><i class="fas fa-notes-medical"></i></div>
          <a href="javascript:void(0)" class="small-box-footer">
            Buat Laporan <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="alert alert-light border" style="border-radius:14px;">
      <i class="fas fa-info-circle mr-1"></i>
      Pilih jenis laporan yang sesuai. Setelah laporan terkirim, tim HSE akan melakukan verifikasi dan tindak lanjut.
    </div>

    @include('livewire.public.partials._unsafe-report-modals')

    {{-- MODAL: ACCIDENT REPORT --}}
    <div class="modal fade" id="modalAccidentReport" tabindex="-1" role="dialog" aria-labelledby="modalAccidentReportLabel" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAccidentReportLabel">
              <i class="fas fa-notes-medical text-success mr-1"></i> Laporan: Accident Report
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

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
        @if ($errors->any())
          <div class="alert alert-danger m-3">
            <i class="fas fa-exclamation-triangle mr-1"></i> <b>Periksa kembali isian Anda:</b>
            <ul class="mb-0 mt-1 pl-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          <script>
            setTimeout(() => {
              const modalBody = document.querySelector('#modalAccidentReport .modal-body');
              if (modalBody) {
                modalBody.scrollTo({ top: 0, behavior: 'smooth' });
              }
            }, 100);
          </script>
        @endif

        <div class="card-body">

          {{-- 1) JENIS INSIDEN/ACCIDENT --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Jenis Insiden/Accident</p>
              <p class="section-hint">Pilih salah satu kategori insiden/kecelakaan.</p>
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
              @error('jenis_insiden') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
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
                <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                  <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modalTestPelaporanKecelakaan">
                    <i class="fas fa-file-signature mr-1"></i>
                    {{ $generated_pelaporan_path ? 'Ubah Formulir' : 'Form Pelaporan' }}
                  </button>
                  @if($generated_pelaporan_path)
                    <button type="button" class="btn btn-outline-secondary" wire:click="downloadGeneratedPelaporan">
                      <i class="fas fa-download mr-1"></i> Unduh PDF
                    </button>
                    <span class="text-success small">
                      <i class="fas fa-check-circle mr-1"></i>{{ $generated_pelaporan_name }}
                    </span>
                  @endif
                </div>
                <small class="text-muted d-block mt-1">Isi formulir, lalu sistem akan membuat lampiran PDF secara otomatis.</small>
              </div>

              <div class="form-group mb-0">
                <label>Formulir Investigasi Kecelakaan (Sertakan Lampiran Daftar Hadir)</label>
                <div class="mb-2">
                  <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#modalTestInvestigasiKecelakaan">
                    <i class="fas fa-search-plus mr-1"></i> {{ $generated_investigasi_path ? 'Ubah Form Investigasi' : 'Form Investigasi' }}
                  </button>
                  @if($generated_investigasi_path)
                    <button type="button" class="btn btn-outline-secondary ml-1" wire:click="downloadGeneratedInvestigation">
                      <i class="fas fa-download mr-1"></i> Unduh PDF
                    </button>
                    <span class="text-success small ml-2"><i class="fas fa-check-circle mr-1"></i>{{ $generated_investigasi_name }}</span>
                  @endif
                </div>
                <small class="text-muted d-block mt-1">Isi formulir, lalu sistem akan membuat lampiran PDF investigasi secara otomatis.</small>
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
                    @error('nama_pelapor') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">No Handphone</label>
                    <input type="text" class="form-control" wire:model="no_handphone" placeholder="Contoh: 081234567890">
                    @error('no_handphone') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>NIP <small class="text-muted">(opsional)</small></label>
                    <input type="text" class="form-control" wire:model="nip" placeholder="Nomor Induk Pegawai (opsional)">
                    @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">No Telepon</label>
                    <input type="text" class="form-control" wire:model="no_telepon" placeholder="Contoh: 031-1234567">
                    @error('no_telepon') <small class="text-danger">{{ $message }}</small> @enderror
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
                    @error('jenis_kelamin') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Lokasi Kerja</label>
                    <select class="form-control" wire:model.live="lokasi_kerja">
                      <option selected disabled value="">-- Pilih Lokasi Kerja --</option>
                      <option value="Head Office">Head Office</option>
                      <option value="Operational Office">Operational Office</option>
                      <option value="Workshop">Workshop</option>
                      <option value="Yang Lain">Yang Lain (Site A/B/C)</option>
                    </select>
                    @error('lokasi_kerja') <small class="text-danger">{{ $message }}</small> @enderror
                    @if($lokasi_kerja === 'Yang Lain')
                      <input type="text" class="form-control mt-2" wire:model="lokasi_kerja_lain"
                             placeholder="Contoh: Site A / Site B / Site C">
                    @endif
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Department/Bagian</label>
                    <select class="form-control" wire:model.live="departemen">
                      <option selected disabled value="">-- Pilih Departemen --</option>
                      <option value="HSE">HSE</option>
                      <option value="Procurement">Procurement</option>
                      <option value="Project Manager">Project Manager</option>
                      <option value="BOD & GM">BOD & GM</option>
                      <option value="EBD">EBD</option>
                      <option value="FAT">FAT</option>
                      <option value="HCFC">HCFC</option>
                      <option value="IT">IT</option>
                      <option value="Legal">Legal</option>
                      <option value="Workshop">Workshop</option>
                    </select>
                    @error('departemen') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Nama Korban (Jika ada)</label>
                <input type="text" class="form-control" wire:model="nama_korban" placeholder="Opsional">
              </div>

            </div>
          </div>

          {{-- 4 TEMPAT & WAKTU --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title">Tempat dan Waktu Terjadinya Insiden</p>
              <p class="section-hint">Isi lokasi, tanggal, dan waktu insiden terjadi.</p>
            </div>
            <div class="card-body pt-3">

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Tempat Kejadian</label>
                    <select class="form-control" wire:model="tempat">
                      <option selected disabled value="">-- Pilih Tempat --</option>
                      <option value="Workshop">Workshop</option>
                      <option value="Site A">Site A</option>
                      <option value="Site B">Site B</option>
                      <option value="Site C">Site C</option>
                    </select>
                    @error('tempat') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Tanggal</label>
                    <input type="date" class="form-control" wire:model="tanggal">
                    @error('tanggal') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Pukul</label>
                    <input type="time" class="form-control" wire:model="pukul">
                    @error('pukul') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="required">Uraian Terjadinya Insiden</label>
                <textarea class="form-control" rows="4" wire:model="uraian_insiden" placeholder="Jelaskan kronologi singkat kejadian..."></textarea>
                @error('uraian_insiden') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="form-group">
                <label class="required">Gambar/Foto (Kejadian)</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="foto_insiden" wire:model="foto_insiden" accept="image/png, image/jpeg, image/jpg">
                  <label class="custom-file-label" for="foto_insiden">
                    {{ $foto_insiden && is_object($foto_insiden) ? $foto_insiden->getClientOriginalName() : 'Tambahkan file' }}
                  </label>
                </div>
                <div wire:loading wire:target="foto_insiden" class="text-info mt-1 small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</div>
                @error('foto_insiden') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                <small class="text-muted d-block mt-1">PNG, JPG, JPEG (Maks. 2MB).</small>
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
                @error('apd') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
              </div>

            </div>
          </div>

          {{-- 5 KONDISI KORBAN --}}
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
              @error('kondisi_korban') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror

              @if($kondisi_korban === 'Yang lain')
              <div class="form-group mb-0 mt-2">
                <label>Jelaskan Kondisi Lainnya</label>
                <input type="text" class="form-control" wire:model="kondisi_lain" placeholder="Isi jika ada kondisi lain...">
              </div>
              @endif
            </div>
          </div>

          {{-- 6 KERUSAKAN PROPERTI --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Ada Kerusakan Property? (Jika ada jelaskan)</p>
              <p class="section-hint">Tuliskan detail kerusakan jika terjadi.</p>
            </div>
            <div class="card-body pt-3">
              <textarea class="form-control" rows="3" wire:model="kerusakan_property" placeholder="Jelaskan kerusakan, aset terdampak, dsb..."></textarea>
              @error('kerusakan_property') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>

          {{-- 7 PENCEMARAN LINGKUNGAN --}}
          <div class="card section-card mb-3">
            <div class="card-header">
              <p class="section-title required">Apakah Ada Pencemaran Lingkungan? (Jika ada jelaskan)</p>
              <p class="section-hint">Contoh: tumpahan oli, limbah, asap, dll.</p>
            </div>
            <div class="card-body pt-3">
              <textarea class="form-control" rows="3" wire:model="pencemaran_lingkungan" placeholder="Jelaskan pencemaran & penanganan awal..."></textarea>
              @error('pencemaran_lingkungan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>

          {{-- 8 TINDAK LANJUT KORBAN --}}
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
              @error('tindak_lanjut') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
            </div>
          </div>

          {{-- 9 EMAIL ATASAN --}}
          <div class="card section-card mb-0">
            <div class="card-header">
              <p class="section-title required">Email Atasan</p>
              <p class="section-hint">Otomatis terisi sesuai departemen yang dipilih.</p>
            </div>
            <div class="card-body pt-3">
              <input type="email" class="form-control" wire:model="email_atasan" readonly required placeholder="Otomatis terisi berdasarkan departemen">
              @error('email_atasan') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
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
          <button type="submit" class="btn btn-info-match" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm mr-1"></span>
            <i class="fas fa-paper-plane mr-1" wire:loading.remove wire:target="save"></i> Kirim Laporan
          </button>
        </div>
      </form>
    </div>

    @include('livewire.public.partials._accident-pdf-test-modals')

          </div>
        </div>
      </div>
    </div>

    <div class="text-center text-muted mt-3">
      <small>&copy; {{ date('Y') }} Sistem HSE</small>
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

  window.addEventListener('scrollToTop', event => {
    const params = event.detail[0] || event.detail;
    if (params && params.modal) {
      const modalObj = document.getElementById(params.modal);
      const modalBody = modalObj ? modalObj.querySelector('.modal-body') : null;
      if (modalBody) {
        const firstTarget = modalBody.querySelector('.alert-success, .alert-danger, .is-invalid, .invalid-feedback');
        if (firstTarget) {
          firstTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
          modalBody.scrollTo({ top: 0, behavior: 'smooth' });
        }
        return;
      }
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  window.addEventListener('pelaporan-pdf-generated', () => {
    $('#modalTestPelaporanKecelakaan').modal('hide');
  });

  window.addEventListener('investigasi-pdf-generated', () => {
    $('#modalTestInvestigasiKecelakaan').modal('hide');
  });
</script>
@endpush
