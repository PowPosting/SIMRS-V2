<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4" style="max-width:1400px;">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="text-primary fw-bold mb-1">
                        <i class="bi bi-cash-coin me-2"></i>Kasir & Pembayaran
                    </h2>
                    <p class="text-muted mb-0">Proses pembayaran seluruh tagihan pasien (obat, administrasi, dokter, dll)</p>
                </div>
                <div class="text-end">
                    <div class="badge bg-primary fs-6 px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i><?= date('d M Y') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-gradient-primary text-white p-4">
            <div class="row align-items-center">
                <div class="col-md-6 col-12 mb-3 mb-md-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filter & Pencarian</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label-dark">Cari Pasien</label>
                        <div class="search-wrapper-dark">
    
                            <input type="text" class="form-control search-input-dark" id="cariPasien" placeholder="Nama atau No. RM">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label-dark">Tanggal</label>
                        <input type="date" class="form-control filter-input-dark" id="tanggal" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label-dark">Status Pembayaran</label>
                        <select class="form-select filter-input-dark" id="statusPembayaran">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="sudah_bayar">Sudah Bayar</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-primary flex-fill" onclick="cariTagihan()">
                            <i class="bi bi-search me-1"></i>Cari
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetFilter()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Tagihan -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-gradient-primary text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-receipt me-2"></i>Daftar Tagihan Pasien
                </h5>
                <span class="badge bg-light text-primary fs-6 px-3 py-2" id="totalTagihan">
                    Total: 0 tagihan
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="tagihanTable">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No. RM</th>
                            <th>Nama Pasien</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Total Tagihan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tagihanBody">
                        <?php if (!empty($tagihan_list)): ?>
                            <?php foreach ($tagihan_list as $tagihan): ?>
                                <tr>
                                    <td class="text-center fw-bold text-primary"><?= esc($tagihan['no_rm']) ?></td>
                                    <td>
                                        <div class="fw-bold"><?= esc($tagihan['nama_pasien']) ?></div>
                                        <small class="text-muted">RM: <?= esc($tagihan['no_rm']) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-bold text-primary"><?= date('d/m/Y', strtotime($tagihan['tanggal'])) ?></div>
                                
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-bold text-success fs-6">Rp <?= number_format($tagihan['total_tagihan'], 0, ',', '.') ?></div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="bi bi-clock me-1"></i>Belum Bayar
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-info btn-sm px-3" onclick="lihatDetail('<?= esc($tagihan['id_pasien']) ?>', '<?= esc($tagihan['tanggal']) ?>')" title="Lihat Detail">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </button>
                                            <button class="btn btn-success btn-sm px-3" onclick="bayarTagihan('<?= esc($tagihan['id_pasien']) ?>', '<?= esc($tagihan['tanggal']) ?>', <?= (int)$tagihan['total_tagihan'] ?>)" title="Proses Pembayaran">
                                                <i class="bi bi-credit-card me-1"></i>Bayar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-receipt" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <h6 class="mt-3 mb-2">Belum Ada Tagihan</h6>
                                        <p class="text-muted small mb-0">Tagihan obat akan muncul di sini setelah ada resep yang selesai diproses farmasi</p>
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
    <!-- Quick Actions -->
    <div class="row g-3 mt-2">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm action-card">
                <div class="card-body text-center p-4">
                    <div class="action-icon bg-primary text-white mb-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="card-title mb-2">Riwayat Pembayaran</h6>
                    <p class="card-text text-muted small mb-3">Lihat history pembayaran pasien</p>
                    <a href="<?= base_url('kasir/riwayat-pembayaran') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm action-card">
                <div class="card-body text-center p-4">
                    <div class="action-icon bg-success text-white mb-3">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h6 class="card-title mb-2">Laporan Harian</h6>
                    <p class="card-text text-muted small mb-3">Laporan pendapatan hari ini</p>
                    <button class="btn btn-outline-success btn-sm" onclick="lihatLaporan()">
                        <i class="bi bi-file-earmark-text me-1"></i>Lihat Laporan
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm action-card">
                <div class="card-body text-center p-4">
                    <div class="action-icon bg-info text-white mb-3">
                        <i class="bi bi-printer"></i>
                    </div>
                    <h6 class="card-title mb-2">Print Struk</h6>
                    <p class="card-text text-muted small mb-3">Cetak ulang struk pembayaran</p>
                    <button class="btn btn-outline-info btn-sm" onclick="printStruk()">
                        <i class="bi bi-printer me-1"></i>Print Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal Detail Tagihan -->
