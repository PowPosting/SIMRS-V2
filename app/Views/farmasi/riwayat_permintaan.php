<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 fw-bold">📋 <?= $pageTitle ?></h2>
                    <p class="text-muted mb-0">Daftar riwayat permintaan obat yang telah diproses</p>
                </div>
                <div>
                    <span class="badge bg-info fs-6">
                        <i class="fas fa-calendar-check me-1"></i>
                        <?= date('d M Y') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" value="<?= $filters['tanggal_mulai'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="tanggal_akhir" value="<?= $filters['tanggal_akhir'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" <?= ($filters['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= ($filters['status'] ?? '') == 'processing' ? 'selected' : '' ?>>Diproses</option>
                                <option value="completed" <?= ($filters['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="<?= base_url('farmasi/riwayat-permintaan') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Permintaan Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list-ul text-primary me-2"></i>
                            Daftar Riwayat Permintaan
                        </h5>
                        <span class="badge bg-secondary">
                            Total: <?= count($riwayat_permintaan ?? []) ?> resep
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($riwayat_permintaan ?? [])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No. RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($riwayat_permintaan ?? []) as $index => $resep): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($resep['tanggal_resep'])) ?><br>
                                            <?= date('H:i', strtotime($resep['tanggal_resep'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary"><?= $resep['no_rm'] ?? '-' ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-semibold"><?= $resep['nama_pasien'] ?? '-' ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            Rp <?= number_format($resep['total_biaya'] ?? 0, 0, ',', '.') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $status = $resep['status'] ?? 'pending';
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
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('farmasi/detail-permintaan-obat/' . $resep['id']) ?>" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('farmasi/print-resep/' . $resep['id']) ?>" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Cetak Resep"
                                               target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-inbox display-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted">Belum Ada Riwayat Permintaan</h5>
                        <p class="text-muted">Riwayat permintaan obat akan muncul di sini setelah ada transaksi</p>
                        <a href="<?= base_url('farmasi/permintaan-obat') ?>" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i>
                            Lihat Permintaan Aktif
                        </a>
                    </div>
                    <?php endif; ?>
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

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
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
</style>
<?= $this->endSection() ?>
