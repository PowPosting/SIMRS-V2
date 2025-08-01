<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4" style="max-width:1200px;">
    <?php
    // Hitung statistik obat
    $totalObat = is_array($list_obat) ? count($list_obat) : 0;
    $stokHabis = 0;
    $stokMinimum = 0;
    $obatAktif = 0;
    if (!empty($list_obat) && is_array($list_obat)) {
        foreach ($list_obat as $obat) {
            if (($obat['stok'] ?? 0) <= 0) $stokHabis++;
            if (isset($obat['stok'], $obat['stok_minimal']) && $obat['stok'] > 0 && $obat['stok'] <= $obat['stok_minimal']) $stokMinimum++;
            if (($obat['status'] ?? '') === 'Aktif') $obatAktif++;
        }
    }
    ?>
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-2"><i class="bi bi-capsule text-primary" style="font-size:2rem;"></i></div>
                    <div class="fw-bold" style="font-size:1.5rem; color:#007bff;"><?= $totalObat ?></div>
                    <div class="text-muted small">Total Obat</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-2"><i class="bi bi-exclamation-triangle text-danger" style="font-size:2rem;"></i></div>
                    <div class="fw-bold" style="font-size:1.5rem; color:#dc3545;"><?= $stokHabis ?></div>
                    <div class="text-muted small">Stok Habis</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-2"><i class="bi bi-arrow-down-circle text-warning" style="font-size:2rem;"></i></div>
                    <div class="fw-bold" style="font-size:1.5rem; color:#ffc107;"><?= $stokMinimum ?></div>
                    <div class="text-muted small">Stok Minimum</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-2"><i class="bi bi-check-circle text-success" style="font-size:2rem;"></i></div>
                    <div class="fw-bold" style="font-size:1.5rem; color:#28a745;"><?= $obatAktif ?></div>
                    <div class="text-muted small">Obat Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h4 class="mb-0"><i class="bi bi-capsule me-2"></i>Data Master Obat</h4>
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="searchObat" class="form-control form-control-sm" style="width:220px;" placeholder="Cari obat...">
                <a href="/farmasi/tambah-obat" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Tambah Obat</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="tabelObat">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Obat</th>
                            <th>Nama Obat</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Stok Minimal</th>
                            <th>Expired</th>
                            <th>Produsen</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list_obat) && is_array($list_obat)): ?>
                            <?php $no=1; foreach ($list_obat as $obat): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= esc($obat['kode_obat'] ?? '') ?></td>
                                    <td><?= esc($obat['nama_obat'] ?? '') ?></td>
                                    <td><?= esc($obat['jenis_obat'] ?? '') ?></td>
                                    <td><?= esc($obat['kategori'] ?? '') ?></td>
                                    <td><?= esc($obat['satuan'] ?? '') ?></td>
                                    <td><?= 'Rp. ' . number_format($obat['harga_beli'] ?? 0, 0, ',', '.') ?></td>
                                    <td><?= 'Rp. ' . number_format($obat['harga_jual'] ?? 0, 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $stok = $obat['stok'] ?? 0;
                                        $stok_min = $obat['stok_minimal'] ?? 0;
                                        if ($stok > 0 && $stok < $stok_min) {
                                            echo '<span class="text-danger fw-bold">' . esc($stok) . '</span>';
                                        } else {
                                            echo esc($stok);
                                        }
                                        ?>
                                    </td>
                                    <td><?= esc($obat['stok_minimal'] ?? 0) ?></td>
                                    <td><?= esc($obat['tanggal_expired'] ?? '-') ?></td>
                                    <td><?= esc($obat['produsen'] ?? '') ?></td>
                                    <td><?= esc($obat['lokasi_penyimpanan'] ?? '') ?></td>
                                    <td>
                                        <?php if (($obat['status'] ?? '') === 'Aktif'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/farmasi/edit-obat/<?= esc($obat['id_obat'] ?? 0) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus-obat" data-id="<?= esc($obat['id_obat'] ?? 0) ?>" data-nama="<?= esc($obat['nama_obat'] ?? '') ?>"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="15" class="text-center text-muted">Data obat belum tersedia</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Konfirmasi Hapus Obat -->
<div class="modal fade" id="modalHapusObat" tabindex="-1" aria-labelledby="modalHapusObatLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalHapusObatLabel">Konfirmasi Hapus Obat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin menghapus obat <strong id="namaObatHapus"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <a href="#" id="btnKonfirmasiHapusObat" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>
<script>
// Filter pencarian obat
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchObat');
    const table = document.getElementById('tabelObat');
    if (!searchInput || !table) return;
    searchInput.addEventListener('input', function() {
        const val = this.value.trim().toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            if (row.querySelectorAll('td').length < 2) return; // skip empty row
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });
});
// Modal konfirmasi hapus obat
document.addEventListener('DOMContentLoaded', function() {
    const hapusButtons = document.querySelectorAll('.btn-hapus-obat');
    const modal = document.getElementById('modalHapusObat');
    const namaObatSpan = document.getElementById('namaObatHapus');
    const btnKonfirmasi = document.getElementById('btnKonfirmasiHapusObat');
    let hapusModal;
    if (window.bootstrap) {
        hapusModal = new bootstrap.Modal(modal);
    } else if (window.$ && window.$.fn.modal) {
        // fallback untuk Bootstrap 4
        hapusModal = { show: () => $(modal).modal('show'), hide: () => $(modal).modal('hide') };
    }
    hapusButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            namaObatSpan.textContent = nama;
            btnKonfirmasi.setAttribute('href', '/farmasi/delete-obat/' + id);
            if (hapusModal) hapusModal.show();
        });
    });
});
</script>
<style>
.table th, .table td {
    vertical-align: middle;
    font-size: 13px;
    padding: 6px 8px;
}
.table thead th {
    font-size: 13.5px;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}
.badge.bg-success { background: #28a745!important; }
.badge.bg-danger { background: #dc3545!important; }
.badge.bg-warning { background: #ffc107!important; color: #212529!important; }
</style>
<?= $this->endSection() ?>