<div class="modal fade" id="modalDetailTagihan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice-dollar"></i> Detail Tagihan Pasien
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalDetailBody">
                <!-- Detail tagihan akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-credit-card"></i> Proses Pembayaran
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Patient Info Card -->
                <div class="patient-info-card mb-4">
                    <div class="d-flex align-items-center">
                        <div class="patient-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="patient-details">
                            <h6 class="patient-name" id="namaPasienPembayaran">-</h6>
                            <small class="patient-rm" id="rmPasienPembayaran">-</small>
                        </div>
                        <div class="ml-auto">
                            <span class="status-badge">Proses Bayar</span>
                        </div>
                    </div>
                </div>

                <!-- Total Tagihan -->
                <div class="total-card mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="total-label">Total Tagihan</span>
                        <h4 class="total-amount" id="totalTagihanDisplay">Rp 0</h4>
                    </div>
                </div>

                <!-- Payment Form -->
                <form id="formPembayaran">
                    <div class="form-group mb-3">
                        <label class="form-label">Jumlah Bayar</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="jumlahBayar" placeholder="0" required>
                        </div>
                        <small class="form-text text-muted">Masukkan jumlah uang yang diterima</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-control form-control-lg" id="metodeBayar" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="cash">💵 Cash/Tunai</option>
                        </select>
                    </div>

                    <!-- Kembalian Card -->
                    <div class="kembalian-card mb-3" id="kembalianSection" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="kembalian-label">Kembalian</span>
                            <h5 class="kembalian-amount" id="kembalianDisplay">Rp 0</h5>
                        </div>
                    </div>

                    <!-- Alert -->
                    <div class="alert alert-warning d-none" id="alertKurangBayar">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Jumlah bayar kurang!</strong> Silakan masukkan jumlah yang sesuai.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <button type="button" class="btn btn-success" id="btnProsesPembayaran" onclick="prosesPembayaran()" disabled>
                    <i class="fas fa-check mr-1"></i>Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Update total tagihan saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    updateTotalTagihan();
});

