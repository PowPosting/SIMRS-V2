<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title m-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        <span style="font-size: 1.25rem; font-weight: 600;">Data Pasien</span>
                    </h3>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" name="table_search" class="form-control" placeholder="Cari nama, no. rekam medis..." style="height: 40px; border-radius: 8px 0 0 8px;">
                            <button class="btn btn-primary px-3" type="submit" style="border-radius: 0 8px 8px 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="<?= base_url('admisi/registrasi-pasien') ?>" class="btn btn-success d-flex align-items-center gap-2" style="height: 40px;">
                            <i class="fas fa-plus"></i>
                            Tambah Pasien
                        </a>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <table class="table table-hover align-middle custom-table">
                        <thead>
                            <tr>
                                <th class="text-center py-3" style="width: 60px; background: var(--pastel-blue);">No</th>
                                <th class="py-3" style="min-width: 250px; background: var(--pastel-blue);">Nama Lengkap</th>
                                <th class="py-3" style="min-width: 200px; background: var(--pastel-blue);">TTL</th>
                                <th class="py-3" style="min-width: 150px; background: var(--pastel-blue);">No HP</th>
                                <th class="text-center py-3" style="width: 150px; background: var(--pastel-blue);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($pasien) && !empty($pasien)): ?>
                                <?php $no = 1; foreach ($pasien as $p): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="icon-bg-primary rounded-circle p-2 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= esc($p['nama_lengkap']) ?></div>
                                                    <small class="text-muted"><?= esc($p['no_rekam_medis'] ?? '-') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div><?= esc($p['tempat_lahir']) ?></div>
                                            <small class="text-muted"><?= tanggal_indonesia($p['tanggal_lahir']) ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-phone text-success"></i>
                                                <?= esc($p['nomor_hp']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <button type="button" class="btn btn-sm btn-soft-info px-2 py-2" onclick="lihatDetail(<?= $p['id'] ?>)" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-soft-warning px-2 py-2" onclick="editPasien(<?= $p['id'] ?>)" title="Edit Data">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-soft-danger px-2 py-2" onclick="hapusPasien(<?= $p['id'] ?>, '<?= esc($p['nama_lengkap']) ?>')" title="Hapus Data">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                            <h6 class="text-muted">Belum ada data pasien</h6>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Modal Detail Pasien -->
<?= $this->include('layouts/modals/modal_detail_pasien_new') ?>

<!-- Modal Konfirmasi Delete -->
<div class="modal" id="modalDeletePasien" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Konfirmasi Hapus Data
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-user-times fa-4x text-danger mb-3"></i>
                <h5 class="mb-3">Apakah Anda yakin ingin menghapus data pasien ini?</h5>
                <p class="mb-1">
                    <strong>Nama Pasien:</strong> 
                    <span id="delete_nama_pasien" class="text-danger">-</span>
                </p>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Perhatian: Data yang sudah dihapus tidak dapat dikembalikan!
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">
                    <i class="fas fa-trash mr-2"></i>Ya, Hapus Data
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var deletePasienId = null;

function editPasien(pasienId) {
    // Redirect ke halaman edit
    window.location.href = '<?= base_url('admisi/edit-pasien') ?>/' + pasienId;
}

function hapusPasien(pasienId, namaPasien) {
    deletePasienId = pasienId;
    
    // Set nama pasien di modal
    document.getElementById('delete_nama_pasien').textContent = namaPasien;
    
    // Show modal konfirmasi
    $('#modalDeletePasien').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}

// Event listener untuk tombol konfirmasi delete
document.getElementById('btnConfirmDelete').addEventListener('click', function() {
    if (deletePasienId) {
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
        this.disabled = true;
        fetch('<?= base_url('admisi/delete-pasien') ?>/' + deletePasienId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#modalDeletePasien').modal('hide');
                alert('Data pasien berhasil dihapus!');
                window.location.reload();
            } else {
                alert('Gagal menghapus data: ' + data.message);
                document.getElementById('btnConfirmDelete').innerHTML = '<i class="fas fa-trash me-2"></i>Ya, Hapus Data';
                document.getElementById('btnConfirmDelete').disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
            document.getElementById('btnConfirmDelete').innerHTML = '<i class="fas fa-trash me-2"></i>Ya, Hapus Data';
            document.getElementById('btnConfirmDelete').disabled = false;
        });
    }
});

