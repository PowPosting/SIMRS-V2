<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4" style="max-width:1400px;">
    <!-- Header Section -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>
                    <i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran
                </h2>
                <p>Riwayat transaksi pembayaran pasien di sistem kasir</p>
            </div>
            <div class="date-badge">
                <i class="bi bi-calendar3 me-2"></i><?= date('d M Y') ?>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="bi bi-funnel me-2"></i>Filter Riwayat</h5>
        </div>
        <div class="card-body">
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">Cari Pasien/No. RM</label>
                            <div class="search-wrapper">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" class="form-control search-input" id="cariPasien" placeholder="Nama atau No. RM">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <label class="filter-label">Tanggal Dari</label>
                            <input type="date" class="form-control" id="tanggalDari" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <label class="filter-label">Tanggal Sampai</label>
                            <input type="date" class="form-control" id="tanggalSampai" value="">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="button" class="btn btn-primary" onclick="filterRiwayat()">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetFilter()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportData()">
                                <i class="bi bi-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card summary-card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="summary-icon bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-receipt text-white fs-4"></i>
                    </div>
                    <h6 class="summary-title text-muted mb-2">TOTAL TRANSAKSI</h6>
                    <h3 class="summary-value text-primary fw-bold mb-0" id="totalTransaksi"><?= count($riwayat_pembayaran ?? []) ?></h3>
                    <small class="text-muted">Transaksi Hari Ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card summary-card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="summary-icon bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-cash-coin text-white fs-4"></i>
                    </div>
                    <h6 class="summary-title text-muted mb-2">TOTAL PENDAPATAN</h6>
                    <h3 class="summary-value text-success fw-bold mb-0" id="totalPendapatan">
                        Rp <?= number_format(array_sum(array_column($riwayat_pembayaran ?? [], 'total_tagihan')), 0, ',', '.') ?>
                    </h3>
                    <small class="text-muted">Pendapatan Hari Ini</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran Table -->
    <div class="table-container">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5>
                    <i class="bi bi-list-ul me-2"></i>Daftar Riwayat Pembayaran
                </h5>
                <span class="badge bg-light text-primary" id="totalRiwayat">
                    Total: <?= count($riwayat_pembayaran ?? []) ?> transaksi
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="riwayatTable">
                <thead>
                    <tr>
                        <th class="text-center">Waktu Bayar</th>
                        <th class="text-center">No. RM</th>
                        <th>Nama Pasien</th>
                        <th class="text-center">Total Tagihan</th>
                        <th>Kasir</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="riwayatBody">
                    <?php if (!empty($riwayat_pembayaran)): ?>
                        <?php foreach ($riwayat_pembayaran as $index => $riwayat): ?>
                            <tr>
                                <td class="text-center">
                                    <div class="fw-bold text-primary"><?= date('d/m/Y', strtotime($riwayat['waktu_bayar'])) ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($riwayat['waktu_bayar'])) ?></small>
                                </td>
                                <td class="text-center fw-bold text-primary"><?= esc($riwayat['no_rm']) ?></td>
                                <td>
                                    <div class="fw-semibold"><?= esc($riwayat['nama_pasien']) ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-success">Rp <?= number_format($riwayat['total_tagihan'], 0, ',', '.') ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= esc($riwayat['nama_kasir']) ?></div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="lihatDetail('<?= $riwayat['no_rm'] ?>', '<?= date('Y-m-d', strtotime($riwayat['waktu_bayar'])) ?>')" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-clock-history"></i>
                                    <h6>Belum Ada Riwayat Pembayaran</h6>
                                    <p>Riwayat pembayaran akan muncul di sini setelah ada transaksi yang berhasil</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Detail Transaksi
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Detail content akan dimuat di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnPrintStruk" onclick="printStrukFromModal()">
                    <i class="bi bi-printer me-1"></i>Cetak Struk
                </button>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function filterRiwayat() {
    const cariPasien = document.getElementById('cariPasien').value.toLowerCase().trim();
    const tanggalDari = document.getElementById('tanggalDari').value;
    const tanggalSampai = document.getElementById('tanggalSampai').value;
    
    console.log('Filter:', { cariPasien, tanggalDari, tanggalSampai });
    
    // Filter client-side
    const rows = document.querySelectorAll('#riwayatTable tbody tr');
    let visibleCount = 0;
    let totalPendapatan = 0;
    
    rows.forEach(row => {
        if (row.cells.length === 1) return; // Skip empty state row
        
        // Ambil data dari sel
        const waktuCell = row.cells[0].querySelector('.fw-bold');
        const noRM = row.cells[1].textContent.trim();
        const namaPasien = row.cells[2].textContent.trim().toLowerCase();
        const totalTagihan = row.cells[3].textContent.trim();
        
        // Parse tanggal dari format dd/mm/yyyy
        let rowDateStr = '';
        if (waktuCell) {
            rowDateStr = waktuCell.textContent.trim(); // Format: dd/mm/yyyy
        }
        
        let show = true;
        
        // Filter by patient name/RM
        if (cariPasien) {
            if (!namaPasien.includes(cariPasien) && !noRM.toLowerCase().includes(cariPasien)) {
                show = false;
            }
        }
        
        // Filter by date range
        if (rowDateStr && (tanggalDari || tanggalSampai)) {
            // Parse dd/mm/yyyy ke Date object
            const [day, month, year] = rowDateStr.split('/');
            const rowDate = new Date(year, parseInt(month) - 1, day);
            rowDate.setHours(0, 0, 0, 0); // Reset time untuk perbandingan tanggal saja
            
            if (tanggalDari) {
                const fromDate = new Date(tanggalDari);
                fromDate.setHours(0, 0, 0, 0);
                if (rowDate < fromDate) {
                    show = false;
                }
            }
            
            if (tanggalSampai) {
                const toDate = new Date(tanggalSampai);
                toDate.setHours(23, 59, 59, 999);
                if (rowDate > toDate) {
                    show = false;
                }
            }
        }
        
        row.style.display = show ? '' : 'none';
        
        if (show) {
            visibleCount++;
            // Hitung total pendapatan dari row yang visible
            const nilai = totalTagihan.replace(/[^\d]/g, ''); // Hapus Rp, titik, koma
            if (nilai) {
                totalPendapatan += parseInt(nilai);
            }
        }
    });
    
    // Update counter dan total pendapatan
    document.getElementById('totalRiwayat').textContent = `Total: ${visibleCount} transaksi`;
    document.getElementById('totalTransaksi').textContent = visibleCount;
    document.getElementById('totalPendapatan').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPendapatan);
}

