{{-- Test modals based on FORM PJK-HSE-FR-24-01 PDF templates. --}}

{{-- ==========================================
  MODAL TEST: FORMULIR PELAPORAN KECELAKAAN
========================================== --}}
<div class="modal fade" id="modalTestPelaporanKecelakaan" tabindex="-1" role="dialog" aria-labelledby="modalTestPelaporanKecelakaanLabel" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTestPelaporanKecelakaanLabel">
          <i class="fas fa-file-medical text-danger mr-1"></i> Test Form: Pelaporan Kecelakaan
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form>
        <div class="modal-body">
          <div class="alert alert-light border">
            <i class="fas fa-info-circle mr-1"></i>
            Modal ini sementara untuk test tampilan dari PDF. Belum dikaitkan ke proses simpan data.
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">Data Dokumen</p>
              <p class="s">Informasi header sesuai form PJK-HSE-FR-24-01.</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>No. Dokumen</label>
                    <input type="text" class="form-control" value="HSE-PJK/FR 24-01">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Revisi</label>
                    <input type="text" class="form-control" value="00">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Tanggal Terbit Dokumen</label>
                    <input type="text" class="form-control" value="18 Nov 2019">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Halaman</label>
                    <input type="text" class="form-control" value="1 dari 1">
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label class="required">Jenis Insiden</label>
                <div class="d-flex flex-wrap" style="gap:14px;">
                  @foreach(['Near miss', 'Kecelakaan perorangan', 'Gangguan Kesehatan'] as $index => $jenis)
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="test_pelaporan_jenis_{{ $index }}" name="test_pelaporan_jenis_insiden">
                      <label class="custom-control-label" for="test_pelaporan_jenis_{{ $index }}">{{ $jenis }}</label>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">A. Keterangan Tenaga Kerja (Korban)</p>
              <p class="s">Personal data of the victim.</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Nama</label>
                    <input type="text" class="form-control" placeholder="Nama korban">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" class="form-control">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select class="form-control">
                      <option value="">Pilih</option>
                      <option>Laki-laki</option>
                      <option>Perempuan</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" class="form-control" placeholder="Alamat korban">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Jenis Pekerjaan/Bagian</label>
                    <input type="text" class="form-control" placeholder="Department/bagian">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">B. Tempat dan Waktu Terjadinya Kecelakaan</p>
              <p class="s">Place and time of accident.</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Tempat</label>
                    <input type="text" class="form-control" placeholder="Lokasi kejadian">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="required">Tanggal</label>
                    <input type="date" class="form-control">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="required">Pukul</label>
                    <input type="time" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label class="required">Uraian Terjadinya Kecelakaan</label>
                <textarea class="form-control" rows="4" placeholder="Tuliskan kronologi kecelakaan..."></textarea>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">C. Kondisi dan Kerusakan</p>
              <p class="s">Keterangan awal yang terlihat saat pelaporan.</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Keterangan Luka/Kondisi Korban</label>
                    <textarea class="form-control" rows="3" placeholder="Eye witness report atau kondisi yang nampak mata..."></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Kerusakan Peralatan/Property</label>
                    <textarea class="form-control" rows="3" placeholder="Isi jika ada kerusakan peralatan atau property..."></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Pencemaran Lingkungan</label>
                <textarea class="form-control" rows="2" placeholder="Isi jika ada dampak lingkungan..."></textarea>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">D. Alat Pelindung Diri</p>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label>Korban memakai Alat Pelindung Diri</label>
                <div class="d-flex flex-wrap" style="gap:14px;">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="test_pelaporan_apd_ya" name="test_pelaporan_apd">
                    <label class="custom-control-label" for="test_pelaporan_apd_ya">Ya</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="test_pelaporan_apd_tidak" name="test_pelaporan_apd">
                    <label class="custom-control-label" for="test_pelaporan_apd_tidak">Tidak</label>
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Alasan tidak memakai</label>
                <input type="text" class="form-control" placeholder="Jelaskan alasan jika tidak memakai APD">
              </div>
            </div>
          </div>

          <div class="section-card mb-0">
            <div class="section-head">
              <p class="t">E. Catatan dan Persetujuan</p>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label>Catatan</label>
                <textarea class="form-control" rows="2" placeholder="Catatan tambahan"></textarea>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-md-0">
                    <label>Dilaporkan oleh P2K3 Unit</label>
                    <input type="text" class="form-control" placeholder="Nama pelapor">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-md-0">
                    <label>Koordinator</label>
                    <input type="text" class="form-control" placeholder="Nama koordinator">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-0">
                    <label>Mengetahui Kepala Divisi</label>
                    <input type="text" class="form-control" placeholder="Nama kepala divisi">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-danger">
            <i class="fas fa-eye mr-1"></i> Preview Tampilan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ==========================================
  MODAL TEST: LAPORAN INVESTIGASI KECELAKAAN
