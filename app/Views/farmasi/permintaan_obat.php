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
        <div class="card-header bg-gradient-primary text-white p-4">
            <div class="row align-items-center">
                <div class="col-md-6 col-12 mb-3 mb-md-0">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-prescription2 me-2"></i>Permintaan Obat dari Dokter</h4>
                </div>
                <div class="col-md-6 col-12">
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <div class="filter-group">
                                <label class="filter-label">Filter Tanggal</label>
                                <select id="filterTanggal" class="form-select filter-select">
                                    <option value="">Semua Tanggal</option>
                                    <option value="today" selected>Hari Ini</option>
                                    <option value="yesterday">Kemarin</option>
                                    <option value="week">7 Hari Terakhir</option>
                                    <option value="month">30 Hari Terakhir</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="filter-group">
                                <label class="filter-label">Filter Status</label>
                                <select id="filterStatus" class="form-select filter-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Belum Diproses</option>
                                    <option value="processing">Sedang Diproses</option>
                                    <option value="completed">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="filter-group">
                                <label class="filter-label">Pencarian</label>
                                <div class="search-wrapper">
                                    <i class="bi bi-search search-icon"></i>
                                    <input type="text" id="searchPermintaan" class="form-control search-input" placeholder="Cari pasien/dokter...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <?php 
                                    $tanggalPermintaan = $permintaan['tanggal_permintaan'] ?? $permintaan['tanggal_resep'] ?? '';
                                    $tanggalFormatted = date('Y-m-d', strtotime($tanggalPermintaan));
                                ?>
                                <tr data-status="<?= esc($permintaan['status'] ?? '') ?>" data-tanggal="<?= $tanggalFormatted ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td>
                                        <div class="fw-bold text-primary"><?= date('d/m/Y', strtotime($tanggalPermintaan)) ?></div>
                                        <small class="text-muted"><?= date('H:i', strtotime($tanggalPermintaan)) ?></small>
                                    </td>
                                    <td><strong><?= esc($permintaan['no_rm'] ?? 'RM000001') ?></strong></td>
                                    <td><?= esc($permintaan['nama_pasien'] ?? 'Mukhamad Diva Mahardika Awaludin') ?></td>
                                    <td><?= esc($permintaan['nama_dokter'] ?? 'Dr. Administrator') ?></td>
                                    <td><strong><?= esc($permintaan['nama_obat'] ?? '') ?></strong>
                                        <?php if (!empty($permintaan['id_obat'])): ?>
                                            <?php
                                            // Ambil stok obat dari database jika ada id_obat
                                            $db = \Config\Database::connect();
                                            $obat = $db->table('obat')->where('id_obat', $permintaan['id_obat'])->get()->getRowArray();
                                            if ($obat):
                                                $stok = $obat['stok'];
                                                $jumlahDiminta = $permintaan['jumlah'] ?? 0;
                                                $badgeClass = 'success';
                                                
                                                if ($stok <= 0) {
                                                    $badgeClass = 'danger';
                                                } elseif ($stok < $jumlahDiminta) {
                                                    $badgeClass = 'warning';
                                                } elseif ($stok <= ($obat['stok_minimal'] ?? 0)) {
                                                    $badgeClass = 'warning';
                                                }
                                            ?>
                                                <br><small class="badge bg-<?= $badgeClass ?> mt-1">Stok: <?= $stok ?></small>
                                                <?php if ($stok < $jumlahDiminta): ?>
                                                    <br><small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Stok tidak mencukupi!</small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
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
                                                <?php
                                                // Cek stok untuk tombol proses
                                                $canProcess = true;
                                                $tooltipMessage = "Proses Permintaan";
                                                
                                                if (!empty($permintaan['id_obat'])) {
                                                    $db = \Config\Database::connect();
                                                    $obat = $db->table('obat')->where('id_obat', $permintaan['id_obat'])->get()->getRowArray();
                                                    if ($obat && $obat['stok'] < ($permintaan['jumlah'] ?? 0)) {
                                                        $canProcess = false;
                                                        $tooltipMessage = "Stok tidak mencukupi - Stok: " . $obat['stok'] . ", Diminta: " . ($permintaan['jumlah'] ?? 0);
                                                    }
                                                }
                                                ?>
                                                <button type="button" 
                                                        class="btn btn-sm <?= $canProcess ? 'btn-info btn-proses' : 'btn-secondary' ?>" 
                                                        data-id="<?= esc($permintaan['id'] ?? 0) ?>" 
                                                        title="<?= $tooltipMessage ?>" 
                                                        style="min-width: 70px;"
                                                        <?= $canProcess ? '' : 'disabled' ?>>
                                                    <?= $canProcess ? 'Proses' : 'Stok <' ?>
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
    const filterTanggal = document.getElementById('filterTanggal');
    const table = document.getElementById('tabelPermintaan');

    // Helper function untuk mendapatkan tanggal
    function getDateRange(filter) {
        // Gunakan waktu lokal, bukan UTC
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const todayStr = `${year}-${month}-${day}`;
        
        console.log('Today (Local):', todayStr);
        
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayStr = `${yesterday.getFullYear()}-${String(yesterday.getMonth() + 1).padStart(2, '0')}-${String(yesterday.getDate()).padStart(2, '0')}`;
        
        switch (filter) {
            case 'today':
                console.log('Filter Hari Ini:', todayStr);
                return {
                    start: todayStr,
                    end: todayStr
                };
            case 'yesterday':
                return {
                    start: yesterdayStr,
                    end: yesterdayStr
                };
            case 'week':
                const weekAgo = new Date(today);
                weekAgo.setDate(weekAgo.getDate() - 7);
                const weekAgoStr = `${weekAgo.getFullYear()}-${String(weekAgo.getMonth() + 1).padStart(2, '0')}-${String(weekAgo.getDate()).padStart(2, '0')}`;
                return {
                    start: weekAgoStr,
                    end: todayStr
                };
            case 'month':
                const monthAgo = new Date(today);
                monthAgo.setDate(monthAgo.getDate() - 30);
                const monthAgoStr = `${monthAgo.getFullYear()}-${String(monthAgo.getMonth() + 1).padStart(2, '0')}-${String(monthAgo.getDate()).padStart(2, '0')}`;
                return {
                    start: monthAgoStr,
                    end: todayStr
                };
            default:
                return null;
        }
    }

    function filterTable() {
        const searchVal = searchInput.value.trim().toLowerCase();
        const statusVal = filterStatus.value;
        const tanggalVal = filterTanggal.value;
        const rows = table.querySelectorAll('tbody tr[data-status]');
        
        const dateRange = getDateRange(tanggalVal);
        
        let visibleCount = 0;
        
        console.log('=== Filter Debug ===');
        console.log('Filter Tanggal:', tanggalVal);
        console.log('Date Range:', dateRange);
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            const tanggalRow = row.getAttribute('data-tanggal');
            
            console.log('Row Tanggal:', tanggalRow);
            
            const matchSearch = text.includes(searchVal);
            const matchStatus = !statusVal || status === statusVal;
            
            let matchTanggal = true;
            if (dateRange && tanggalRow) {
                matchTanggal = tanggalRow >= dateRange.start && tanggalRow <= dateRange.end;
                console.log('Match?', tanggalRow, '>=', dateRange.start, '&&', tanggalRow, '<=', dateRange.end, '=', matchTanggal);
            }
            
            if (matchSearch && matchStatus && matchTanggal) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        console.log('Visible Count:', visibleCount);
        console.log('Total Rows:', rows.length);
        
        // Update counter
        updateCounter();
    }

    function updateCounter() {
        const allRows = table.querySelectorAll('tbody tr[data-status]');
        const visibleRows = table.querySelectorAll('tbody tr[data-status]:not([style*="display: none"])');
        const totalVisible = visibleRows.length;
        const totalAll = allRows.length;
        
        // Update header title with counter
        const headerTitle = document.querySelector('.card-header h4');
        if (headerTitle) {
            const baseTitle = 'Permintaan Obat dari Dokter';
            if (totalVisible !== totalAll) {
                headerTitle.innerHTML = `<i class="bi bi-prescription2 me-2"></i>${baseTitle} <small class="text-warning">(${totalVisible} dari ${totalAll})</small>`;
            } else {
                headerTitle.innerHTML = `<i class="bi bi-prescription2 me-2"></i>${baseTitle}`;
            }
        }
    }

    // Event listeners
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);
    if (filterTanggal) filterTanggal.addEventListener('change', filterTable);

    // Jalankan filter awal (untuk "Hari Ini" yang sudah terpilih)
    filterTable();

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

    // Button batal (untuk processing)
    document.querySelectorAll('.btn-batal').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            Swal.fire({
                title: 'Konfirmasi Pembatalan',
                text: 'Yakin ingin membatalkan proses permintaan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/farmasi/batal-permintaan/' + id;
                }
            });
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
/* Header styling improvements */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.card-header {
    border-bottom: none;
    border-radius: 8px 8px 0 0 !important;
}