function cariTagihan() {
    const searchText = document.getElementById('cariPasien').value.toLowerCase();
    const tanggal = document.getElementById('tanggal').value;
    const status = document.getElementById('statusPembayaran').value;
    
    const rows = document.querySelectorAll('#tagihanTable tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        // Skip empty state row
        if (row.querySelector('.empty-state')) {
            row.style.display = 'none';
            return;
        }
        
        const noRM = row.cells[0].textContent.toLowerCase();
        const namaPasien = row.cells[1].textContent.toLowerCase();
        const tanggalRow = row.cells[2].querySelector('.fw-bold').textContent;
        const statusBadge = row.cells[4].querySelector('.badge').textContent.toLowerCase();
        
        // Filter berdasarkan pencarian
        const matchSearch = searchText === '' || noRM.includes(searchText) || namaPasien.includes(searchText);
        
        // Filter berdasarkan tanggal
        let matchTanggal = true;
        if (tanggal) {
            const [day, month, year] = tanggalRow.split('/');
            const rowDate = `${year}-${month}-${day}`;
            matchTanggal = rowDate === tanggal;
        }
        
        // Filter berdasarkan status
        let matchStatus = true;
        if (status === 'belum_bayar') {
            matchStatus = statusBadge.includes('belum bayar');
        } else if (status === 'sudah_bayar') {
            matchStatus = statusBadge.includes('sudah bayar') || statusBadge.includes('lunas');
        }
        
        // Tampilkan atau sembunyikan baris
        if (matchSearch && matchTanggal && matchStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update total count
    updateTotalTagihan();
    
    // Tampilkan pesan jika tidak ada hasil
    if (visibleCount === 0) {
        const tbody = document.querySelector('#tagihanTable tbody');
        const emptyRow = tbody.querySelector('.empty-state')?.closest('tr');
        if (emptyRow) {
            emptyRow.style.display = '';
            emptyRow.querySelector('h6').textContent = 'Tidak Ada Hasil';
            emptyRow.querySelector('p').textContent = 'Tidak ada tagihan yang sesuai dengan filter pencarian';
        }
    }
}

function resetFilter() {
    document.getElementById('cariPasien').value = '';
    document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
    document.getElementById('statusPembayaran').value = '';
    
    // Tampilkan semua baris
    const rows = document.querySelectorAll('#tagihanTable tbody tr');
    rows.forEach(row => {
        if (!row.querySelector('.empty-state')) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updateTotalTagihan();
}

function updateTotalTagihan() {
    const rows = document.querySelectorAll('#tagihanTable tbody tr');
    let count = 0;
    
    rows.forEach(row => {
        if (row.style.display !== 'none' && !row.querySelector('.empty-state')) {
            count++;
        }
    });
    
    document.getElementById('totalTagihan').textContent = `Total: ${count} tagihan`;
}

function lihatDetail(idPasien, tanggal) {
    // Load detail tagihan via AJAX
    $('#modalDetailBody').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#modalDetailTagihan').modal('show');
    
    // AJAX call to get detail
    $.get('<?= base_url('kasir/detail-tagihan') ?>/' + idPasien + '/' + tanggal)
        .done(function(data) {
            $('#modalDetailBody').html(data);
        })
        .fail(function() {
            $('#modalDetailBody').html('<div class="alert alert-danger">Error loading data</div>');
        });
}

function bayarTagihan(idPasien, tanggal, totalTagihan) {
    // Set data pasien
    document.getElementById('rmPasienPembayaran').textContent = idPasien;
    document.getElementById('totalTagihanDisplay').textContent = 'Rp ' + number_format(totalTagihan);
    
    // Reset form
    document.getElementById('jumlahBayar').value = '';
    document.getElementById('metodeBayar').value = '';
    document.getElementById('kembalianSection').style.display = 'none';
    document.getElementById('alertKurangBayar').classList.add('d-none');
    document.getElementById('btnProsesPembayaran').disabled = true;
    
    // Simpan data untuk proses pembayaran
    window.currentPayment = {
        idPasien: idPasien,
        tanggal: tanggal,
        totalTagihan: totalTagihan
    };
    
    // Cari nama pasien dari tabel
    const rows = document.querySelectorAll('#tagihanTable tbody tr');
    rows.forEach(row => {
        const rmCell = row.cells[0].textContent;
        if (rmCell === idPasien) {
            const namaPasien = row.cells[1].textContent;
            document.getElementById('namaPasienPembayaran').textContent = namaPasien;
        }
    });
    
    $('#modalPembayaran').modal('show');
}

function prosesPembayaran() {
    const formData = {
        idPasien: window.currentPayment.idPasien,
        tanggal: window.currentPayment.tanggal,
        totalTagihan: window.currentPayment.totalTagihan,
        jumlahBayar: parseFloat(document.getElementById('jumlahBayar').value),
        metodeBayar: document.getElementById('metodeBayar').value
    };
    
    // Validasi
    if (!formData.jumlahBayar || formData.jumlahBayar < formData.totalTagihan) {
        document.getElementById('alertKurangBayar').classList.remove('d-none');
        return;
    }
    
    if (!formData.metodeBayar) {
        Swal.fire({
            icon: 'warning',
            title: 'Metode Pembayaran',
            text: 'Silakan pilih metode pembayaran terlebih dahulu'
        });
        return;
    }
    
    // Disable button dan show loading
    const btn = document.getElementById('btnProsesPembayaran');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...';
    
    // AJAX call to process payment dengan timeout yang lebih pendek
    $.ajax({
        url: '<?= base_url('kasir/proses-pembayaran') ?>',
        type: 'POST',
        data: formData,
        timeout: 10000, // 10 detik timeout
        dataType: 'json'
    })
    .done(function(response) {
        if (response.success) {
            $('#modalPembayaran').modal('hide');
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil!',
                text: 'Tagihan pasien telah berhasil dibayar',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                // Update UI atau refresh halaman
                location.reload();
            });
        } else {
            throw new Error(response.message || 'Gagal memproses pembayaran');
        }
    })
    .fail(function(xhr, status, error) {
        let errorMessage = 'Terjadi kesalahan sistem';
        
        if (status === 'timeout') {
            errorMessage = 'Koneksi timeout. Silakan coba lagi.';
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        } else if (error) {
            errorMessage = error;
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memproses Pembayaran',
            text: errorMessage,
            confirmButtonText: 'Coba Lagi'
        });
    })
    .always(function() {
        // Reset button
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function lihatLaporan() {
    // Implementasi laporan
    console.log('Membuka laporan...');
}

function printStruk() {
    // Implementasi print struk
    console.log('Print struk...');
}

// Helper function
function number_format(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Auto calculate kembalian dan validasi
document.getElementById('jumlahBayar').addEventListener('input', function() {
    const totalTagihan = window.currentPayment ? window.currentPayment.totalTagihan : 0;
    const jumlahBayar = parseFloat(this.value) || 0;
    const kembalian = jumlahBayar - totalTagihan;
    
    const kembalianSection = document.getElementById('kembalianSection');
    const kembalianDisplay = document.getElementById('kembalianDisplay');
    const alertKurangBayar = document.getElementById('alertKurangBayar');
    const btnProses = document.getElementById('btnProsesPembayaran');
    
    if (jumlahBayar > 0) {
        if (kembalian >= 0) {
            // Cukup atau lebih
            kembalianDisplay.textContent = 'Rp ' + number_format(kembalian);
            kembalianSection.style.display = 'block';
            alertKurangBayar.classList.add('d-none');
            
            // Enable button jika metode pembayaran sudah dipilih
            const metodeBayar = document.getElementById('metodeBayar').value;
            btnProses.disabled = !metodeBayar;
        } else {
            // Kurang bayar
            kembalianSection.style.display = 'none';
            alertKurangBayar.classList.remove('d-none');
            btnProses.disabled = true;
        }
    } else {
        kembalianSection.style.display = 'none';
        alertKurangBayar.classList.add('d-none');
        btnProses.disabled = true;
    }
});

// Validasi metode pembayaran
document.getElementById('metodeBayar').addEventListener('change', function() {
    const jumlahBayar = parseFloat(document.getElementById('jumlahBayar').value) || 0;
    const totalTagihan = window.currentPayment ? window.currentPayment.totalTagihan : 0;
    const btnProses = document.getElementById('btnProsesPembayaran');
    
    // Enable button jika jumlah bayar cukup dan metode pembayaran dipilih
    btnProses.disabled = !(this.value && jumlahBayar >= totalTagihan);
});

// Real-time search saat mengetik
document.getElementById('cariPasien').addEventListener('input', function() {
    cariTagihan();
});

// Filter saat tanggal atau status berubah
document.getElementById('tanggal').addEventListener('change', function() {
    cariTagihan();
});

document.getElementById('statusPembayaran').addEventListener('change', function() {
    cariTagihan();
});

// Enter key untuk search
document.getElementById('cariPasien').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        cariTagihan();
    }
});
</script>

<style>
/* Header styling improvements */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.card-header {
    border-bottom: none;
    border-radius: 12px 12px 0 0 !important;
}

.card {
    border-radius: 12px;
    border: none;
}

/* Filter styling improvements - Dark theme for body */
.filter-group {
    margin-bottom: 0;
}

.filter-label-dark {
    display: block;
    color: #495057;
    font-weight: 600;
    font-size: 13px;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-input-dark, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    color: #495057;
    font-size: 14px;
    padding: 10px 12px;
    transition: all 0.3s ease;
    background-color: #fff;
}

.filter-input-dark:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    outline: none;
}

.search-wrapper-dark {
    position: relative;
}

.search-input-dark {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    color: #495057;
    font-size: 14px;
    padding: 10px 12px 10px 40px;
    transition: all 0.3s ease;
    background-color: #fff;
}

.search-input-dark:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    outline: none;
}