========================================== --}}
<div class="modal fade" id="modalTestInvestigasiKecelakaan" tabindex="-1" role="dialog" aria-labelledby="modalTestInvestigasiKecelakaanLabel" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTestInvestigasiKecelakaanLabel">
          <i class="fas fa-search-plus text-secondary mr-1"></i> Test Form: Laporan Investigasi Kecelakaan
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form>
        <div class="modal-body">
          <div class="alert alert-light border">
            <i class="fas fa-info-circle mr-1"></i>
            Modal ini sementara untuk test tampilan dari PDF investigasi. Belum dikaitkan ke proses simpan data.
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">Data Insiden</p>
              <p class="s">Klasifikasi awal, korban, waktu, dan unit kerja.</p>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label class="required">Jenis Insiden</label>
                <div class="d-flex flex-wrap" style="gap:14px;">
                  @foreach(['Near Miss', 'Gangguan Kesehatan', 'Kebakaran', 'Kecelakaan'] as $index => $jenis)
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="test_investigasi_jenis_{{ $index }}" name="test_investigasi_jenis_insiden">
                      <label class="custom-control-label" for="test_investigasi_jenis_{{ $index }}">{{ $jenis }}</label>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Nama & No. Payroll</label>
                    <input type="text" class="form-control" placeholder="Nama korban / no payroll">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Peralatan/Bahan</label>
                    <input type="text" class="form-control" placeholder="Peralatan atau bahan terkait">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Umur</label>
                    <input type="number" min="0" class="form-control" placeholder="Tahun">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" class="form-control">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Jam</label>
                    <input type="time" class="form-control">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Department</label>
                    <input type="text" class="form-control" placeholder="Department">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Section</label>
                    <input type="text" class="form-control" placeholder="Section">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Jabatan</label>
                    <input type="text" class="form-control" placeholder="Jabatan">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Total Lama Bekerja</label>
                    <input type="text" class="form-control" placeholder="Contoh: 2 tahun 3 bulan">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Shift</label>
                    <div class="d-flex flex-wrap" style="gap:14px;">
                      @foreach(['1st', '2nd', '3rd'] as $index => $shift)
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="test_investigasi_shift_{{ $index }}" name="test_investigasi_shift">
                          <label class="custom-control-label" for="test_investigasi_shift_{{ $index }}">{{ $shift }}</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-md-0">
                    <label>Kerja Lembur</label>
                    <div class="d-flex flex-wrap" style="gap:14px;">
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="test_investigasi_lembur_yes" name="test_investigasi_lembur">
                        <label class="custom-control-label" for="test_investigasi_lembur_yes">Yes</label>
                      </div>
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="test_investigasi_lembur_no" name="test_investigasi_lembur">
                        <label class="custom-control-label" for="test_investigasi_lembur_no">No</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group mb-0">
                    <label>Klasifikasi</label>
                    <div class="d-flex flex-wrap" style="gap:14px;">
                      @foreach(['P3K', 'MTI', 'LWD', 'Fatality'] as $index => $klasifikasi)
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="test_investigasi_klasifikasi_{{ $index }}" name="test_investigasi_klasifikasi">
                          <label class="custom-control-label" for="test_investigasi_klasifikasi_{{ $index }}">{{ $klasifikasi }}</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">Keterangan Kecelakaan dan Informasi Terkait</p>
              <p class="s">Gambaran kecelakaan, lokasi, pekerjaan korban, dan kejadian sebelumnya.</p>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label>Nama Saksi dan Rekan Kerja (No. Payroll)</label>
                <textarea class="form-control" rows="2" placeholder="Tuliskan saksi atau rekan kerja terkait..."></textarea>
              </div>

              <div class="form-group">
                <label>Penyebab Kecelakaan dan Bagian Tubuh yang Terluka / Kerusakan Peralatan</label>
                <textarea class="form-control" rows="3" placeholder="Jelaskan penyebab awal, bagian tubuh terluka, atau kerusakan..."></textarea>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Tempat</label>
                    <input type="text" class="form-control" placeholder="Tempat kejadian">
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Deskripsi Insiden</label>
                    <textarea class="form-control" rows="3" placeholder="Deskripsi insiden secara lengkap..."></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <label>Gambaran Kecelakaan</label>
                <textarea class="form-control" rows="4" placeholder="Lokasi kecelakaan terjadi, pekerjaan korban saat bekerja, langkah nyata, tugas atau bagian pekerjaan yang dilakukan, dan kejadian sebelumnya..."></textarea>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">Analisa Faktor Penyebab</p>
              <p class="s">Centang faktor yang sesuai dan lengkapi penyebab lainnya jika ada.</p>
            </div>
            <div class="card-body">
              @php
                $factorGroups = [
                  'Lingkungan Kerja' => [
                    'Ruang gerak yang terbatas/sempit',
                    'Housekeeping yang tidak memadai',
                    'Kondisi lingkungan yang berbahaya',
                    'Terpapar kebisingan tinggi',
                    'Terpapar radiasi',
                    'Suhu ekstrim',
                    'Penerangan kurang/berlebih',
                    'Ventilasi kurang memadai',
                    'Terpapar getaran yang berlebihan/lama',
                  ],
                  'Faktor Manusia' => [
                    'Perilaku/attitude yang kurang',
                    'Kurang istirahat/tidur',
                    'Kurang pengetahuan atau ketrampilan',
                    'Mengoperasikan alat yang bukan wewenangnya',
                    'Gagal mengamankan/tidak memasang LOTO',
                    'Memakai peralatan yang rusak',
                    'Tidak memakai/salah menggunakan APD',
                    'Posisi kerja yang tidak aman/tidak ergonomis',
                    'Cara pengangkatan yang tidak tepat',
                    'Menggunakan alat tidak benar/pemaksaan peralatan',
                    'Tidak melaksanakan prosedur/standard kerja dengan benar',
                    'Bercanda/bermain-main',
                  ],
                  'Faktor Peralatan' => [
                    'Pelindung pada alat atau alat peringatan tidak memadai',
                    'APD tidak memadai',
                    'Alat/material tidak memadai atau rusak',
                    'Desain perancangan tidak memadai/tidak ergonomis',
                    'Spesifikasi pembelian tidak memadai',
                    'Bahaya bahan mudah meledak/terbakar',
                    'Perkakas/peralatan/material tidak memadai',
                    'Aus dan rusak normal',
                    'Kerusakan akibat kecelakaan/abnormal',
                  ],
                  'Faktor Metode / Prosedur Kerja' => [
                    'Sistem peringatan tidak memadai',
                    'Belum ada standard/prosedur',
                    'Prosedur/standard tidak dapat diimplementasikan',
                    'Cara pemuatan/penyimpanan tidak aman',
                    'Pemeliharaan tidak memadai',
                    'Kurangnya pengawasan/supervisi',
                  ],
                ];
              @endphp

              @foreach($factorGroups as $groupTitle => $factors)
                <div class="mb-3">
                  <label class="d-block">{{ $groupTitle }}</label>
                  <div class="row">
                    @foreach($factors as $factorIndex => $factor)
                      @php $id = 'test_investigasi_factor_' . \Illuminate\Support\Str::slug($groupTitle) . '_' . $factorIndex; @endphp
                      <div class="col-md-6">
                        <div class="custom-control custom-checkbox mb-2">
                          <input class="custom-control-input" type="checkbox" id="{{ $id }}">
                          <label class="custom-control-label" for="{{ $id }}">{{ $factor }}</label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <input type="text" class="form-control" placeholder="Penyebab lain untuk {{ strtolower($groupTitle) }}">
                </div>
              @endforeach
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">Ringkasan Faktor Penyebab</p>
              <p class="s">Jelaskan masing-masing item yang diperiksa.</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-md-0">
                    <label>Unsafe Condition</label>
                    <textarea class="form-control" rows="4" placeholder="Ringkasan unsafe condition..."></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label>Unsafe Action</label>
                    <textarea class="form-control" rows="4" placeholder="Ringkasan unsafe action..."></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">Biaya Kerugian</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Peralatan / Material</label>
                    <input type="number" min="0" class="form-control" placeholder="0">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Pengobatan</label>
                    <input type="number" min="0" class="form-control" placeholder="0">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Hari Hilang / Down Time</label>
                    <input type="number" min="0" class="form-control" placeholder="0">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Total Biaya</label>
                    <input type="number" min="0" class="form-control" placeholder="0">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="section-card">
            <div class="section-head">
              <p class="t">Tindakan Perbaikan dan Pencegahan</p>
            </div>
            <div class="card-body">
              @for($i = 1; $i <= 3; $i++)
                <div class="border rounded p-3 mb-3">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Dept.</label>
                        <input type="text" class="form-control" placeholder="Dept">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Penanggung Jawab</label>
                        <input type="text" class="form-control" placeholder="Nama PIC">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Rencana Penyelesaian</label>
                        <input type="date" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-1">
                      <div class="form-group mb-0">
                        <label>Efektif</label>
                        <select class="form-control">
                          <option></option>
                          <option>Ya</option>
                          <option>Tidak</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group mb-0">
                    <label>Uraian Tindakan {{ $i }}</label>
                    <textarea class="form-control" rows="2" placeholder="Tuliskan tindakan perbaikan/pencegahan..."></textarea>
                  </div>
                </div>
              @endfor
            </div>
          </div>

          <div class="section-card mb-0">
            <div class="section-head">
              <p class="t">Pengesahan</p>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Disiapkan oleh</label>
                    <input type="text" class="form-control" placeholder="Nama">
                  </div>
                  <input type="text" class="form-control mb-2" placeholder="Jabatan">
                  <input type="text" class="form-control" placeholder="Departemen">
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Tim Penyelidik</label>
                    <textarea class="form-control" rows="4" placeholder="Nama tim penyelidik..."></textarea>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Perbaikan oleh Dept. Penanggung Jawab</label>
                    <input type="text" class="form-control" placeholder="Nama">
                  </div>
                  <input type="date" class="form-control" placeholder="Tanggal">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-secondary">
            <i class="fas fa-eye mr-1"></i> Preview Tampilan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
