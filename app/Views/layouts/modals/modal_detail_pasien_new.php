<!-- Modal Notifikasi Error Daftar Ulang -->
<div class="modal fade" id="modalDaftarUlangError" tabindex="-1" role="dialog" aria-labelledby="modalDaftarUlangErrorLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white border-0">
        <h5 class="modal-title font-weight-bold" id="modalDaftarUlangErrorLabel">
          <i class="fas fa-exclamation-circle mr-2"></i>Tidak Dapat Mendaftar
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-3">
          <i class="fas fa-times-circle text-danger" style="font-size: 4rem; opacity: 0.8;"></i>
        </div>
        <div class="alert alert-light border-danger mb-0" role="alert">
          <p class="mb-0 text-dark" id="pesanDaftarUlangError" style="line-height: 1.6;">Terjadi kesalahan.</p>
        </div>
      </div>
      <div class="modal-footer border-0 bg-light">
        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalDetailPasienBaru" tabindex="-1" role="dialog" aria-labelledby="modalDetailPasienBaruLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width:1100px;">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary text-white border-0">
        <div class="d-flex flex-column w-100">
          <!-- Baris 1: No RM dan Nama -->
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-white text-primary px-3 py-2 mr-3" id="detail_no_rm" style="font-size:1.1em; letter-spacing:1px; font-weight:600;">-</span>
            <h4 class="mb-0 text-white font-weight-bold" id="detail_nama">-</h4>
          </div>
          <!-- Baris 2: Action Buttons -->
          <div class="d-flex align-items-center">
            <button id="btnLihatDokumenIdentitas" class="btn btn-sm btn-light mr-2" type="button">
              <i class="fas fa-file-alt"></i> Dokumen Identitas
            </button>
            <button id="btnExportWordDetailPasien" class="btn btn-sm btn-light mr-2" type="button">
              <i class="fas fa-file-word"></i> Export Word
            </button>
            <button id="btnDaftarUlangPasien" class="btn btn-sm btn-success" type="button">
              <i class="fa fa-plus-circle"></i> Daftarkan Kembali
            </button>
          </div>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="position:absolute; right:15px; top:15px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-0" style="max-height:70vh; overflow-y:scroll; padding-right:12px;">
        <!-- SECTION: Identitas Pasien -->
        <div class="mb-4">
          <h5 class="border-bottom pb-2 mb-3 font-weight-bold text-primary"><i class="fa-solid fa-user"></i>  Identitas Pasien</h5>
          <div class="row g-2">
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">No. RM</span><br>
              <span class="fw-semibold" id="detail_no_rm2">-</span>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">Nomor Identitas</span><br>
              <span id="detail_no_identitas">-</span>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">Status Aktif</span><br>
              <span id="detail_status_aktif">-</span>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">Nama Lengkap</span><br>
              <span id="detail_nama2">-</span>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">Gelar</span><br>
              <span id="detail_title">-</span>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">Jenis Kelamin</span><br>
              <span id="detail_jk2">-</span>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">Tempat, Tanggal Lahir</span><br>
              <span id="detail_ttl">-</span>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">Status Perkawinan</span><br>
              <span id="detail_status_perkawinan">-</span>
            </div>
          </div>
        </div>

        <!-- SECTION: Alamat Pasien -->
        <div class="mb-4">
          <h5 class="border-bottom pb-2 mb-3 font-weight-bold text-primary"><i class="fa-solid fa-house-user"></i> Alamat Pasien</h5>
          <div class="row g-2">
            <div class="col-md-4 col-12 mb-2">
              <span class="text-muted small">Alamat Lengkap</span><br>
              <span id="detail_alamat">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Kelurahan</span><br>
              <span id="detail_kelurahan">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Kecamatan</span><br>
              <span id="detail_kecamatan">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Kabupaten/Kota</span><br>
              <span id="detail_kabupaten">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Provinsi</span><br>
              <span id="detail_provinsi">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Kode Pos</span><br>
              <span id="detail_kode_pos">-</span>
            </div>
          </div>
        </div>

        <!-- SECTION: Kontak Darurat -->
        <div class="mb-4">
          <h5 class="border-bottom pb-2 mb-3 font-weight-bold text-primary"><i class="fa-solid fa-address-book"></i> Kontak Darurat</h5>
          <div class="row g-2">
            <div class="col-md-4 col-12 mb-2">
              <span class="text-muted small">Nama Kontak</span><br>
              <span id="detail_nama_kontak_darurat">-</span>
            </div>
            <div class="col-md-4 col-6 mb-2">
              <span class="text-muted small">Hubungan</span><br>
              <span id="detail_hubungan_kontak_darurat">-</span>
            </div>
            <div class="col-md-4 col-6 mb-2">
              <span class="text-muted small">No HP Kontak</span><br>
              <span id="detail_nomor_hp_kontak_darurat">-</span>
            </div>
            <div class="col-md-12 col-12 mb-2">
              <span class="text-muted small">Alamat Kontak</span><br>
              <span id="detail_alamat_kontak_darurat">-</span>
            </div>
          </div>
        </div>

        <!-- SECTION: Informasi Medis -->
        <div class="mb-4">
          <h5 class="border-bottom pb-2 mb-3 font-weight-bold text-primary"><i class="fa-solid fa-notes-medical"></i> Informasi Medis</h5>
          <div class="row g-2">
            <div class="col-md-3 col-6 mb-2">
              <span class="text-muted small">Golongan Darah</span><br>
              <span id="detail_golongan_darah">-</span>
            </div>
          </div>
        </div>

        <!-- SECTION: Informasi Tambahan -->
        <div class="mb-2">
          <h5 class="border-bottom pb-2 mb-3 font-weight-bold text-primary"><i class="fa-solid fa-circle-info"></i> Informasi Tambahan</h5>
          <div class="row g-2">
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Agama</span><br>
              <span id="detail_agama">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Pendidikan</span><br>
              <span id="detail_pendidikan_terakhir">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Pekerjaan</span><br>
              <span id="detail_pekerjaan">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Kewarganegaraan</span><br>
              <span id="detail_kewarganegaraan">-</span>
            </div>
            <div class="col-md-2 col-6 mb-2">
              <span class="text-muted small">Suku</span><br>
              <span id="detail_suku">-</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Notifikasi Daftar Ulang Berhasil -->
