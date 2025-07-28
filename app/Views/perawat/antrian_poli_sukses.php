<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Berhasil Mendaftar Antrian Poli</h4>
                </div>
                <div class="card-body text-center">
                    <h5 class="mb-3">Nomor Antrian Poli Anda:</h5>
                    <div class="display-4 fw-bold text-primary mb-4">
                        <?= esc($_GET['no_antrian'] ?? '-') ?>
                    </div>
                    <p class="mb-4">Silakan tunggu di ruang poliklinik sesuai nomor antrian Anda.<br>Petugas akan memanggil sesuai urutan.</p>
                    <a href="<?= base_url('exportword/antrian-poli/' . esc($_GET['no_antrian'] ?? '-')) ?>" class="btn btn-success mb-2" target="_blank">
                        <i class="fas fa-file-word me-2"></i>Cetak Antrian
                    </a>
                    <br>
                    <a href="<?= base_url('perawat/antrian-pendaftaran') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Antrian Pendaftaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