/* Filter styling improvements */
.filter-group {
    margin-bottom: 0;
}

.filter-label {
    display: block;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    font-size: 12px;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-select {
    background-color: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    color: #495057;
    font-size: 14px;
    padding: 8px 12px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.filter-select:focus {
    background-color: #fff;
    border-color: rgba(255, 255, 255, 0.8);
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
    outline: none;
}

.search-wrapper {
    position: relative;
}

.search-input {
    background-color: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    color: #495057;
    font-size: 14px;
    padding: 8px 12px 8px 40px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.search-input:focus {
    background-color: #fff;
    border-color: rgba(255, 255, 255, 0.8);
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
    outline: none;
}

.search-input::placeholder {
    color: rgba(108, 117, 125, 0.7);
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(108, 117, 125, 0.7);
    font-size: 14px;
    z-index: 5;
    pointer-events: none;
}

/* Table styling */
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
    border-bottom: 2px solid #e9ecef;
}

.badge {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 6px;
}

.btn-sm {
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 500;
}

.card {
    border-radius: 12px;
    border: none;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .card-header {
        padding: 20px !important;
    }
    
    .card-header .col-12:first-child {
        text-align: center;
        margin-bottom: 20px !important;
    }
    
    .card-header h4 {
        font-size: 1.3rem;
    }
    
    .filter-group {
        margin-bottom: 15px;
    }
    
    .filter-group:last-child {
        margin-bottom: 0;
    }
}

@media (min-width: 769px) {
    .card-header .row {
        align-items: flex-end;
    }
}

/* Stock status styling */
.badge.bg-danger {
    background-color: #dc3545 !important;
}
.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}
.badge.bg-success {
    background-color: #198754 !important;
}
.text-danger {
    color: #dc3545 !important;
}

/* Hover effects */
.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transition: background-color 0.15s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
    transition: transform 0.15s ease-in-out;
}

.filter-select:hover, .search-input:hover {
    background-color: #fff;
    border-color: rgba(255, 255, 255, 0.6);
}

/* Card statistics improvements */
.card.border-0 {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border-radius: 12px;
}

.card.border-0:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Additional enhancements */
.card-body {
    border-radius: 0 0 12px 12px;
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.fw-bold.text-primary {
    color: #007bff !important;
    font-weight: 600 !important;
}

/* Animation for counter updates */
.card-header h4 small {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
<?= $this->endSection() ?>
