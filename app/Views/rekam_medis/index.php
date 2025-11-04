<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="font-weight-bold mb-0" style="color: #000;">
                                <i class="fas fa-folder-open text-primary mr-2 fa-lg"></i>
                                Rekam Medis Pasien
                            </h3>
                            <p class="text-muted small mb-0 mt-1">Riwayat rekam medis seluruh pasien</p>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" onclick="window.location.reload()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search Box -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary">
                                        <i class="fas fa-search text-white"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari No. RM, Nama, atau No. HP...">
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="text-muted">Total Pasien: <strong id="totalPasien">0</strong></span>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablePasien">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">No. RM</th>
                                    <th width="20%">Nama Pasien</th>
                                    <th width="10%">L/P</th>
                                    <th width="10%">Umur</th>
                                    <th width="12%">No. HP</th>
                                    <th width="10%" class="text-center">Total Kunjungan</th>
                                    <th width="13%">Kunjungan Terakhir</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Memuat data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div id="paginationInfo" class="text-muted"></div>
                        </div>
                        <div class="col-md-6">
                            <nav>
                                <ul class="pagination justify-content-end mb-0" id="pagination"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    font-weight: 600;
    color: #333;
    border-top: none;
}

.table td {
    vertical-align: middle;
}

.badge-kunjungan {
    font-size: 16px;
    padding: 8px 12px;
    border-radius: 50%;
    min-width: 40px;
    display: inline-block;
}

.badge-rm {
    background-color: #e3f2fd !important;
    color: #1976d2 !important;
    font-weight: 600;
    padding: 6px 12px;
    border: 1px solid #90caf9;
}

.badge-gender-l {
    background-color: #e1f5fe !important;
    color: #0277bd !important;
    font-weight: 600;
    padding: 6px 12px;
}

.badge-gender-p {
    background-color: #fce4ec !important;
    color: #c2185b !important;
    font-weight: 600;
    padding: 6px 12px;
}

.badge-kunjungan-custom {
    font-size: 16px;
    padding: 8px 12px;
    border-radius: 50%;
    min-width: 40px;
    display: inline-block;
    font-weight: bold;
}

.badge-kunjungan-high {
    background-color: #c8e6c9 !important;
    color: #2e7d32 !important;
}

.badge-kunjungan-medium {
    background-color: #fff9c4 !important;
    color: #f57f17 !important;
}

.badge-kunjungan-low {
    background-color: #e0e0e0 !important;
    color: #616161 !important;
}

.btn-detail {
    transition: all 0.3s ease;
}

.btn-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentPage = 1;
let totalRecords = 0;
const recordsPerPage = 10;
let searchQuery = '';

$(document).ready(function() {
    loadData();

    // Search dengan debounce
    let searchTimer;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            searchQuery = $(this).val();
            currentPage = 1;
            loadData();
        }, 500);
    });
});

function loadData() {
    const start = (currentPage - 1) * recordsPerPage;
    
    $.ajax({
        url: '<?= base_url('rekam-medis/get-pasien-list') ?>',
        method: 'GET',
        data: {
            start: start,
            length: recordsPerPage,
            search: searchQuery
        },
        beforeSend: function() {
            $('#tableBody').html(`
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat data...</p>
                    </td>
                </tr>
            `);
        },
        success: function(response) {
            if (response.success) {
                totalRecords = response.recordsTotal;
                $('#totalPasien').text(totalRecords);
                renderTable(response.data, start);
                renderPagination();
            } else {
                showError(response.message);
            }
        },
        error: function(xhr) {
            showError('Gagal memuat data. Silakan coba lagi.');
        }
    });
}

function renderTable(data, start) {
    let html = '';
    
    if (data.length === 0) {
        html = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">Tidak ada data</h5>
                    <p class="text-muted">Belum ada pasien yang terdaftar atau tidak ditemukan</p>
                </td>
            </tr>
        `;
    } else {
        data.forEach((row, index) => {
            const no = start + index + 1;
            const kunjunganClass = row.total_kunjungan > 10 ? 'badge-kunjungan-high' : 
                                   row.total_kunjungan > 5 ? 'badge-kunjungan-medium' : 'badge-kunjungan-low';
            const genderClass = row.jenis_kelamin === 'L' ? 'badge-gender-l' : 'badge-gender-p';
            
            html += `
                <tr>
                    <td>${no}</td>
                    <td><span class="badge badge-rm">${row.no_rekam_medis}</span></td>
                    <td><strong>${row.title} ${row.nama_lengkap}</strong></td>
                    <td><span class="badge ${genderClass}">${row.jenis_kelamin_text}</span></td>
                    <td>${row.umur}</td>
                    <td>${row.nomor_hp || '-'}</td>
                    <td class="text-center">
                        <span class="badge badge-kunjungan-custom ${kunjunganClass}">${row.total_kunjungan}</span>
                    </td>
                    <td><small>${row.kunjungan_terakhir_formatted}</small></td>
                    <td class="text-center">
                        <a href="<?= base_url('rekam-medis/detail/') ?>${row.no_rekam_medis}" 
                           class="btn btn-sm btn-primary btn-detail">
                            <i class="fas fa-folder-open mr-1"></i> Detail
                        </a>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#tableBody').html(html);
}

function renderPagination() {
    const totalPages = Math.ceil(totalRecords / recordsPerPage);
    const start = (currentPage - 1) * recordsPerPage + 1;
    const end = Math.min(currentPage * recordsPerPage, totalRecords);
    
    // Info
    $('#paginationInfo').html(`Menampilkan ${start} - ${end} dari ${totalRecords} pasien`);
    
    // Pagination buttons
    let html = '';
    
    // Previous
    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Pages
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>
            `;
        } else if (i === currentPage - 3 || i === currentPage + 3) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    // Next
    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `;
    
    $('#pagination').html(html);
}

function changePage(page) {
    const totalPages = Math.ceil(totalRecords / recordsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadData();
}

function showError(message) {
    $('#tableBody').html(`
        <tr>
            <td colspan="9" class="text-center py-4">
                <i class="fas fa-exclamation-circle fa-3x text-danger mb-3 d-block"></i>
                <h5 class="text-danger">Error</h5>
                <p class="text-muted">${message}</p>
                <button class="btn btn-outline-primary mt-2" onclick="loadData()">
                    <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                </button>
            </td>
        </tr>
    `);
}
</script>
<?= $this->endSection() ?>
