<div>
@section('title', 'Report Unsafe | Sistem HSE')
@section('body-class', 'hold-transition layout-top-nav')
@section('is-auth', true)

@include('layouts.partials.report-unsafe-styles')
<div class="public-page">
  <div class="container">

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
  MODAL: UNSAFE ACTION
========================================== --}}
<div class="modal fade" id="modalUnsafeAction" tabindex="-1" role="dialog" aria-labelledby="modalUnsafeActionLabel" aria-hidden="true" wire:ignore.self>
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

      <form wire:submit="saveUnsafeAction">
        <div class="modal-body">
          @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
          @endif
          @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
          @endif

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
                    <input type="date" class="form-control @error('ua_tanggal_pengamatan') is-invalid @enderror" wire:model="ua_tanggal_pengamatan">
                    @error('ua_tanggal_pengamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Waktu Pengamatan</label>
                    <input type="time" class="form-control @error('ua_waktu_pengamatan') is-invalid @enderror" wire:model="ua_waktu_pengamatan">
                    @error('ua_waktu_pengamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Nama Anda</label>
                    <input type="text" class="form-control @error('ua_nama') is-invalid @enderror" wire:model="ua_nama" placeholder="Nama lengkap">
                    @error('ua_nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    <input class="custom-control-input @error('ua_status') is-invalid @enderror" type="radio" id="ua_status_pamitra" wire:model="ua_status" value="Karyawan Pamitra">
                    <label class="custom-control-label" for="ua_status_pamitra">Karyawan Pamitra</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input @error('ua_status') is-invalid @enderror" type="radio" id="ua_status_sub" wire:model="ua_status" value="Karyawan Sub-Kontraktor">
                    <label class="custom-control-label" for="ua_status_sub">Karyawan Sub-Kontraktor</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input @error('ua_status') is-invalid @enderror" type="radio" id="ua_status_tamu" wire:model="ua_status" value="Tamu">
                    <label class="custom-control-label" for="ua_status_tamu">Tamu</label>
                  </div>
                </div>
                @error('ua_status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                        <input class="custom-control-input @error('ua_departemen') is-invalid @enderror" type="radio" id="ua_dept_{{ $i }}" wire:model="ua_departemen" value="{{ $d }}">
                        <label class="custom-control-label" for="ua_dept_{{ $i }}">{{ $d }}</label>
                      </div>
                    </div>
                  @endforeach
                </div>
                @error('ua_departemen') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="form-group mb-0">
                <label>Nama Perusahaan (Untuk Sub Kontraktor/Tamu)</label>
                <input type="text" class="form-control" wire:model="ua_perusahaan" placeholder="Opsional">
              </div>

            </div>
          </div>

          {{-- Detail Unsafe --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Detail Unsafe Action</p>
              <p class="s">Mengikuti format form PDF.</p>
            </div>
            <div class="card-body">

              <div class="form-group">
                <label class="required">Perilaku yang Diamati</label>
                <textarea class="form-control @error('ua_perilaku') is-invalid @enderror" rows="3" wire:model="ua_perilaku" placeholder="Jelaskan unsafe action..."></textarea>
                @error('ua_perilaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Foto Sebelum (Before)</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input @error('ua_foto_sebelum') is-invalid @enderror" id="ua_foto_sebelum" wire:model="ua_foto_sebelum" accept="image/png, image/jpeg, image/jpg">
                      <label class="custom-file-label" for="ua_foto_sebelum">
                        {{ $ua_foto_sebelum ? $ua_foto_sebelum->getClientOriginalName() : 'Pilih file' }}
                      </label>
                    </div>
                    @error('ua_foto_sebelum') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div wire:loading wire:target="ua_foto_sebelum" class="text-info mt-1 small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Foto Sesudah (After)</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input @error('ua_foto_sesudah') is-invalid @enderror" id="ua_foto_sesudah" wire:model="ua_foto_sesudah" accept="image/png, image/jpeg, image/jpg">
                      <label class="custom-file-label" for="ua_foto_sesudah">
                        {{ $ua_foto_sesudah ? $ua_foto_sesudah->getClientOriginalName() : 'Pilih file' }}
                      </label>
                    </div>
                    @error('ua_foto_sesudah') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div wire:loading wire:target="ua_foto_sesudah" class="text-info mt-1 small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</div>
                  </div>
                </div>
              </div>
              <small class="text-muted d-block mt-n2 mb-3">Format JPG, JPEG, PNG (Maks. 2MB).</small>

              <div class="form-group">
                <label class="required">Lokasi</label>
                <input type="text" class="form-control @error('ua_lokasi') is-invalid @enderror" wire:model="ua_lokasi" placeholder="Contoh: Workshop A / Gudang">
                @error('ua_lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="form-group">
                <label class="required">Dampak yang Anda Yakini dapat Terjadi</label>
                <textarea class="form-control @error('ua_dampak') is-invalid @enderror" rows="2" wire:model="ua_dampak" placeholder="Contoh: cidera..."></textarea>
                @error('ua_dampak') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="form-group mb-0">
                <label class="required">Perbaikan dan Pencegahan yang Dilakukan</label>
                <textarea class="form-control @error('ua_perbaikan') is-invalid @enderror" rows="2" wire:model="ua_perbaikan" placeholder="Contoh: pasang rambu, briefing..."></textarea>
                @error('ua_perbaikan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

            </div>
          </div>

          {{-- Email Atasan --}}
          <div class="section-card mb-0">
            <div class="section-head">
              <p class="t">Approval</p>
            </div>
            <div class="card-body">
              <div class="form-group mb-0">
                <label class="required">Email Atasan/Sponsor</label>
                <select class="form-control" wire:model="ua_email_atasan" required>
                  <option selected disabled value="">Pilih</option>
                  <option value="shaffan_zain@pamitra.co.id">shaffan_zain@pamitra.co.id</option>
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
            <span wire:loading wire:target="saveUnsafeAction" class="spinner-border spinner-border-sm mr-1"></span>
            <i class="fas fa-paper-plane mr-1" wire:loading.remove wire:target="saveUnsafeAction"></i> Kirim Unsafe Action
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ==========================================
  MODAL: UNSAFE CONDITION
========================================== --}}
<div class="modal fade" id="modalUnsafeCondition" tabindex="-1" role="dialog" aria-labelledby="modalUnsafeConditionLabel" aria-hidden="true" wire:ignore.self>
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

      <form wire:submit="saveUnsafeCondition">
        <div class="modal-body">
          @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
          @endif
          @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
          @endif

          {{-- Data Pengamatan --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Data Pengamatan</p>
            </div>
            <div class="card-body">

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Tanggal Pengamatan</label>
                    <input type="date" class="form-control @error('uc_tanggal_pengamatan') is-invalid @enderror" wire:model="uc_tanggal_pengamatan">
                    @error('uc_tanggal_pengamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Waktu Pengamatan</label>
                    <input type="time" class="form-control @error('uc_waktu_pengamatan') is-invalid @enderror" wire:model="uc_waktu_pengamatan">
                    @error('uc_waktu_pengamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="required">Nama Anda</label>
                    <input type="text" class="form-control @error('uc_nama') is-invalid @enderror" wire:model="uc_nama" placeholder="Nama lengkap">
                    @error('uc_nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>

            </div>
          </div>

          {{-- Status + Departemen --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Status & Unit</p>
            </div>
            <div class="card-body">

              <div class="form-group">
                <label class="required">Status</label>
                <div class="d-flex flex-wrap" style="gap:14px;">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input @error('uc_status') is-invalid @enderror" type="radio" id="uc_status_pamitra" wire:model="uc_status" value="Karyawan Pamitra">
                    <label class="custom-control-label" for="uc_status_pamitra">Karyawan Pamitra</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input @error('uc_status') is-invalid @enderror" type="radio" id="uc_status_sub" wire:model="uc_status" value="Karyawan Sub-Kontraktor">
                    <label class="custom-control-label" for="uc_status_sub">Karyawan Sub-Kontraktor</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input @error('uc_status') is-invalid @enderror" type="radio" id="uc_status_tamu" wire:model="uc_status" value="Tamu">
                    <label class="custom-control-label" for="uc_status_tamu">Tamu</label>
                  </div>
                </div>
                @error('uc_status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                        <input class="custom-control-input @error('uc_departemen') is-invalid @enderror" type="radio" id="uc_dept_{{ $i }}" wire:model="uc_departemen" value="{{ $d }}">
                        <label class="custom-control-label" for="uc_dept_{{ $i }}">{{ $d }}</label>
                      </div>
                    </div>
                  @endforeach
                </div>
                @error('uc_departemen') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="form-group mb-0">
                <label>Nama Perusahaan (Untuk Sub Kontraktor/Tamu)</label>
                <input type="text" class="form-control" wire:model="uc_perusahaan" placeholder="Opsional">
              </div>

            </div>
          </div>

          {{-- Detail Unsafe --}}
          <div class="section-card">
            <div class="section-head">
              <p class="t">Detail Unsafe Condition</p>
            </div>
            <div class="card-body">

              <div class="form-group">
                <label class="required">Kondisi yang Diamati</label>
                <textarea class="form-control @error('uc_kondisi') is-invalid @enderror" rows="3" wire:model="uc_kondisi" placeholder="Jelaskan kondisi..."></textarea>
                @error('uc_kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Foto Sebelum (Before)</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input @error('uc_foto_sebelum') is-invalid @enderror" id="uc_foto_sebelum" wire:model="uc_foto_sebelum" accept="image/png, image/jpeg, image/jpg">
                      <label class="custom-file-label" for="uc_foto_sebelum">
                        {{ $uc_foto_sebelum ? $uc_foto_sebelum->getClientOriginalName() : 'Pilih file' }}
                      </label>
                    </div>
                    @error('uc_foto_sebelum') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div wire:loading wire:target="uc_foto_sebelum" class="text-info mt-1 small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Foto Sesudah (After)</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input @error('uc_foto_sesudah') is-invalid @enderror" id="uc_foto_sesudah" wire:model="uc_foto_sesudah" accept="image/png, image/jpeg, image/jpg">
                      <label class="custom-file-label" for="uc_foto_sesudah">
                        {{ $uc_foto_sesudah ? $uc_foto_sesudah->getClientOriginalName() : 'Pilih file' }}
                      </label>
                    </div>
                    @error('uc_foto_sesudah') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div wire:loading wire:target="uc_foto_sesudah" class="text-info mt-1 small"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</div>
                  </div>
                </div>
              </div>
              <small class="text-muted d-block mt-n2 mb-3">Format JPG, JPEG, PNG (Maks. 2MB).</small>

              <div class="form-group">
                <label class="required">Lokasi</label>
                <input type="text" class="form-control @error('uc_lokasi') is-invalid @enderror" wire:model="uc_lokasi" placeholder="Lokasi">
                @error('uc_lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="form-group">
                <label class="required">Dampak yang Anda Yakini dapat Terjadi</label>
                <textarea class="form-control" rows="2" wire:model="uc_dampak" placeholder="Contoh: terpeleset..."></textarea>
              </div>

              <div class="form-group mb-0">
                <label class="required">Perbaikan/Pencegahan (dilakukan)</label>
                <textarea class="form-control" rows="2" wire:model="uc_perbaikan" placeholder="Contoh: lapisi tumpahan..."></textarea>
              </div>

            </div>
          </div>

          {{-- Email Atasan --}}
          <div class="section-card mb-0">
            <div class="section-head">
              <p class="t">Approval</p>
            </div>
            <div class="card-body">
              <div class="form-group mb-0">
                <label class="required">Email Atasan/Sponsor</label>
                <select class="form-control" wire:model="uc_email_atasan" required>
                  <option selected disabled value="">Pilih</option>
                  <option value="shaffan_zain@pamitra.co.id">shaffan_zain@pamitra.co.id</option>
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
          <button type="submit" class="btn btn-info-match">
            <span wire:loading wire:target="saveUnsafeCondition" class="spinner-border spinner-border-sm mr-1"></span>
            <i class="fas fa-paper-plane mr-1" wire:loading.remove wire:target="saveUnsafeCondition"></i> Kirim Unsafe Condition
          </button>
        </div>
      </form>
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
        label.textContent = input.files.length === 1
          ? input.files[0].name
          : (input.files.length + ' files selected');
      }
    }
  });

  window.addEventListener('scrollToTop', event => {
    const params = event.detail[0] || event.detail;
    if (params && params.modal) {
      const modalObj = document.getElementById(params.modal);
      if (!modalObj) return;

      const modalBody = modalObj.querySelector('.modal-body');
      if (!modalBody) return;

      // Cari elemen error pertama atau alert sukses
      const firstError = modalBody.querySelector('.alert-success, .is-invalid, .invalid-feedback');
      
      if (firstError) {
        // Scroll ke elemen error/sukses
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else {
        // Jika tidak ada error (mungkin sukses), scroll ke atas
        modalBody.scrollTo({ top: 0, behavior: 'smooth' });
      }

      // Auto-hide alerts after 5 seconds
      setTimeout(() => {
        $(modalBody).find('.alert').fadeOut('slow', function() {
          $(this).remove();
        });
      }, 5000);
    }
  });
</script>
@endpush