<div class="modal fade" id="modalDaftarUlangSukses" tabindex="-1" role="dialog" aria-labelledby="modalDaftarUlangSuksesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title font-weight-bold" id="modalDaftarUlangSuksesLabel">
          <i class="fas fa-check-circle mr-2"></i>Pendaftaran Ulang Berhasil
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center py-4">
        <div class="mb-3">
          <i class="fas fa-check-circle text-success" style="font-size: 4rem; opacity: 0.8;"></i>
        </div>
        <p class="text-muted mb-2" style="font-size: 1.1rem;">Nomor Antrian:</p>
        <h1 class="display-3 font-weight-bold text-success mb-4" id="noAntrianDaftarUlang" style="letter-spacing: 2px;">-</h1>
        <button class="btn btn-primary btn-lg px-5" id="btnExportWordAntrian">
          <i class="fas fa-print mr-2"></i> Cetak Antrian
        </button>
      </div>
      <div class="modal-footer border-0 bg-light justify-content-center">
        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>
<script>
function setFotoIdentitasModal(foto_identitas) {
    var btn = document.getElementById('btnLihatDokumenIdentitas');
    if (btn) {
      if (foto_identitas && foto_identitas !== '-') {
          btn.disabled = false;
          btn.onclick = function() {
              var ext = foto_identitas.split('.').pop().toLowerCase();
              var url = '<?= base_url('uploads/') ?>' + foto_identitas;
              window.open(url, '_blank');
          };
      } else {
          btn.disabled = false;
          btn.onclick = function() {
              alert('Dokumen identitas belum diupload.');
          };
      }
    }
    var btnPrint = document.getElementById('btnPrintDetailPasien');
    if (btnPrint) {
      btnPrint.onclick = function() {
        window.print();
      };
    }
}
// Export modal content as Word (docx)
document.getElementById('btnExportWordDetailPasien').onclick = function() {
    var noRM = document.getElementById('detail_no_rm').textContent.trim();
    if (!noRM || noRM === '-') {
        alert('No. RM tidak ditemukan!');
        return;
    }
    // Ganti URL sesuai route CodeIgniter Anda jika perlu
    var url = '/exportword/pasien/' + encodeURIComponent(noRM);
    window.open(url, '_blank');
};


// Modal pilih poli
var modalPilihPoli = document.createElement('div');
modalPilihPoli.innerHTML = `
<div class="modal fade" id="modalPilihPoliDaftarUlang" tabindex="-1" role="dialog" aria-labelledby="modalPilihPoliLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white border-0">
        <h5 class="modal-title font-weight-bold" id="modalPilihPoliLabel">
          <i class="fas fa-hospital mr-2"></i>Pilih Poli Tujuan
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body py-4">
        <label class="font-weight-semibold mb-2">Poliklinik Tujuan:</label>
        <select class="form-control form-control-lg" id="selectPoliDaftarUlang" style="border-radius: 8px;">
          <option value="">-- Pilih Poli --</option>
        </select>
      </div>
      <div class="modal-footer border-0 bg-light">
        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Batal
        </button>
        <button type="button" class="btn btn-primary px-4" id="btnKonfirmasiPilihPoli">
          <i class="fas fa-check mr-1"></i> Daftarkan
        </button>
      </div>
    </div>
  </div>
</div>
`;
document.body.appendChild(modalPilihPoli);

