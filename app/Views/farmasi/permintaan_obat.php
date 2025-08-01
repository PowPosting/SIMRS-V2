<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4" style="max-width:1400px;">
    <?php
    // Hitung statistik permintaan obat
    $totalPermintaan = is_array($list_permintaan) ? count($list_permintaan) : 0;
    $belumDiproses = 0;
    $sedangDiproses = 0;
    $selesaiDiproses = 0;
    if (!empty($list_permintaan) && is_array($list_permintaan)) {
        foreach ($list_permintaan as $permintaan) {
            switch ($permintaan['status'] ?? '') {
                case 'pending':
                    $belumDiproses++;
                    break;
                case 'processing':
                    $sedangDiproses++;
                    break;
                case 'completed':
                    $selesaiDiproses++;
                    break;
            }
        }
    }
    ?>
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-2"><i class="bi bi-list-task text-primary" style="font-size:2rem;"></i></div>
                    <div class="fw-bold" style="font-size:1.5rem; color:#007bff;"><?= $totalPermintaan ?></div>
                    <div class="text-muted small">Total Permintaan</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-2"><i class="bi bi-clock text-warning" style="font-size:2rem;"></i></div>
                    <div class="fw-bold" style="font-size:1.5rem; color:#ffc107;"><?= $belumDiproses ?></div>
                    <div class="text-muted small">Belum Diproses</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-2"><i class="bi bi-gear text-info" style="font-size:2rem;"></i></div>
                    <div class="fw-bold" style="font-size:1.5rem; color:#17a2b8;"><?= $sedangDiproses ?></div>
                    <div class="text-muted small">Sedang Diproses</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-2"><i class="bi bi-check-circle text-success" style="font-size:2rem;"></i></div>
                    <div class="fw-bold" style="font-size:1.5rem; color:#28a745;"><?= $selesaiDiproses ?></div>
                    <div class="text-muted small">Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h4 class="mb-0"><i class="bi bi-prescription2 me-2"></i>Permintaan Obat dari Dokter</h4>
            <div class="d-flex align-items-center gap-2">
                <select id="filterStatus" class="form-select form-select-sm" style="width:180px;">
                    <option value="">Semua Status</option>
                    <option value="pending">Belum Diproses</option>
                    <option value="processing">Sedang Diproses</option>
                    <option value="completed">Selesai</option>
                </select>
                <input type="text" id="searchPermintaan" class="form-control form-control-sm" style="width:220px;" placeholder="Cari pasien/dokter...">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="tabelPermintaan">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>No. RM</th>
                            <th>Nama Pasien</th>
                            <th>Dokter</th>
                            <th>Obat Diminta</th>
                            <th>Jumlah</th>
                            <th>Instruksi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list_permintaan) && is_array($list_permintaan)): ?>
                            <?php $no=1; foreach ($list_permintaan as $permintaan): ?>
                                <tr data-status="<?= esc($permintaan['status'] ?? '') ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($permintaan['tanggal_permintaan'] ?? $permintaan['tanggal_resep'] ?? '')) ?></td>
                                    <td><strong><?= esc($permintaan['no_rm'] ?? 'RM000001') ?></strong></td>
                                    <td><?= esc($permintaan['nama_pasien'] ?? 'Mukhamad Diva Mahardika Awaludin') ?></td>
                                    <td><?= esc($permintaan['nama_dokter'] ?? 'Dr. Administrator') ?></td>
                                    <td><strong><?= esc($permintaan['nama_obat'] ?? '') ?></strong></td>
                                    <td class="text-center"><?= esc($permintaan['jumlah'] ?? 0) ?> <?= esc($permintaan['satuan'] ?? '') ?></td>
                                    <td><?= esc($permintaan['instruksi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php
                                        $status = $permintaan['status'] ?? 'pending';
                                        switch($status) {
                                            case 'pending':
                                                echo '<span class="badge bg-warning">Belum Diproses</span>';
                                                break;
                                            case 'processing':
                                                echo '<span class="badge bg-info">Sedang Diproses</span>';
                                                break;
                                            case 'completed':
                                                echo '<span class="badge bg-success">Selesai</span>';
                                                break;
                                            default:
                                                echo '<span class="badge bg-secondary">Unknown</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <?php if ($status === 'pending'): ?>
                                                <button type="button" class="btn btn-sm btn-info btn-proses" data-id="<?= esc($permintaan['id'] ?? 0) ?>" title="Proses Permintaan" style="min-width: 70px;">
                                                    Proses
                                                </button>
                                            <?php elseif ($status === 'processing'): ?>
                                                <button type="button" class="btn btn-sm btn-success btn-selesai" data-id="<?= esc($permintaan['id'] ?? 0) ?>" title="Selesaikan" style="min-width: 70px;">
                                                    Selesai
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning btn-batal" data-id="<?= esc($permintaan['id'] ?? 0) ?>" title="Batalkan" style="min-width: 70px;">
                                                    Batal
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-detail" data-id="<?= esc($permintaan['id'] ?? 0) ?>" title="Lihat Detail" style="min-width: 70px;">
                                                    Detail
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="10" class="text-center text-muted py-4">Belum ada permintaan obat</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Permintaan -->
<div class="modal fade" id="modalDetailPermintaan" tabindex="-1" aria-labelledby="modalDetailPermintaanLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetailPermintaanLabel">Detail Permintaan Obat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="detailPermintaanContent">
        <!-- Content akan diisi via AJAX atau JavaScript -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Proses -->
<div class="modal fade" id="modalKonfirmasiProses" tabindex="-1" aria-labelledby="modalKonfirmasiProsesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalKonfirmasiProsesLabel">Konfirmasi Proses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin memproses permintaan obat ini?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <a href="#" id="btnKonfirmasiProses" class="btn btn-info">Proses</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Selesai -->
<div class="modal fade" id="modalKonfirmasiSelesai" tabindex="-1" aria-labelledby="modalKonfirmasiSelesaiLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalKonfirmasiSelesaiLabel">Konfirmasi Selesai</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Yakin obat sudah disiapkan dan siap diambil pasien?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <a href="#" id="btnKonfirmasiSelesai" class="btn btn-success">Selesai</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter pencarian
    const searchInput = document.getElementById('searchPermintaan');
    const filterStatus = document.getElementById('filterStatus');
    const table = document.getElementById('tabelPermintaan');

    function filterTable() {
        const searchVal = searchInput.value.trim().toLowerCase();
        const statusVal = filterStatus.value;
        const rows = table.querySelectorAll('tbody tr[data-status]');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            
            const matchSearch = text.includes(searchVal);
            const matchStatus = !statusVal || status === statusVal;
            
            row.style.display = matchSearch && matchStatus ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);

    // Modal handlers
    const modalProses = document.getElementById('modalKonfirmasiProses');
    const modalSelesai = document.getElementById('modalKonfirmasiSelesai');
    const btnKonfirmasiProses = document.getElementById('btnKonfirmasiProses');
    const btnKonfirmasiSelesai = document.getElementById('btnKonfirmasiSelesai');

    let prosesModal, selesaiModal;
    if (window.bootstrap) {
        prosesModal = new bootstrap.Modal(modalProses);
        selesaiModal = new bootstrap.Modal(modalSelesai);
    } else if (window.$ && window.$.fn.modal) {
        prosesModal = { show: () => $(modalProses).modal('show'), hide: () => $(modalProses).modal('hide') };
        selesaiModal = { show: () => $(modalSelesai).modal('show'), hide: () => $(modalSelesai).modal('hide') };
    }

    // Button proses
    document.querySelectorAll('.btn-proses').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            btnKonfirmasiProses.setAttribute('href', '/farmasi/proses-permintaan/' + id);
            if (prosesModal) prosesModal.show();
        });
    });

    // Button selesai
    document.querySelectorAll('.btn-selesai').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            btnKonfirmasiSelesai.setAttribute('href', '/farmasi/selesai-permintaan/' + id);
            if (selesaiModal) selesaiModal.show();
        });
    });

    // Button batal (langsung redirect tanpa modal)
    document.querySelectorAll('.btn-batal').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (confirm('Yakin ingin membatalkan proses permintaan ini?')) {
                window.location.href = '/farmasi/batal-permintaan/' + id;
            }
        });
    });

    // Button detail (bisa diperluas untuk AJAX)
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            // Untuk sementara redirect ke detail page
            window.location.href = '/farmasi/detail-permintaan/' + id;
        });
    });
});
</script>

<style>
.table th, .table td {
    vertical-align: middle;
    font-size: 14px;
    padding: 12px 8px;
}
.table thead th {
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    background-color: #f8f9fa;
}
.badge {
    font-size: 12px;
    padding: 6px 12px;
}
.btn-sm {
    font-size: 13px;
    padding: 6px 12px;
}
.card {
    border-radius: 8px;
}
</style>
<?= $this->endSection() ?>
