<div class="modal fade" id="modalDetailPasienBaru" tabindex="-1" role="dialog" aria-labelledby="modalDetailPasienBaruLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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
          </div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-0">
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

</script>

