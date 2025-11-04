<!-- Informasi Pasien -->
<div class="mb-4">
    <div class="d-flex align-items-center mb-3">
        <div class="bg-primary text-white rounded-circle p-2 me-3">
            <i class="fas fa-user"></i>
        </div>
        <div>
            <h5 class="mb-0 text-primary"><?= esc($pasien['nama_pasien'] ?? '-') ?></h5>
            <small class="text-muted"><?= esc($pasien['no_rm'] ?? '-') ?> • <?= esc($pasien['nama_dokter'] ?? 'Dr. -') ?></small>
        </div>
        <div class="ms-auto">
            <span class="badge bg-warning">Belum Dibayar</span>
        </div>
    </div>
    
    <div class="row text-sm">
        <div class="col-6">
            <span class="text-muted">Tanggal:</span> <?= date('d M Y', strtotime($pasien['tanggal_resep'] ?? date('Y-m-d'))) ?>
        </div>
        <div class="col-6">
            <span class="text-muted">Gender:</span> <?= ($pasien['jenis_kelamin'] ?? '-') === 'L' ? 'Laki-laki' : (($pasien['jenis_kelamin'] ?? '-') === 'P' ? 'Perempuan' : '-') ?>
        </div>
    </div>
</div>

<!-- Rincian Obat -->
<?php if (!empty($resep_list)): ?>
<div class="mb-4">
    <h6 class="text-success mb-3">
        <i class="fas fa-pills me-2"></i>Obat & Farmasi
    </h6>
    
    <div class="bg-light rounded p-3">
        <?php 
        $no = 1; 
        $total_obat = 0;
        ?>
        <?php foreach ($resep_list as $resep): ?>
            <?php 
            $subtotal = ($resep['harga_jual'] ?? 0) * ($resep['jumlah'] ?? 0);
            $total_obat += $subtotal;
            ?>
            <div class="d-flex justify-content-between align-items-center py-2 <?= $no > 1 ? 'border-top' : '' ?>">
                <div>
                    <div class="fw-bold"><?= esc($resep['nama_obat']) ?></div>
                    <small class="text-muted"><?= esc($resep['jumlah'] ?? 0) ?> <?= esc($resep['satuan'] ?? 'pcs') ?> × Rp <?= number_format($resep['harga_jual'] ?? 0, 0, ',', '.') ?></small>
                </div>
                <div class="text-end">
                    <div class="fw-bold">Rp <?= number_format($subtotal ?? 0, 0, ',', '.') ?></div>
                </div>
            </div>
            <?php $no++; ?>
        <?php endforeach; ?>
        
        <div class="border-top pt-2 mt-2">
            <div class="d-flex justify-content-between">
                <span class="fw-bold text-success">Subtotal Obat:</span>
                <span class="fw-bold text-success">Rp <?= number_format($total_obat ?? 0, 0, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<!-- Jika tidak ada resep obat -->
<div class="mb-4">
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="fas fa-info-circle me-3 fa-2x"></i>
        <div>
            <strong>Tidak Ada Resep Obat</strong><br>
            <small>Pasien tidak mendapatkan resep obat dari dokter. Hanya biaya konsultasi dan administrasi.</small>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Biaya Layanan -->
<div class="mb-4">
    <h6 class="text-info mb-3">
        <i class="fas fa-receipt me-2"></i>Biaya Layanan
    </h6>
    
    <div class="bg-light rounded p-3">
        <div class="d-flex justify-content-between py-1">
            <span>Administrasi</span>
            <span>Rp <?= number_format($biaya_layanan['administrasi'] ?? 10000, 0, ',', '.') ?></span>
        </div>
        <div class="d-flex justify-content-between py-1">
            <span>Konsultasi Dokter</span>
            <span>Rp <?= number_format($biaya_layanan['konsultasi'] ?? 50000, 0, ',', '.') ?></span>
        </div>
    </div>
</div>

<!-- Total & Action -->
<div class="border-top pt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Total Tagihan:</h5>
        <h4 class="text-primary mb-0 fw-bold">Rp <?= number_format($total_tagihan ?? 0, 0, ',', '.') ?></h4>
    </div>
    
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="button" class="btn btn-outline-secondary" onclick="cetakStruk()">
            <i class="fas fa-print me-1"></i>Cetak Struk
        </button>
        <button type="button" class="btn btn-success" onclick="bayarSekarang()">
            <i class="fas fa-credit-card me-1"></i>Bayar Sekarang
        </button>
    </div>
    
    <small class="text-muted d-block mt-2">
        <i class="fas fa-info-circle me-1"></i>
        Pembayaran dapat dilakukan tunai, transfer, atau kartu debit
    </small>
</div>

<script>
function bayarSekarang() {
    // Tutup modal dan buka modal pembayaran
    $('#detailModal').modal('hide');
    $('#modalDetailTagihan').modal('hide');
    
    const idPasien = '<?= esc($pasien['no_rm'] ?? '') ?>';
    const tanggal = '<?= date('Y-m-d', strtotime($pasien['tanggal_resep'] ?? date('Y-m-d'))) ?>';
    const totalTagihan = <?= $total_tagihan ?? 0 ?>;
    
    // Panggil function bayarTagihan yang ada di parent page
    if (typeof bayarTagihan === 'function') {
        bayarTagihan(idPasien, tanggal, totalTagihan);
    }
}

function cetakStruk() {
    const idPasien = '<?= esc($pasien['no_rm'] ?? '') ?>';
    const tanggal = '<?= date('Y-m-d', strtotime($pasien['tanggal_resep'] ?? date('Y-m-d'))) ?>';
    
    // Buka halaman print dalam tab/window baru
    window.open('<?= base_url('kasir/print-tagihan/') ?>' + idPasien + '/' + tanggal, '_blank');
}
</script>

<style>
.text-sm {
    font-size: 0.875rem;
}

.rounded-circle {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.border-top {
    border-top: 1px solid #dee2e6 !important;
}

.fw-bold {
    font-weight: 600 !important;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

@media print {
    .btn { display: none !important; }
    body { font-size: 12px; }
}
</style>
