<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4" style="max-width:98%;">
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
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <div>
                    <label class="mb-0 me-2">Tampilkan:</label>
                    <select id="entriesPerPage" class="form-select form-select-sm d-inline-block" style="width:auto;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100" selected>100</option>
                        <option value="all">Semua</option>
                    </select>
                    <span class="ms-2 text-muted">data per halaman</span>
                </div>
                <div id="paginationInfo" class="text-muted"></div>
            </div>
            <div class="table-responsive" style="max-height: 70vh; overflow: auto;">
                <table class="table table-hover table-bordered mb-0" id="tabelObat" style="font-size: 11px;">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th style="min-width: 60px; width: 60px;">No</th>
                            <th style="min-width: 120px;">Kode Obat</th>
                            <th style="min-width: 250px;">Nama Obat</th>
                            <th style="min-width: 150px;">Jenis</th>
                            <th style="min-width: 100px;">Satuan</th>
                            <th style="min-width: 120px;">Harga Beli</th>
                            <th style="min-width: 120px;">Harga Jual</th>
                            <th style="min-width: 80px;">Stok</th>
                            <th style="min-width: 110px;">Stok Minimal</th>
                            <th style="min-width: 120px;">Expired</th>
                            <th style="min-width: 180px;">Produsen</th>
                            <th style="min-width: 150px;">Lokasi</th>
                            <th style="min-width: 100px;">Status</th>
                            <th style="min-width: 130px; width: 130px;" class="sticky-col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list_obat) && is_array($list_obat)): ?>
                            <?php $no=1; foreach ($list_obat as $obat): ?>
                                <tr class="obat-row">
                                    <td class="text-center" style="width: 60px;"><?= $no++ ?></td>
                                    <td style="white-space: nowrap;"><?= esc($obat['kode_obat'] ?? '') ?></td>
                                    <td style="max-width: 250px; white-space: normal;"><?= esc($obat['nama_obat'] ?? '') ?></td>
                                    <td><?= esc($obat['jenis_obat'] ?? '') ?></td>
                                    <td class="text-center"><?= esc($obat['satuan'] ?? '') ?></td>
                                    <td class="text-end" style="white-space: nowrap;"><?= 'Rp. ' . number_format($obat['harga_beli'] ?? 0, 0, ',', '.') ?></td>
                                    <td class="text-end" style="white-space: nowrap;"><?= 'Rp. ' . number_format($obat['harga_jual'] ?? 0, 0, ',', '.') ?></td>
                                    <td class="text-center">
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
                                    <td class="text-center"><?= esc($obat['stok_minimal'] ?? 0) ?></td>
                                    <td class="text-center" style="white-space: nowrap;"><?= esc($obat['tanggal_expired'] ?? '-') ?></td>
                                    <td><?= esc($obat['produsen'] ?? '') ?></td>
                                    <td><?= esc($obat['lokasi_penyimpanan'] ?? '') ?></td>
                                    <td class="text-center">
                                        <?php if (($obat['status'] ?? '') === 'Aktif'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center sticky-col bg-white" style="width: 130px;">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="/farmasi/edit-obat/<?= esc($obat['id_obat'] ?? 0) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger btn-hapus-obat" data-id="<?= esc($obat['id_obat'] ?? 0) ?>" data-nama="<?= esc($obat['nama_obat'] ?? '') ?>" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="14" class="text-center text-muted">Data obat belum tersedia</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                <nav>
                    <ul class="pagination pagination-sm justify-content-center mb-0" id="pagination"></ul>
                </nav>
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
// Pagination variables
let currentPage = 1;
let entriesPerPage = 100;
let allRows = [];
let filteredRows = [];

// Filter pencarian obat dengan pagination
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchObat');
    const table = document.getElementById('tabelObat');
    const entriesSelect = document.getElementById('entriesPerPage');
    
    if (!searchInput || !table) return;
    
    // Get all rows
    allRows = Array.from(table.querySelectorAll('tbody tr.obat-row'));
    filteredRows = [...allRows];
    
    // Initial render
    renderTable();
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const val = this.value.trim().toLowerCase();
        filteredRows = allRows.filter(row => {
            const text = row.textContent.toLowerCase();
            return text.includes(val);
        });
        currentPage = 1;
        renderTable();
    });
    
    // Entries per page change
    entriesSelect.addEventListener('change', function() {
        entriesPerPage = this.value === 'all' ? filteredRows.length : parseInt(this.value);
        currentPage = 1;
        renderTable();
    });
});

