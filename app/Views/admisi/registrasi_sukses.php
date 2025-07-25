<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h3 class="mb-3">Pendaftaran Berhasil!</h3>
                    <div class="alert alert-success">
                        <h5>Informasi Pendaftaran:</h5>
                        <p class="mb-1">No. RM: <strong><?= $no_rm ?></strong></p>
                        <p class="mb-0">No. Antrian: <strong><?= $no_antrian ?></strong></p>
                    </div>
                    <p class="text-muted">Silakan menuju ke Nurse Station untuk pemeriksaan awal</p>
                    <hr>
                    <div class="mt-4">
                        <a href="<?= base_url('exportword/antrian/' . $no_antrian) ?>" class="btn btn-success mr-2" target="_blank">
                            <i class="fas fa-print mr-1"></i> Cetak Nomor Antrian
                        </a>
                        <a href="<?= base_url('admisi/registrasi-pasien') ?>" class="btn btn-outline-primary mr-2">
                            <i class="fas fa-user-plus mr-1"></i> Daftar Pasien Baru
                        </a>
                        <a href="<?= base_url('admisi/datapasien') ?>" class="btn btn-primary">
                            <i class="fas fa-list mr-1"></i> Lihat Daftar Pasien
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
