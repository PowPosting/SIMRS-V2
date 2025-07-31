<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('css/antrian-perawat.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="font-weight-bold mb-0" style="color: #000;">
                                <i class="fas fa-calendar-day text-dark mr-2 fa-lg"></i>
                                Daftar Antrian Pasien
                            </h3>
                            <p class="text-muted small mb-0 mt-1">Daftar pasien yang sedang menunggu pemeriksaan</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="search-box mr-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary">
                                            <i class="fas fa-search text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control border-left-0" id="searchInput" placeholder="Cari pasien...">
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-refresh" onclick="loadAntrianData()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="row" id="antrianContainer">
                        <!-- Data antrian akan dimuat melalui AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="antrianCardTemplate">
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <div class="card-body position-relative">
                    <span class="status-badge" style="display: {{status === 'Menunggu Pemeriksaan' ? 'inline-block' : 'none'}}">
                        <i class="fas fa-clock mr-1"></i>Menunggu
                    </span>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-initial bg-primary-subtle rounded-circle p-3">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ml-3">
                            <h5 class="mb-1 font-weight-bold text-dark">{{nama_pasien}}</h5>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge badge-light">RM: {{no_rm}}</span>
                                <span class="badge badge-primary" style="border-radius: 20px;">No. {{no_antrian}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="patient-details p-3 bg-light rounded mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clinic-medical text-primary mr-2"></i>
                            <span>{{poli_tujuan}}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-primary mr-2"></i>
                            <span class="small">Menunggu sejak <time class="timeago" datetime="{{created_at}}">{{created_at}}</time></span>
                        </div>
                    </div>
                    <button class="btn btn-success btn-block btn-mulai-pemeriksaan" data-id="{{id}}">
                        <i class="fas fa-stethoscope mr-2"></i>Mulai Pemeriksaan
                    </button>
                </div>
            </div>
        </div>
    </template>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.7/jquery.timeago.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.7/locales/jquery.timeago.id.js"></script>
<script src="<?= base_url('js/antrian-perawat-new.js') ?>"></script>
<?= $this->endSection() ?>
