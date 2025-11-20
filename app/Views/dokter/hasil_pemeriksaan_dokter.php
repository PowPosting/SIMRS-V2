<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4" style="max-width: 1200px;">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-clipboard2-check me-2"></i>Daftar Pasien Sudah Diperiksa</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>No. RM</th>
                            <th>Nama Pasien</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list_pemeriksaan) && is_array($list_pemeriksaan)): ?>
                            <?php $no=1; foreach ($list_pemeriksaan as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><span class="badge bg-light text-primary"><?= esc($row['no_rekam_medis']) ?></span></td>
                                    <td>
                                        <a href="<?= base_url('dokter/detailpemeriksaanpasien/' . $row['id_pemeriksaan']) ?>" class="text-primary fw-bold">
                                            <?= esc($row['nama_lengkap']) ?>
                                        </a>
                                    </td>
                                    <td><?= esc($row['poli'] ?? '-') ?></td>
                                    <td><?= esc($row['dokter'] ?? '-') ?></td>
                                    <td><?= esc($row['waktu_pemeriksaan'] ?? '-') ?></td>
                                    <td>
                                        <a href="<?= base_url('dokter/detailpemeriksaanpasien/' . $row['id_pemeriksaan']) ?>" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada pasien yang diperiksa</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