// Fungsi untuk menampilkan modal detail pasien
function lihatDetail(pasienId) {
    // Optional: tampilkan loading di modal
    $('#modalDetailPasienBaru').modal('show');
    // Kosongkan dulu isi modal
    isiModalDetailPasien({});
    // Ambil data pasien via AJAX
    fetch('<?= base_url('shared/get-detail-pasien') ?>/' + pasienId, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            isiModalDetailPasien(data.data);
        } else {
            alert('Data pasien tidak ditemukan!');
        }
    })
    .catch(error => {
        alert('Gagal mengambil data pasien!');
    });
}

// Fungsi untuk mengisi data modal detail pasien
function isiModalDetailPasien(p) {
    p = p || {};
    // Header
    document.getElementById('detail_no_rm').textContent = p.no_rekam_medis || '-';
    document.getElementById('detail_nama').textContent = p.nama_lengkap || '-';

    // Identitas Pasien
    document.getElementById('detail_no_rm2').textContent = p.no_rekam_medis || '-';
    document.getElementById('detail_no_identitas').textContent = p.nomor_identitas || '-';
    document.getElementById('detail_status_aktif').textContent = p.status_aktif_text || '-';
    document.getElementById('detail_nama2').textContent = p.nama_lengkap || '-';
    document.getElementById('detail_title').textContent = p.title || '-';
    document.getElementById('detail_jk2').textContent = p.jenis_kelamin_text || '-';
    document.getElementById('detail_ttl').textContent = (p.tempat_lahir ? p.tempat_lahir + ', ' : '') + (p.tanggal_lahir_formatted || '-');
    document.getElementById('detail_umur').textContent = (p.umur ? p.umur.tahun + ' Tahun ' + (p.umur.bulan || 0) + ' Bulan ' + (p.umur.hari || 0) + ' Hari' : '-');
    document.getElementById('detail_status_perkawinan').textContent = p.status_perkawinan || '-';

    // Alamat Pasien
    document.getElementById('detail_alamat').textContent = p.alamat_lengkap || '-';
    document.getElementById('detail_kelurahan').textContent = p.kelurahan || '-';
    document.getElementById('detail_kecamatan').textContent = p.kecamatan || '-';
    document.getElementById('detail_kabupaten').textContent = p.kabupaten_kota || '-';
    document.getElementById('detail_provinsi').textContent = p.provinsi || '-';
    document.getElementById('detail_kode_pos').textContent = p.kode_pos || '-';

    // Kontak Darurat
    document.getElementById('detail_nama_kontak_darurat').textContent = p.nama_kontak_darurat || '-';
    document.getElementById('detail_hubungan_kontak_darurat').textContent = p.hubungan_kontak_darurat || '-';
    document.getElementById('detail_nomor_hp_kontak_darurat').textContent = p.nomor_hp_kontak_darurat || '-';
    document.getElementById('detail_alamat_kontak_darurat').textContent = p.alamat_kontak_darurat || '-';

    // Informasi Medis
    document.getElementById('detail_golongan_darah').textContent = p.golongan_darah || '-';

    // Informasi Tambahan
    document.getElementById('detail_agama').textContent = p.agama || '-';
    document.getElementById('detail_pendidikan_terakhir').textContent = p.pendidikan_terakhir || '-';
    document.getElementById('detail_pekerjaan').textContent = p.pekerjaan || '-';
    document.getElementById('detail_kewarganegaraan').textContent = p.kewarganegaraan || '-';
    document.getElementById('detail_suku').textContent = p.suku || '-';
    document.getElementById('detail_bahasa').textContent = p.bahasa || '-';
}
}
</script>

<?= $this->endSection() ?>
