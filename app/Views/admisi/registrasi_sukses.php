<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
.card {
    padding: 2rem;
    margin-top: 30px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}    
.btn-cetak {
    background-color: #343434ff;
    color: #fff;
}
.btn-cetak:hover {
    background-color: #0e0e0eff;
    color: #fff;
}

.btn-daftar {
    background-color: #28a745;
    color: #fff;
}
.btn-daftar:hover {
    background-color: #24933eff;
    color: #fff;
}

.btn-lihat {
    background-color: #00438a;
    color: #fff;
}
.btn-lihat:hover {
    background-color: #00356dff;
    color: #fff;
}
.mb-1, .mb-0 {
    color: #212121ff !important;
}
.card-body {
    padding: 2rem;
    margin-top: 20px;
    shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}
.h5 {
    color: #212121ff !important;
}   
</style>
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
                    <p class="text-muted">Silakan menuju ke  Ruang Perawat untuk pemeriksaan awal</p>
                    <hr>
                    <div class="mt-4">
                        <a href="<?= base_url('exportword/antrian/' . $no_antrian) ?>" class="btn btn-cetak" target="_blank">
                            <i class="fas fa-print mr-1"></i> Cetak Nomor Antrian
                        </a>
                        <a href="<?= base_url('admisi/registrasi-pasien') ?>" class="btn btn-daftar">
                            <i class="fas fa-user-plus mr-1"></i> Daftar Pasien Baru
                        </a>
                        <a href="<?= base_url('admisi/datapasien') ?>" class="btn btn-lihat">
                            <i class="fas fa-list mr-1"></i> Lihat Daftar Pasien
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