.search-input-dark::placeholder {
    color: rgba(108, 117, 125, 0.7);
}

.search-icon-dark {
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
    padding: 15px 12px;
    border-color: #e9ecef;
}

.table thead th {
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    background-color: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transition: background-color 0.15s ease-in-out;
}

/* Empty state styling */
.empty-state {
    padding: 2rem 1rem;
}

.empty-state i {
    color: #6c757d;
}

.empty-state h6 {
    color: #495057;
    font-weight: 600;
}

/* Badge styling */
.badge {
    font-size: 12px;
    font-weight: 500;
    border-radius: 6px;
    letter-spacing: 0.25px;
}

/* Button styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    font-size: 13px;
    transition: all 0.2s ease;
    border-width: 2px;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-sm {
    padding: 8px 16px;
    font-size: 12px;
}

/* Action cards styling */
.action-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border-radius: 12px;
}

.action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 24px;
}

.action-card .card-title {
    font-weight: 600;
    color: #2c3e50;
}

.action-card .card-text {
    font-size: 13px;
    line-height: 1.4;
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
    
    .filter-group {
        margin-bottom: 15px;
    }
    
    .filter-group:last-child {
        margin-bottom: 0;
    }
    
    .table th, .table td {
        padding: 10px 8px;
        font-size: 13px;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 8px !important;
    }
}

