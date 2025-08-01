<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Success Animation Card -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-white"></i>
                    </div>
                    <h4 class="mb-0 font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Pendaftaran Antrian Berhasil!
                    </h4>
                    <p class="mb-0 mt-2 text-white-50">Silakan catat nomor antrian Anda</p>
                </div>
                
                <div class="card-body text-center py-5">
                    <!-- Queue Number Display -->
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-ticket-alt text-primary mr-2"></i>
                            Nomor Antrian Poli Anda:
                        </h5>
                        <div class="queue-number-display mb-4">
                            <div class="h1 font-weight-bold text-primary mb-2" style="font-size: 3rem; text-shadow: 1px 1px 3px rgba(0,123,255,0.2);">
                                <?= esc($_GET['no_antrian'] ?? '-') ?>
                            </div>
                            <div class="badge badge-primary badge-pill px-3 py-1">
                                <i class="fas fa-clock mr-1"></i>
                                <?= date('d/m/Y H:i') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="alert alert-info border-left-primary mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x text-primary mr-3"></i>
                            <div class="text-left">
                                <h6 class="font-weight-bold mb-1">Petunjuk:</h6>
                                <p class="mb-0">
                                    Silakan tunggu di ruang poliklinik sesuai nomor antrian Anda.<br>
                                    <strong>Petugas akan memanggil sesuai urutan.</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row justify-content-center">
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('exportword/antrian-poli/' . esc($_GET['no_antrian'] ?? '-')) ?>" 
                               class="btn btn-success btn-lg btn-block shadow-sm" target="_blank">
                                <i class="fas fa-file-word mr-2"></i>
                                Cetak Antrian
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('perawat/antrian-pendaftaran') ?>" 
                               class="btn btn-outline-primary btn-lg btn-block">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Antrian
                            </a>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-hospital mr-1"></i>
                            Sistem Informasi Manajemen Rumah Sakit (SIMRS)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.border-left-primary {
    border-left: 4px solid #007bff;
}

.queue-number-display {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 15px;
    border: 2px dashed #007bff;
    position: relative;
}

.queue-number-display::before {
    content: '';
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    background: linear-gradient(45deg, #007bff, #28a745);
    border-radius: 12px;
    z-index: -1;
    opacity: 0.08;
}

.card {
    animation: slideInUp 0.5s ease-out;
}

@keyframes slideInUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

@media (max-width: 768px) {
    .h1 {
        font-size: 2rem !important;
    }
    
    .queue-number-display {
        padding: 12px;
    }
}
</style>

<?= $this->endSection() ?>