function renderTable() {
    const totalEntries = filteredRows.length;
    const totalPages = entriesPerPage >= totalEntries ? 1 : Math.ceil(totalEntries / entriesPerPage);
    
    // Ensure current page is valid
    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;
    
    // Hide all rows first
    allRows.forEach(row => row.style.display = 'none');
    
    // Show only current page rows
    const start = (currentPage - 1) * entriesPerPage;
    const end = Math.min(start + entriesPerPage, totalEntries);
    
    for (let i = start; i < end; i++) {
        filteredRows[i].style.display = '';
    }
    
    // Update pagination info
    const info = document.getElementById('paginationInfo');
    if (info) {
        if (totalEntries === 0) {
            info.textContent = 'Menampilkan 0 dari 0 data';
        } else {
            info.textContent = `Menampilkan ${start + 1} - ${end} dari ${totalEntries} data`;
        }
    }
    
    // Render pagination buttons
    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const pagination = document.getElementById('pagination');
    if (!pagination) return;
    
    pagination.innerHTML = '';
    
    if (totalPages <= 1) return;
    
    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">‹</a>`;
    pagination.appendChild(prevLi);
    
    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    if (startPage > 1) {
        const firstLi = document.createElement('li');
        firstLi.className = 'page-item';
        firstLi.innerHTML = `<a class="page-link" href="#" data-page="1">1</a>`;
        pagination.appendChild(firstLi);
        
        if (startPage > 2) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = `<span class="page-link">...</span>`;
            pagination.appendChild(dotsLi);
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
        pagination.appendChild(li);
    }
    
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = `<span class="page-link">...</span>`;
            pagination.appendChild(dotsLi);
        }
        
        const lastLi = document.createElement('li');
        lastLi.className = 'page-item';
        lastLi.innerHTML = `<a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>`;
        pagination.appendChild(lastLi);
    }
    
    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">›</a>`;
    pagination.appendChild(nextLi);
    
    // Add click handlers
    pagination.querySelectorAll('a.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page'));
            if (page && page !== currentPage && page >= 1 && page <= totalPages) {
                currentPage = page;
                renderTable();
                // Scroll to top of table
                document.querySelector('.table-responsive').scrollTop = 0;
            }
        });
    });
}

// Modal konfirmasi hapus obat
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalHapusObat');
    const namaObatSpan = document.getElementById('namaObatHapus');
    const btnKonfirmasi = document.getElementById('btnKonfirmasiHapusObat');
    let hapusModal;
    
    if (window.bootstrap) {
        hapusModal = new bootstrap.Modal(modal);
    } else if (window.$ && window.$.fn.modal) {
        hapusModal = { show: () => $(modal).modal('show'), hide: () => $(modal).modal('hide') };
    }
    
    // Use event delegation for dynamically shown rows
    document.querySelector('#tabelObat tbody').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-hapus-obat');
        if (btn) {
            const id = btn.getAttribute('data-id');
            const nama = btn.getAttribute('data-nama');
            namaObatSpan.textContent = nama;
            btnKonfirmasi.setAttribute('href', '/farmasi/delete-obat/' + id);
            if (hapusModal) hapusModal.show();
        }
    });
});
</script>
<style>
.table-responsive {
    position: relative;
    overflow: auto;
}

.table th, .table td {
    vertical-align: middle;
    font-size: 11px;
    padding: 10px 8px;
}

.table thead th {
    font-size: 11px;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    background: #f8f9fa !important;
    position: sticky;
    top: 0;
    z-index: 10;
    white-space: nowrap;
    border: 1px solid #dee2e6;
}

.table tbody td {
    white-space: nowrap;
    border: 1px solid #dee2e6;
}

.table tbody td[style*="white-space: normal"] {
    white-space: normal !important;
    word-wrap: break-word;
}

.sticky-col {
    position: sticky !important;
    right: 0;
    z-index: 5;
    box-shadow: -3px 0 8px rgba(0,0,0,0.1);
    background: white !important;
}

.table thead th.sticky-col {
    z-index: 11 !important;
    background: #f8f9fa !important;
}

.badge.bg-success { 
    background: #28a745!important; 
    font-size: 10px;
    padding: 4px 8px;
}
.badge.bg-danger { 
    background: #dc3545!important; 
    font-size: 10px;
    padding: 4px 8px;
}
.badge.bg-warning { 
    background: #ffc107!important; 
    color: #212529!important; 
    font-size: 10px;
    padding: 4px 8px;
}

.pagination {
    margin: 0;
}

.page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.table-responsive::-webkit-scrollbar {
    height: 10px;
    width: 10px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 5px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 5px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 11px;
}

.btn-sm i {
    font-size: 12px;
}

/* Zebra striping for better readability */
.table-hover tbody tr:hover {
    background-color: #f1f3f5;
}

.obat-row:nth-child(even) {
    background-color: #f8f9fa;
}

/* Improve button visibility */
.sticky-col .btn {
    white-space: nowrap;
}
</style>
<?= $this->endSection() ?>