/* Modal Pembayaran Styling - Enhanced */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 1rem 1.5rem;
}

.modal-header .modal-title {
    font-weight: 600;
    font-size: 1.1rem;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
    font-size: 1.5rem;
}

.modal-header .close:hover {
    color: white;
    opacity: 1;
}

.modal-body {
    padding: 1.5rem;
}

/* Patient Info Card */
.patient-info-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
}

.patient-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    margin-right: 12px;
}

.patient-details {
    flex: 1;
}

.patient-name {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
    font-size: 1rem;
}

.patient-rm {
    color: #6c757d;
    font-size: 0.85rem;
}

.status-badge {
    background: #ffc107;
    color: #212529;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Total Card */
.total-card {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-radius: 8px;
    padding: 1.2rem;
}

.total-label {
    font-size: 0.95rem;
    opacity: 0.9;
}

.total-amount {
    margin: 0;
    font-weight: 700;
    font-size: 1.4rem;
}

/* Kembalian Card */
.kembalian-card {
    background: #e8f5e8;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 1rem;
}

.kembalian-label {
    color: #155724;
    font-weight: 600;
}

.kembalian-amount {
    margin: 0;
    color: #155724;
    font-weight: 700;
}

/* Form Styling */
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
    font-size: 0.9rem;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    min-height: 45px;
    line-height: 1.5;
}

.form-control-lg {
    min-height: 50px;
    padding: 0.8rem 1rem;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
    outline: none;
}

/* Dropdown specific styling */
select.form-control {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.25em 1.25em;
    padding-right: 2.5rem;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.input-group-text {
    background: #e9ecef;
    border: 2px solid #e9ecef;
    border-right: none;
    font-weight: 600;
    color: #495057;
    border-radius: 8px 0 0 8px;
}

.input-group .form-control {
    border-left: none;
    border-radius: 0 8px 8px 0;
}

/* Alert */
.alert {
    border-radius: 8px;
    border: none;
    padding: 0.8rem 1rem;
}

.alert-warning {
    background: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
}

/* Utility classes */
.me-1 { margin-right: 0.25rem !important; }
.me-2 { margin-right: 0.5rem !important; }
.fw-bold { font-weight: 700 !important; }
.fs-6 { font-size: 1rem !important; }
</style>

<?= $this->endSection() ?>
