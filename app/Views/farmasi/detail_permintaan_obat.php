<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <!-- Detail Permintaan Obat -->
    <div class="row">
        <div class="col-12">
            <!-- Informasi Pasien -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2"></i>
                        Informasi Pasien
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="30%"><strong>Nama Pasien:</strong></td>
                                    <td><?= $resep['nama_pasien'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>No. RM:</strong></td>
                                    <td><?= $resep['no_rm'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin:</strong></td>
                                    <td><?= ($resep['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : (($resep['jenis_kelamin'] ?? '') == 'P' ? 'Perempuan' : '-') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="30%"><strong>Umur:</strong></td>
                                    <td><?= $umur ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Dokter:</strong></td>
                                    <td><?= $resep['nama_dokter'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($resep['tanggal_resep'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Obat yang Dipesan -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-pills me-2"></i>
                            Daftar Obat yang Dipesan
                        </h5>
                        <span class="badge bg-light text-dark">
                            Total: <?= count($semua_resep) ?> item obat
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Nama Obat</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="15%">Harga Satuan</th>
                                    <th width="15%">Subtotal</th>
                                    <th width="25%">Instruksi</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($semua_resep as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div>
                                            <strong><?= $item['nama_obat_final'] ?></strong>
                                            <?php if (!empty($item['dosis'])): ?>
                                                <br><small class="text-muted">Dosis: <?= $item['dosis'] ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?= $item['jumlah'] ?></span>
                                        <small class="text-muted d-block"><?= $item['satuan_final'] ?></small>
                                    </td>
                                    <td>
                                        <?php if ($item['harga_jual'] > 0): ?>
                                            <span class="fw-bold text-success">
                                                Rp <?= number_format($item['harga_jual'], 0, ',', '.') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['subtotal'] > 0): ?>
                                            <span class="fw-bold text-success">
                                                Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= $item['instruksi'] ?? $item['cara_pakai'] ?? 'Sesuai petunjuk dokter' ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php
                                        $status = $item['status'] ?? 'pending';
                                        $statusClass = '';
                                        $statusText = '';
                                        
                                        switch ($status) {
                                            case 'pending':
                                                $statusClass = 'bg-warning text-dark';
                                                $statusText = 'Pending';
                                                break;
                                            case 'processing':
                                                $statusClass = 'bg-info';
                                                $statusText = 'Diproses';
                                                break;
                                            case 'completed':
                                                $statusClass = 'bg-success';
                                                $statusText = 'Selesai';
                                                break;
                                            default:
                                                $statusClass = 'bg-secondary';
                                                $statusText = ucfirst($status);
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Biaya:</strong></td>
                                    <td colspan="3">
                                        <strong class="text-success fs-5">
                                            Rp <?= number_format($total_biaya, 0, ',', '.') ?>
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="card shadow-sm mt-3">
                <div class="card-body text-center">
                    <a href="<?= base_url('farmasi/riwayat-permintaan') ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali ke Riwayat
                    </a>
                    <a href="<?= base_url('farmasi/print-struk-resep/' . $resep['id']) ?>" class="btn btn-success" target="_blank">
                        <i class="fas fa-print me-1"></i>
                        Cetak Struk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}

.badge {
    font-size: 0.75rem;
}

.fs-5 {
    font-size: 1.25rem;
}
</style>
<?= $this->endSection() ?>
