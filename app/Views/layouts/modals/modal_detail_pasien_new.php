<!-- Modal Notifikasi Error Daftar Ulang -->
<div class="modal fade" id="modalDaftarUlangError" tabindex="-1" role="dialog" aria-labelledby="modalDaftarUlangErrorLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalDaftarUlangErrorLabel"><i class="fa fa-times-circle mr-2"></i>Gagal Daftar Ulang</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <div class="mb-3">
          <span class="h5" id="pesanDaftarUlangError">Terjadi kesalahan.</span>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalDetailPasienBaru" tabindex="-1" role="dialog" aria-labelledby="modalDetailPasienBaruLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width:1100px;">
    <div class="modal-content">
      <div class="modal-header bg-white border-0 pb-0">
        <div class="d-flex align-items-center w-100">
          <div class="mr-3">
            <span class="badge badge-pill badge-info p-1" id="detail_no_rm" style="font-size:1.3em; letter-spacing:2px;">-</span>
          </div>
          <div class="flex-grow-1 d-flex align-items-center">
            <i class="fa-solid fa-id-card text-primary">  </i>
            <h4 class="mb-0 mr-3" id="detail_nama">-</h4>
            <button id="btnLihatDokumenIdentitas" class="btn btn-sm btn-outline-primary ml-2" style="white-space:nowrap;" type="button">
              <i class="fas fa-file-alt"></i> Dokumen Identitas
            </button>
            <button id="btnExportWordDetailPasien" class="btn btn-sm btn-outline-primary ml-2" type="button">
              <i class="fas fa-file-word"></i> Export Word
            </button>
            <button id="btnDaftarUlangPasien" class="btn btn-sm btn-success ml-2" type="button">
              <i class="fa fa-plus-circle"></i> Daftarkan Kembali
            </button>
          </div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalDaftarUlangSuksesLabel"><i class="fa fa-check-circle mr-2"></i>Pendaftaran Ulang Berhasil</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <div class="mb-3">
          <span class="h4">Nomor Antrian:</span><br>
          <span class="display-4 font-weight-bold text-success" id="noAntrianDaftarUlang">-</span>
        </div>
        <button class="btn btn-primary" id="btnExportWordAntrian"><i class="fa fa-print mr-1"></i> Cetak Antrian</button>
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
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPilihPoliLabel">Pilih Poli Tujuan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select class="form-control" id="selectPoliDaftarUlang">
          <option value=""> Pilih Poli </option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnKonfirmasiPilihPoli">Daftarkan</button>
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
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalKonfirmasiDaftarUlangLabel"><i class="fa fa-question-circle mr-2"></i>Konfirmasi Daftar Ulang</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                Daftarkan kembali pasien ini ke pendaftaran hari ini di poli terpilih?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnKonfirmasiDaftarUlangOK">OK</button>
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

