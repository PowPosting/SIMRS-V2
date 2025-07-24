<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="car        .then(data => {
            if (data.success) {
                // Hide modal
                $('#modalDeletePasien').modal('hide');
                
                // Show success message
                alert('Data pasien berhasil dihapus!');
                
                // Reload page
                window.location.reload();
            } else {
                alert('Gagal menghapus data: ' + data.message);
                
                // Reset button
                document.getElementById('btnConfirmDelete').innerHTML = '<i class="fas fa-trash mr-2"></i>Ya, Hapus Data';
                document.getElementById('btnConfirmDelete').disabled = false;    <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title m-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        <span style="font-size: 1.25rem; font-weight: 600;">Data Pasien</span>
                    </h3>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama, no. rekam medis..." style="height: 40px; border-radius: 8px 0 0 8px;">
                            <button class="btn btn-primary px-3" type="button" id="searchButton" style="border-radius: 0 8px 8px 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="<?= base_url('admisi/registrasipasien') ?>" class="btn btn-success d-flex align-items-center gap-2" style="height: 40px;">
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
                                            <div class="d-flex justify-content-center gap-2">
                                                <div class="d-flex flex-column align-items-center">
                                                    <button type="button" class="btn btn-sm btn-soft-info px-2 py-2" onclick="lihatDetail(<?= $p['id'] ?>)" title="Lihat Detail">
                                                        <i class="fas fa-eye" style="color: #2196F3;"></i>
                                                    </button>
                                                    <small class="text-info" style="font-size:11px;">Lihat</small>
                                                </div>
                                                <div class="d-flex flex-column align-items-center">
                                                    <button type="button" class="btn btn-sm btn-soft-warning px-2 py-2" onclick="editPasien(<?= $p['id'] ?>)" title="Edit Data">
                                                        <i class="fas fa-edit" style="color: #FF9800;"></i>
                                                    </button>
                                                    <small class="text-warning" style="font-size:11px;">Edit</small>
                                                </div>
                                                <div class="d-flex flex-column align-items-center">
                                                    <button type="button" class="btn btn-sm btn-soft-danger px-2 py-2" onclick="hapusPasien(<?= $p['id'] ?>, '<?= esc($p['nama_lengkap']) ?>')" title="Hapus Data">
                                                        <i class="fas fa-trash" style="color: #E91E63;"></i>
                                                    </button>
                                                    <small class="text-danger" style="font-size:11px;">Hapus</small>
                                                </div>
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
        // Show loading
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
        this.disabled = true;
        
        // Send delete request
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
                // Hide modal
                $('#modalDeletePasien').modal('hide');
                
                // Show success message
                alert('Data pasien berhasil dihapus!');
                
                // Reload page
                window.location.reload();
            } else {
                alert('Gagal menghapus data: ' + data.message);
                
                // Reset button
                document.getElementById('btnConfirmDelete').innerHTML = '<i class="fas fa-trash me-2"></i>Ya, Hapus Data';
                document.getElementById('btnConfirmDelete').disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
            
            // Reset button
            document.getElementById('btnConfirmDelete').innerHTML = '<i class="fas fa-trash mr-2"></i>Ya, Hapus Data';
            document.getElementById('btnConfirmDelete').disabled = false;
        });
    }
});

// Fungsi pencarian
function searchTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const tableRows = document.querySelectorAll('table tbody tr');
    let visibleCount = 0;

    tableRows.forEach(row => {
        // Baris "Belum ada data pasien" (hanya 1 kolom, colspan=5)
        if (row.querySelectorAll('td').length === 1) {
            row.style.display = 'none';
            return;
        }
        let text = '';
        // Kolom nama dan no rekam medis
        const nameCell = row.querySelector('td:nth-child(2)');
        if (nameCell) {
            const name = nameCell.querySelector('.fw-semibold')?.textContent || '';
            const medicalRecord = nameCell.querySelector('.text-muted')?.textContent || '';
            text = name + ' ' + medicalRecord;
        }
        // Kolom TTL
        const ttlCell = row.querySelector('td:nth-child(3)');
        if (ttlCell) {
            text += ' ' + ttlCell.textContent;
        }
        // Kolom No HP
        const phoneCell = row.querySelector('td:nth-child(4)');
        if (phoneCell) {
            text += ' ' + phoneCell.textContent;
        }
        text = text.toLowerCase();
        if (text.includes(searchValue)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    // Tampilkan baris "Belum ada data pasien" jika tidak ada hasil
    if (visibleCount === 0) {
        tableRows.forEach(row => {
            if (row.querySelectorAll('td').length === 1) {
                row.style.display = '';
            }
        });
    }
}

// Event listeners untuk pencarian
document.getElementById('searchInput').addEventListener('keyup', searchTable);
document.getElementById('searchButton').addEventListener('click', searchTable);

// Inisialisasi tooltip
const tooltips = document.querySelectorAll('[title]');
tooltips.forEach(tooltip => {
    new bootstrap.Tooltip(tooltip);
});
</script>

<?= $this->endSection() ?>