function resetFilter() {
    document.getElementById('cariPasien').value = '';
    document.getElementById('tanggalDari').value = '';
    document.getElementById('tanggalSampai').value = '';
    
    // Show all rows
    const rows = document.querySelectorAll('#riwayatTable tbody tr');
    let totalCount = 0;
    let totalPendapatan = 0;
    
    rows.forEach(row => {
        if (row.cells.length > 1) { // Skip empty state row
            row.style.display = '';
            totalCount++;
            
            // Hitung total pendapatan
            const totalTagihan = row.cells[3].textContent.trim();
            const nilai = totalTagihan.replace(/[^\d]/g, '');
            if (nilai) {
                totalPendapatan += parseInt(nilai);
            }
        }
    });
    
    // Reset counter dan total
    document.getElementById('totalRiwayat').textContent = `Total: ${totalCount} transaksi`;
    document.getElementById('totalTransaksi').textContent = totalCount;
    document.getElementById('totalPendapatan').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPendapatan);
}

function exportData() {
    // Get filter values
    const tanggalDari = document.getElementById('tanggalDari').value;
    const tanggalSampai = document.getElementById('tanggalSampai').value;
    const cariPasien = document.getElementById('cariPasien').value;
    
    // Build URL with query parameters
    let url = '<?= base_url('kasir/export-excel') ?>';
    const params = new URLSearchParams();
    
    if (tanggalDari) params.append('tanggal_dari', tanggalDari);
    if (tanggalSampai) params.append('tanggal_sampai', tanggalSampai);
    if (cariPasien) params.append('search', cariPasien);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    // Show loading
    Swal.fire({
        title: 'Mengekspor Data',
        html: '<i class="bi bi-hourglass-split"></i> Sedang memproses data ke Excel...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Export to Excel
    window.location.href = url;
    
    // Close loading after delay
    setTimeout(() => {
        Swal.close();
    }, 2000);
}

function exportSemuaData() {
    // Konfirmasi
    Swal.fire({
        title: 'Export Semua Data?',
        text: 'Akan mengekspor semua riwayat pembayaran tanpa filter',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Export',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#198754'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Mengekspor Data',
                html: '<i class="bi bi-hourglass-split"></i> Sedang memproses semua data ke Excel...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Export all data (no filter parameters)
            window.location.href = '<?= base_url('kasir/export-excel') ?>';
            
            // Close loading after delay
            setTimeout(() => {
                Swal.close();
            }, 2000);
        }
    });
}

// Global variable untuk menyimpan data transaksi yang sedang dilihat
let currentTransactionData = {};

function lihatDetail(noRm, tanggal) {
    // Simpan data transaksi untuk keperluan print
    currentTransactionData = { noRm: noRm, tanggal: tanggal };
    
    // Show detail in a simple modal
    $('#detailContent').html('<div class="text-center"><i class="bi bi-hourglass-split"></i> Memuat data...</div>');
    $('#modalDetail').modal('show');
    
    // Load detail via AJAX
    $.ajax({
        url: '<?= site_url('kasir/detail-tagihan/') ?>' + noRm + '/' + tanggal + '?view=simple',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const detailHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informasi Pasien</h6>
                            <table class="table table-borderless table-sm">
                                <tr><td class="fw-medium">Nama Pasien:</td><td>${data.nama_pasien}</td></tr>
                                <tr><td class="fw-medium">No. RM:</td><td>${data.no_rm}</td></tr>
                                <tr><td class="fw-medium">Tanggal Lahir:</td><td>${data.tanggal_lahir}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informasi Tagihan</h6>
                            <table class="table table-borderless table-sm">
                                <tr><td class="fw-medium">No. Tagihan:</td><td>${data.no_tagihan}</td></tr>
                                <tr><td class="fw-medium">Jenis Layanan:</td><td>${data.jenis_layanan}</td></tr>
                                <tr><td class="fw-medium">Metode Bayar:</td><td>${data.metode_pembayaran}</td></tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Detail Biaya</h6>
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr><th>Deskripsi</th><th class="text-end">Jumlah</th></tr>
                                </thead>
                                <tbody>
                                    ${data.detail_biaya || '<tr><td colspan="2" class="text-center text-muted">Data detail tidak tersedia</td></tr>'}
                                </tbody>
                                <tfoot class="table-light">
                                    <tr><th>Total Biaya</th><th class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(data.total_biaya)}</th></tr>
                                    <tr><th>Jumlah Bayar</th><th class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(data.jumlah_bayar)}</th></tr>
                                    <tr><th>Kembalian</th><th class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(data.kembalian)}</th></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                `;
                $('#detailContent').html(detailHTML);
            } else {
                $('#detailContent').html('<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>' + response.message + '</div>');
            }
        },
        error: function() {
            $('#detailContent').html('<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan saat memuat detail transaksi</div>');
        }
    });
}

function printStrukFromModal() {
    if (currentTransactionData.noRm && currentTransactionData.tanggal) {
        // Buka halaman print dalam tab/window baru
        window.open('<?= base_url('kasir/print-tagihan/') ?>' + currentTransactionData.noRm + '/' + currentTransactionData.tanggal, '_blank');
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Data transaksi tidak ditemukan'
        });
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Real-time search on input
    const cariPasienInput = document.getElementById('cariPasien');
    if (cariPasienInput) {
        cariPasienInput.addEventListener('input', function() {
            filterRiwayat();
        });
        
        // Enter key untuk trigger filter
        cariPasienInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterRiwayat();
            }
        });
    }
    
    // Auto filter saat tanggal berubah
    const tanggalDari = document.getElementById('tanggalDari');
    const tanggalSampai = document.getElementById('tanggalSampai');
    
    if (tanggalDari) {
        tanggalDari.addEventListener('change', function() {
            filterRiwayat();
        });
    }
    
    if (tanggalSampai) {
        tanggalSampai.addEventListener('change', function() {
            filterRiwayat();
        });
    }
});
</script>

<style>
/* Base Container & Layout */
.container-fluid {
    padding-left: 20px !important;
    padding-right: 20px !important;
}

/* Header Section */
.page-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 15px;
    padding: 25px 30px;
    margin-bottom: 25px;
    color: white;
    box-shadow: 0 8px 25px rgba(0,123,255,0.15);
}

.page-header h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.page-header p {
    margin: 8px 0 0 0;
    opacity: 0.9;
    font-size: 16px;
}

.date-badge {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 25px;
    padding: 12px 20px;
    font-weight: 600;
    font-size: 14px;
}

/* Cards */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.card-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    padding: 20px 25px;
    border-radius: 15px 15px 0 0 !important;
}

.card-header h5 {
    margin: 0;
    color: white;
    font-weight: 600;
    font-size: 18px;
}

.card-body {
    padding: 25px;
}

/* Filter Section */
.filter-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.filter-group {
    margin-bottom: 0;
}

.filter-label {
    display: block;
    color: #495057;
    font-weight: 600;
    font-size: 12px;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
    height: auto;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    outline: none;
}

.search-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
    z-index: 5;
}

.search-input {
    padding-left: 45px !important;
}

/* Summary Cards */
.summary-card {
    border: none;
    border-radius: 15px;
    background: white;
    transition: all 0.3s ease;
    height: 100%;
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.summary-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px auto;
    font-size: 28px;
    color: white;
}

.summary-title {
    font-size: 14px;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-value {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}

/* Table */
.table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.table {
    margin: 0;
    font-size: 14px;
}

.table thead th {
    background: #f8f9fa;
    border: none;
    padding: 18px 15px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #495057;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table tbody td {
    padding: 18px 15px;
    border-top: 1px solid #f0f0f0;
    vertical-align: middle;
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(0,123,255,0.03);
}

/* Badges */
.badge {
    font-size: 11px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 20px;
    letter-spacing: 0.3px;
}

.badge i {
    margin-right: 4px;
}

/* Buttons */
.btn {
    border-radius: 10px;
    font-weight: 500;
    font-size: 13px;
    padding: 10px 20px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
}

.btn-outline-primary {
    border-color: #007bff;
    color: #007bff;
}

.btn-outline-primary:hover {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
}

.btn-outline-success {
    border-color: #28a745;
    color: #28a745;
}

.btn-outline-success:hover {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Empty State */
.empty-state {
    padding: 60px 30px;
    text-align: center;
}

.empty-state i {
    font-size: 80px;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h6 {
    color: #6c757d;
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
    font-size: 14px;
    max-width: 400px;
    margin: 0 auto;
}

/* Modal */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
    border-radius: 15px 15px 0 0;
    padding: 20px 25px;
}

.modal-header .modal-title {
    font-weight: 600;
    font-size: 18px;
}

.modal-header .btn-close {
    background: none;
    border: none;
    color: white;
    opacity: 0.8;
    font-size: 24px;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    border: none;
    padding: 20px 25px;
    background: #f8f9fa;
    border-radius: 0 0 15px 15px;
}

/* Struk */
.struk {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 25px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.6;
    max-width: 350px;
    margin: 0 auto;
}

.struk-header {
    text-align: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px dashed #dee2e6;
}

.struk-header h6 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 5px;
    color: #2c3e50;
}

.struk-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    align-items: center;
}

.struk-row strong {
    color: #2c3e50;
}

.struk-divider {
    border-top: 1px dashed #dee2e6;
    margin: 15px 0;
    padding-top: 15px;
}

.struk-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px dashed #dee2e6;
    font-size: 12px;
    color: #6c757d;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
    
    .page-header {
        padding: 20px;
        text-align: center;
    }
    
    .page-header h2 {
        font-size: 24px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .filter-section {
        padding: 15px;
    }
    
    .summary-icon {
        width: 60px;
        height: 60px;
        font-size: 24px;
    }
    
    .summary-value {
        font-size: 20px;
    }
    
    .table thead th,
    .table tbody td {
        padding: 12px 8px;
        font-size: 12px;
    }
    
    .btn {
        padding: 8px 15px;
        font-size: 12px;
    }
    
    .btn-sm {
        padding: 5px 10px;
        font-size: 11px;
    }
}

@media (max-width: 576px) {
    .summary-card {
        margin-bottom: 15px;
    }
    
    .table-responsive {
        font-size: 11px;
    }
    
    .badge {
        font-size: 10px;
        padding: 4px 8px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

.summary-card:nth-child(1) { animation-delay: 0.1s; }
.summary-card:nth-child(2) { animation-delay: 0.2s; }
.summary-card:nth-child(3) { animation-delay: 0.3s; }
.summary-card:nth-child(4) { animation-delay: 0.4s; }

/* Utilities */
.text-primary { color: #007bff !important; }
.text-success { color: #28a745 !important; }
.text-warning { color: #ffc107 !important; }
.text-info { color: #17a2b8 !important; }
.text-muted { color: #6c757d !important; }

.bg-primary { background-color: #007bff !important; }
.bg-success { background-color: #28a745 !important; }
.bg-warning { background-color: #ffc107 !important; }
.bg-info { background-color: #17a2b8 !important; }

.fw-bold { font-weight: 700 !important; }
.fw-semibold { font-weight: 600 !important; }
</style>

<?= $this->endSection() ?>
