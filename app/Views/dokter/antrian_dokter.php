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
                            <h3 class="font-weight-bold text-primary mb-0">
                                <i class="fas fa-list-alt mr-2"></i>Daftar Antrian Pasien Poli
                            </h3>
                            <p class="text-muted small mb-0 mt-1">Daftar pasien yang menunggu pemeriksaan dokter</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="search-box mr-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0">
                                            <i class="fas fa-search text-primary"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control border-left-0" id="searchInput" placeholder="Cari pasien...">
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-refresh" onclick="loadAntrianDokterData()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="row" id="antrianDokterContainer">
                        <?php if (!empty($antrianPoli)): ?>
                            <?php foreach ($antrianPoli as $antrian): ?>
                                <div class="col-md-6 col-xl-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                                        <div class="card-body position-relative">
                                            <span class="status-badge" style="display:<?= ($antrian['status'] === 'Menunggu Pemeriksaan') ? 'inline-block' : 'none' ?>">
                                                <i class="fas fa-clock mr-1"></i>Menunggu
                                            </span>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-initial bg-primary-subtle rounded-circle p-3">
                                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ml-3">
                                                    <h5 class="mb-1 font-weight-bold text-dark"><?= esc($antrian['nama_pasien'] ?? '-') ?></h5>
                                                    <p class="mb-0 text-muted small">
                                                        <span class="badge badge-light mr-2">RM: <?= esc($antrian['no_rm'] ?? '-') ?></span>
                                                        <span class="badge badge-primary">No. <?= esc($antrian['no_antrian'] ?? '-') ?></span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="patient-details p-3 bg-light rounded mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-clinic-medical text-primary mr-2"></i>
                                                    <span><?= esc($antrian['poli_tujuan'] ?? '-') ?></span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-primary mr-2"></i>
                                                    <span class="small">Menunggu sejak <?= esc($antrian['created_at'] ?? '-') ?></span>
                                                </div>
                                            </div>
                                            <button class="btn btn-success btn-block btn-mulai-pemeriksaan-dokter" onclick="window.location.href='/dokter/pemeriksaan/<?= esc($antrian['id']) ?>'">
                                                <i class="fas fa-user-md mr-2"></i>Mulai Pemeriksaan Dokter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center text-muted py-5">
                                <h4>Tidak ada antrian poli saat ini</h4>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="antrianDokterCardTemplate">
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
                            <p class="mb-0 text-muted small">
                                <span class="badge badge-light mr-2">RM: {{no_rm}}</span>
                                <span class="badge badge-primary">No. {{no_antrian}}</span>
                            </p>
                        </div>
                    </div>
                    <div class="patient-details p-3 bg-light rounded mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clinic-medical text-primary mr-2"></i>
                            <span>{{poli_tujuan}}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-primary mr-2"></i>
                            <span class="small">Menunggu sejak {{created_at}}</span>
                        </div>
                    </div>
                    <button class="btn btn-success btn-block btn-mulai-pemeriksaan-dokter" onclick="window.location.href='/dokter/pemeriksaan/{{id}}'">
                        <i class="fas fa-user-md mr-2"></i>Mulai Pemeriksaan Dokter
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
<script src="<?= base_url('js/antrian-dokter.js') ?>"></script>
<?= $this->endSection() ?>
