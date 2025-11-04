<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
.stat-card {
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}
.badge-expired {
    background-color: #fee;
    color: #c00;
    padding: 5px 10px;
    font-weight: 600;
}
.badge-warning-exp {
    background-color: #fff3cd;
    color: #856404;
    padding: 5px 10px;
    font-weight: 600;
}
</style>

<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?= number_format($totalResepHariIni) ?></h3>
                            <p class="mb-0">Total Resep Hari Ini</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-prescription"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?= number_format($totalObatKeluar) ?></h3>
                            <p class="mb-0">Obat Keluar Hari Ini</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">Rp <?= number_format($nilaiTransaksi, 0, ',', '.') ?></h3>
                            <p class="mb-0">Nilai Transaksi Hari Ini</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?= count($obatHampirExpired) ?></h3>
                            <p class="mb-0">Obat Hampir Expired</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="laporanTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pemakaian-tab" data-toggle="tab" href="#pemakaian" role="tab">
                        <i class="fas fa-chart-line mr-1"></i> Pemakaian Obat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="expired-tab" data-toggle="tab" href="#expired" role="tab">
                        <i class="fas fa-calendar-times mr-1"></i> Obat Hampir Expired
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content" id="laporanTabContent">
                <!-- Tab Pemakaian Obat -->
                <div class="tab-pane fade show active" id="pemakaian" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Tanggal Mulai:</label>
                            <input type="date" id="startDate" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Akhir:</label>
                            <input type="date" id="endDate" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label><br>
                            <button class="btn btn-primary" id="btnFilter">
                                <i class="fas fa-search mr-1"></i> Filter
                            </button>
                            <button class="btn btn-success" id="btnExport">
                                <i class="fas fa-file-excel mr-1"></i> Export
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablePemakaian">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Obat</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Total Transaksi</th>
                                    <th>Total Nilai</th>
                                </tr>
                            </thead>
                            <tbody id="bodyPemakaian">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td colspan="6" class="text-right">GRAND TOTAL:</td>
                                    <td id="grandTotal">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Tab Obat Expired -->
                <div class="tab-pane fade" id="expired" role="tabpanel">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle mr-2"></i>
                        Menampilkan obat yang akan expired dalam 3 bulan ke depan
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Obat</th>
                                    <th>Satuan</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Tanggal Expired</th>
                                    <th>Sisa Hari</th>
                                    <th>Nilai Kerugian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($obatHampirExpired)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-check-circle fa-3x mb-3 text-success d-block"></i>
                                            <h5>Tidak ada obat yang hampir expired</h5>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($obatHampirExpired as $index => $obat): 
                                        $expDate = new DateTime($obat['tanggal_expired']);
                                        $today = new DateTime();
                                        $sisaHari = $today->diff($expDate)->days;
                                        $nilaiKerugian = $obat['stok'] * $obat['harga_jual'];
                                    ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><strong><?= esc($obat['nama_obat']) ?></strong></td>
                                            <td><?= esc($obat['satuan']) ?></td>
                                            <td><span class="badge badge-info"><?= number_format($obat['stok']) ?></span></td>
                                            <td>Rp <?= number_format($obat['harga_jual'], 0, ',', '.') ?></td>
                                            <td>
                                                <span class="badge <?= $sisaHari <= 30 ? 'badge-expired' : 'badge-warning-exp' ?>">
                                                    <?= date('d/m/Y', strtotime($obat['tanggal_expired'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $sisaHari <= 30 ? 'badge-danger' : 'badge-warning' ?>">
                                                    <?= $sisaHari ?> hari
                                                </span>
                                            </td>
                                            <td class="text-danger font-weight-bold">
                                                Rp <?= number_format($nilaiKerugian, 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    loadPemakaian();
    
    $('#btnFilter').click(function() {
        loadPemakaian();
    });
    
    $('#btnExport').click(function() {
        const start = $('#startDate').val();
        const end = $('#endDate').val();
        window.open(`<?= base_url('farmasi/exportLaporanPemakaian') ?>?start_date=${start}&end_date=${end}`, '_blank');
    });
});

function loadPemakaian() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();
    
    $.ajax({
        url: '<?= base_url('farmasi/getLaporanPemakaian') ?>',
        type: 'GET',
        data: { start_date: startDate, end_date: endDate },
        success: function(response) {
            if (response.success) {
                renderPemakaian(response.data, response.grandTotal);
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Gagal memuat data laporan');
        }
    });
}

function renderPemakaian(data, grandTotal) {
    let html = '';
    
    if (data.length === 0) {
        html = `
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    <h5>Tidak ada data untuk periode ini</h5>
                </td>
            </tr>
        `;
    } else {
        data.forEach((row, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${row.nama_obat}</strong></td>
                    <td>${row.satuan}</td>
                    <td>Rp ${parseInt(row.harga).toLocaleString('id-ID')}</td>
                    <td><span class="badge badge-primary">${parseInt(row.total_jumlah).toLocaleString('id-ID')}</span></td>
                    <td><span class="badge badge-info">${parseInt(row.jumlah_transaksi).toLocaleString('id-ID')}</span></td>
                    <td class="font-weight-bold text-success">Rp ${parseInt(row.total_nilai).toLocaleString('id-ID')}</td>
                </tr>
            `;
        });
    }
    
    $('#bodyPemakaian').html(html);
    $('#grandTotal').text('Rp ' + parseInt(grandTotal).toLocaleString('id-ID'));
}

function showError(message) {
    alert(message);
}
</script>
<?= $this->endSection() ?>
