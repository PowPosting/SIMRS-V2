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
                                                    <?php if (isset($p['jenis_kelamin']) && strtoupper($p['jenis_kelamin']) == 'L'): ?>
                                                        <i class="fas fa-mars" style="color:#1781FF;"></i>
                                                    <?php elseif (isset($p['jenis_kelamin']) && strtoupper($p['jenis_kelamin']) == 'P'): ?>
                                                        <i class="fas fa-venus" style="color:#e61e1e;"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-user text-primary"></i>
                                                    <?php endif; ?>
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
// Fitur pencarian tabel pasien
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.querySelector('input[name="table_search"]');
    var searchBtn = document.querySelector('.input-group button[type="submit"]');
    var table = document.querySelector('table.table');
    if (!searchInput || !table) return;

    function filterTable() {
        var keyword = searchInput.value.toLowerCase();
        var rows = table.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            var nama = row.querySelector('td:nth-child(2) .fw-semibold');
            var noRM = row.querySelector('td:nth-child(2) small');
            var text = '';
            if (nama) text += nama.textContent.toLowerCase();
            if (noRM) text += ' ' + noRM.textContent.toLowerCase();
            if (text.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filterTable();
        });
    }
});
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
    //
    // Optional: tampilkan loading di modal
    $('#modalDetailPasienBaru').modal('show');
    // Kosongkan dulu isi modal
    isiModalDetailPasien({});
    // Ambil data pasien via AJAX
    var url = '<?= base_url('admisi/get-detail-pasien') ?>/' + pasienId;
    console.log('[DEBUG] Fetch URL:', url);
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('[DEBUG] Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('[DEBUG] Data diterima:', data);
        if (data.pasien) {
            // Gabungkan data pasien utama dengan alamat, kontak, info medis, info tambahan (ambil index 0 jika ada)
            var p = data.pasien || {};
            if (Array.isArray(data.alamat) && data.alamat.length > 0) {
                Object.assign(p, data.alamat[0]);
            }
            if (Array.isArray(data.kontak) && data.kontak.length > 0) {
                Object.assign(p, data.kontak[0]);
            }
            if (Array.isArray(data.info_medis) && data.info_medis.length > 0) {
                Object.assign(p, data.info_medis[0]);
            }
            if (Array.isArray(data.info_tambahan) && data.info_tambahan.length > 0) {
                Object.assign(p, data.info_tambahan[0]);
            }
            isiModalDetailPasien(p);
        } else {
            // alert('[DEBUG] Data pasien tidak ditemukan! Response: ' + JSON.stringify(data));
        }
    })
    .catch(error => {
        console.error('[DEBUG] Error fetch:', error);
        alert('[DEBUG] Gagal mengambil data pasien! Error: ' + error);
    });
}

// Fungsi untuk mengisi data modal detail pasien
function isiModalDetailPasien(p) {
    p = p || {};
    // Debug: tampilkan seluruh objek hasil response backend ke console browser
    console.log('Detail pasien response:', p);
    // Log khusus info medis & tambahan
    console.log('Info Medis:', p.golongan_darah);
    console.log('Info Tambahan:', {
        agama: p.agama,
        pendidikan_terakhir: p.pendidikan_terakhir,
        pekerjaan: p.pekerjaan,
        kewarganegaraan: p.kewarganegaraan,
        suku: p.suku
    });

    // Helper format tanggal (YYYY-MM-DD ke DD-MM-YYYY)
    function formatTanggal(tgl) {
        if (!tgl) return '-';
        var arr = tgl.split('-');
        if (arr.length === 3) return arr[2] + '-' + arr[1] + '-' + arr[0];
        return tgl;
    }
    // Set foto identitas (panggil fungsi di modal)
    if (typeof setFotoIdentitasModal === 'function') {
        setFotoIdentitasModal(p.foto_identitas || '');
    }
    // Header
    function safeSet(id, value) {
        var el = document.getElementById(id);
        if (el) {
            el.textContent = value;
        } else {
            console.warn('[DEBUG] Element id not found:', id);
        }
    }
    safeSet('detail_no_rm', p.no_rekam_medis || '-');
    safeSet('detail_nama', p.nama_lengkap || '-');
    // Identitas Pasien
    safeSet('detail_no_rm2', p.no_rekam_medis || '-');
    safeSet('detail_no_identitas', p.nomor_identitas || '-');
    safeSet('detail_status_aktif', p.status_aktif || '-');
    safeSet('detail_nama2', p.nama_lengkap || '-');
    safeSet('detail_title', p.title || '-');
    safeSet('detail_jk2', p.jenis_kelamin || '-');
    safeSet('detail_ttl', (p.tempat_lahir ? p.tempat_lahir + ', ' : '') + formatTanggal(p.tanggal_lahir));
    safeSet('detail_status_perkawinan', p.status_perkawinan || '-');
    // Alamat Pasien
    safeSet('detail_alamat', p.alamat_lengkap || '-');
    safeSet('detail_kelurahan', p.kelurahan || '-');
    safeSet('detail_kecamatan', p.kecamatan || '-');
    safeSet('detail_kabupaten', p.kabupaten_kota || '-');
    safeSet('detail_provinsi', p.provinsi || '-');
    safeSet('detail_kode_pos', p.kode_pos || '-');
    // Kontak Darurat
    safeSet('detail_nama_kontak_darurat', p.nama_kontak || '-');
    safeSet('detail_hubungan_kontak_darurat', p.hubungan || '-');
    safeSet('detail_nomor_hp_kontak_darurat', p.nomor_hp || '-');
    safeSet('detail_alamat_kontak_darurat', p.alamat || '-');
    // Informasi Medis
    safeSet('detail_golongan_darah', p.golongan_darah || '-');
    // Informasi Tambahan
    safeSet('detail_agama', p.agama || '-');
    safeSet('detail_pendidikan_terakhir', p.pendidikan_terakhir || '-');
    safeSet('detail_pekerjaan', p.pekerjaan || '-');
    safeSet('detail_kewarganegaraan', p.kewarganegaraan || '-');
    safeSet('detail_suku', p.suku || '-');
}

</script>

<?= $this->endSection() ?>