// Ambil data poli dari backend (AJAX)
function loadPoliOptionsDaftarUlang() {
    fetch('/admisi/get-poli-list', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            var select = document.getElementById('selectPoliDaftarUlang');
            select.innerHTML = '<option value="">-- Pilih Poli --</option>';
            if (data.success && Array.isArray(data.poli)) {
                data.poli.forEach(function(poli) {
                    var opt = document.createElement('option');
                    opt.value = poli.id;
                    opt.textContent = poli.nama + (poli.kode ? ' ('+poli.kode+')' : '');
                    select.appendChild(opt);
                });
            }
        });
}

document.getElementById('btnDaftarUlangPasien').onclick = function() {
    var noRM = document.getElementById('detail_no_rm').textContent.trim();
    if (!noRM || noRM === '-') {
        document.getElementById('pesanDaftarUlangError').textContent = 'No. RM tidak ditemukan!';
        $('#modalDaftarUlangError').modal('show');
        return;
    }
    loadPoliOptionsDaftarUlang();
    $('#modalPilihPoliDaftarUlang').modal('show');
    document.getElementById('btnKonfirmasiPilihPoli').onclick = function() {
        var idPoli = document.getElementById('selectPoliDaftarUlang').value;
        if (!idPoli) {
            document.getElementById('pesanDaftarUlangError').textContent = 'Silakan pilih poli tujuan!';
            $('#modalDaftarUlangError').modal('show');
            return;
        }
        // Konfirmasi dengan modal Bootstrap custom
        if (window.confirmDaftarUlangModal) {
            window.confirmDaftarUlangModal.remove();
        }
        var confirmModal = document.createElement('div');
        confirmModal.innerHTML = `
        <div class="modal fade" id="modalKonfirmasiDaftarUlang" tabindex="-1" role="dialog" aria-labelledby="modalKonfirmasiDaftarUlangLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
              <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title font-weight-bold" id="modalKonfirmasiDaftarUlangLabel">
                  <i class="fas fa-question-circle mr-2"></i>Konfirmasi Daftar Ulang
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center py-4">
                <div class="mb-3">
                  <i class="fas fa-user-check text-primary" style="font-size: 3rem; opacity: 0.8;"></i>
                </div>
                <p class="mb-0" style="font-size: 1.05rem; line-height: 1.6;">
                  Daftarkan kembali pasien ini ke pendaftaran hari ini di poli terpilih?
                </p>
              </div>
              <div class="modal-footer border-0 bg-light justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                  <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="button" class="btn btn-primary px-4" id="btnKonfirmasiDaftarUlangOK">
                  <i class="fas fa-check mr-1"></i> Ya, Daftarkan
                </button>
              </div>
            </div>
          </div>
        </div>
        `;
        document.body.appendChild(confirmModal);
        window.confirmDaftarUlangModal = confirmModal;
        $('#modalKonfirmasiDaftarUlang').modal('show');
        document.getElementById('btnKonfirmasiDaftarUlangOK').onclick = function() {
            $('#modalKonfirmasiDaftarUlang').modal('hide');
            setTimeout(function() {
                if (window.confirmDaftarUlangModal) {
                    window.confirmDaftarUlangModal.remove();
                    window.confirmDaftarUlangModal = null;
                }
            }, 500);
            fetch('/admisi/daftar-ulang-pasien', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ no_rm: noRM, id_poli: idPoli })
            })
            .then(res => res.json())
            .then(data => {
                $('#modalPilihPoliDaftarUlang').modal('hide');
                if (data.success) {
                    document.getElementById('noAntrianDaftarUlang').textContent = data.no_antrian;
                    $('#modalDaftarUlangSukses').modal('show');
                    document.getElementById('btnExportWordAntrian').onclick = function() {
                        window.open('<?= base_url('admisi/print-antrian/') ?>' + encodeURIComponent(data.no_antrian), '_blank');
                    };
                } else {
                    document.getElementById('pesanDaftarUlangError').textContent = 'Gagal daftar ulang: ' + (data.message || 'Terjadi kesalahan.');
                    $('#modalDaftarUlangError').modal('show');
                }
            })
            .catch(() => {
                document.getElementById('pesanDaftarUlangError').textContent = 'Gagal menghubungi server.';
                $('#modalDaftarUlangError').modal('show');
            });
        };
    };
};
</script>

