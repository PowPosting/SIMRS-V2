<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.hero-gradient {
    background: linear-gradient(135deg, #1EACA8ea 0%, #201F5A 100%);
    border-radius: 15px;
}
.stats-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}
.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
}
.quick-action-btn {
    transition: all 0.3s ease;
    border-radius: 10px;
}
.quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Statistics Card Enhancements */
.stats-card .card-body {
    padding: 1.25rem;
}

.stats-card .text-xs {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.05em;
}

.stats-card .h5 {
    font-size: 1.75rem;
    font-weight: 700;
}

/* Color variations for text */
.text-primary {
    color: #4e73df !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-info {
    color: #36b9cc !important;
}

.text-warning {
    color: #f6c23e !important;
}

/* Shadow enhancements */
.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

/* Card hover animation */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
    100% {
        transform: scale(1);
    }
}

.stats-card:hover .fas {
    animation: pulse 1s ease-in-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-card .h5 {
        font-size: 1.5rem;
    }
    
    .stats-card .text-xs {
        font-size: 0.65rem;
    }
}

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Welcome Hero Section -->
<div class="row">
    <div class="col-12 px-4 mt-4">
        <div class="card border-0 shadow-sm mb-4 hero-gradient">
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="text-white">
                            <h1 class="display-4 fw-bold mb-3 text-white">
                                Selamat Datang di SIMRS 
                                <span class="text-warning">HAMORI</span>
                            </h1>
                            <p class="lead mb-4 text-white">
                                Sistem Informasi Manajemen Rumah Sakit yang terintegrasi untuk memberikan 
                                pelayanan kesehatan terbaik bagi pasien dan masyarakat.
                            </p>
                            <div class="row text-center">
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-md fa-2x text-warning me-3"></i>
                                        <div>
                                            <h5 class="mb-0 text-white">Dokter Profesional</h5>
                                            <small class="text-white-50">Tim medis berpengalaman</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-heartbeat fa-2x text-warning me-3"></i>
                                        <div>
                                            <h5 class="mb-0 text-white">Pelayanan 24/7</h5>
                                            <small class="text-white-50">Siap melayani sepanjang waktu</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-shield-alt fa-2x text-warning me-3"></i>
                                        <div>
                                            <h5 class="mb-0 text-white">Fasilitas Lengkap</h5>
                                            <small class="text-white-50">Teknologi medis terkini</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <img src="<?= base_url('img/hero.png') ?>" 
                             alt="Medical Illustration" 
                             class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards Section -->
<div class="row px-4 mb-4">
    <div class="col-12 mb-3">
        <h4 class="text-gray-800 fw-bold">
            <i class="fas fa-chart-bar text-primary me-2"></i>
            Statistik Sistem
        </h4>
        <p class="text-muted">Ringkasan data sistem rumah sakit</p>
    </div>
    
    <!-- Row 1 - Primary Stats -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pasien</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_pasien']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Pasien Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['pasien_hari_ini']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Dokter</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_dokter']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-md fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Antrian Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['antrian_hari_ini']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2 - Secondary Stats -->
<div class="row px-4 mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Pemeriksaan Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['pemeriksaan_bulan_ini']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Obat Tersedia</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['obat_tersedia']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pasien Aktif (30 Hari)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['pasien_aktif']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-heartbeat fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Staff</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_staff']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
