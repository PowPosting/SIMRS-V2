<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="h3 mb-4">Dashboard Administrator</h2>
        </div>
    </div>

    <!-- Statistik Sistem -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white shadow-soft" style="background: var(--soft-blue);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">Total Users</h5>
                            <h2 class="mb-0"><?= $stats['total_users'] ?? 0 ?></h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white shadow-soft" style="background: var(--soft-green);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">Active Users</h5>
                            <h2 class="mb-0"><?= $stats['active_users'] ?? 0 ?></h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-user-check fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white shadow-soft" style="background: var(--soft-orange);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">Total Dokter</h5>
                            <h2 class="mb-0"><?= $stats['total_doctors'] ?? 0 ?></h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-stethoscope fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white shadow-soft" style="background: var(--soft-cyan);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">Total Perawat</h5>
                            <h2 class="mb-0"><?= $stats['total_nurses'] ?? 0 ?></h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-heartbeat fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white shadow-soft" style="background: var(--soft-purple);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">Total Pasien</h5>
                            <h2 class="mb-0"><?= $stats['total_pasien'] ?? 0 ?></h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-profile fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white shadow-soft" style="background: var(--soft-red);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">Total Antrian</h5>
                            <h2 class="mb-0"><?= $stats['total_antrian'] ?? 0 ?></h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-list-ol fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  

<?= $this->endSection() ?>
