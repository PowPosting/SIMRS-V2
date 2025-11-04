<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent px-0">
            <li class="breadcrumb-item"><a href="<?= base_url('rekam-medis') ?>">Rekam Medis</a></li>
            <li class="breadcrumb-item active"><?= $pasien['no_rekam_medis'] ?></li>
        </ol>
    </nav>

    <!-- Patient Info Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle mr-2"></i>
                        <?= $pasien['title'] ?> <?= $pasien['nama_lengkap'] ?>
                    </h4>
                    <small>No. Rekam Medis: <strong><?= $pasien['no_rekam_medis'] ?></strong></small>
                </div>
                <div>
                    <a href="<?= base_url('rekam-medis/print/' . $pasien['no_rekam_medis']) ?>" 
                       class="btn btn-light btn-sm" target="_blank">
                        <i class="fas fa-print mr-1"></i> Cetak Rekam Medis
                    </a>
                    <a href="<?= base_url('rekam-medis') ?>" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row">
                <!-- Identitas -->
                <div class="col-md-6 mb-3">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-id-card text-primary mr-2"></i>Identitas</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%">Jenis Kelamin</td>
                            <td><strong><?= $pasien['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></strong></td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal Lahir</td>
                            <td><strong><?= $pasien['tempat_lahir'] ?>, <?= date('d/m/Y', strtotime($pasien['tanggal_lahir'])) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Umur</td>
                            <td><strong><?= date_diff(date_create($pasien['tanggal_lahir']), date_create('today'))->y ?> tahun</strong></td>
                        </tr>
                        <tr>
                            <td>Status Perkawinan</td>
                            <td><strong><?= $pasien['status_perkawinan'] ?? '-' ?></strong></td>
                        </tr>
                        <tr>
                            <td>No. HP</td>
                            <td><strong><?= $pasien['nomor_hp'] ?? '-' ?></strong></td>
                        </tr>
                    </table>
                </div>

                <!-- Alamat & Info Lain -->
                <div class="col-md-6 mb-3">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-map-marker-alt text-primary mr-2"></i>Alamat & Info Lainnya</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%">Alamat</td>
                            <td><strong><?= $alamat['alamat_lengkap'] ?? '-' ?></strong></td>
                        </tr>
                        <tr>
                            <td>Kelurahan/Desa</td>
                            <td><strong><?= $alamat['kelurahan'] ?? '-' ?></strong></td>
                        </tr>
                        <tr>
                            <td>Kecamatan</td>
                            <td><strong><?= $alamat['kecamatan'] ?? '-' ?></strong></td>
                        </tr>
                        <tr>
                            <td>Golongan Darah</td>
                            <td><strong><?= $infoMedis['golongan_darah'] ?? '-' ?></strong></td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td><strong><?= $infoTambahan['agama'] ?? '-' ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Kunjungan -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-history text-primary mr-2"></i>
                Riwayat Kunjungan
                <span class="badge badge-primary ml-2"><?= count($riwayatKunjungan) ?> Kunjungan</span>
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($riwayatKunjungan)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Riwayat Kunjungan</h5>
                    <p class="text-muted">Pasien ini belum pernah melakukan kunjungan</p>
                </div>
            <?php else: ?>
                <div class="timeline">
                    <?php foreach ($riwayatKunjungan as $index => $kunjungan): ?>
                        <div class="timeline-item mb-4">
                            <div class="row">
                                <div class="col-md-2 text-right">
                                    <div class="timeline-badge bg-primary text-white">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <strong><?= date('d M Y', strtotime($kunjungan['tanggal_kunjungan'])) ?></strong><br>
                                        <?= date('H:i', strtotime($kunjungan['tanggal_kunjungan'])) ?> WIB
                                    </small>
                                </div>
                                <div class="col-md-10">
                                    <div class="card border-left-primary">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="font-weight-bold mb-3">
                                                        <i class="fas fa-hospital text-primary mr-2"></i>
                                                        <?= $kunjungan['nama_poli'] ?>
                                                        <span class="badge badge-info ml-2"><?= $kunjungan['no_antrian'] ?></span>
                                                    </h6>
                                                    
                                                    <!-- Vital Signs -->
                                                    <?php if ($kunjungan['tekanan_darah']): ?>
                                                        <p class="mb-2"><strong>Tanda Vital:</strong></p>
                                                        <ul class="list-unstyled ml-3 small">
                                                            <li>• TD: <?= $kunjungan['tekanan_darah'] ?> mmHg</li>
                                                            <li>• Nadi: <?= $kunjungan['detak_jantung'] ?? '-' ?> x/menit</li>
                                                            <li>• Suhu: <?= $kunjungan['suhu_tubuh'] ?? '-' ?>°C</li>
                                                            <li>• RR: <?= $kunjungan['pernafasan'] ?? '-' ?> x/menit</li>
                                                            <li>• BB: <?= $kunjungan['berat_badan'] ?? '-' ?> kg</li>
                                                            <li>• TB: <?= $kunjungan['tinggi_badan'] ?? '-' ?> cm</li>
                                                        </ul>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($kunjungan['keluhan']): ?>
                                                        <p class="mb-2"><strong>Keluhan:</strong></p>
                                                        <p class="ml-3 small"><?= nl2br(esc($kunjungan['keluhan'])) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <!-- SOAP -->
                                                    <?php if ($kunjungan['assessment']): ?>
                                                        <?php if ($kunjungan['subjective']): ?>
                                                            <p class="mb-2"><strong>Subjective (Keluhan Utama):</strong></p>
                                                            <p class="ml-3 small"><?= nl2br(esc($kunjungan['subjective'])) ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($kunjungan['riwayat_penyakit']): ?>
                                                            <p class="mb-2"><strong>Riwayat Penyakit:</strong></p>
                                                            <p class="ml-3 small"><?= nl2br(esc($kunjungan['riwayat_penyakit'])) ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($kunjungan['riwayat_alergi']): ?>
                                                            <p class="mb-2"><strong>Riwayat Alergi:</strong></p>
                                                            <p class="ml-3 small"><?= nl2br(esc($kunjungan['riwayat_alergi'])) ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($kunjungan['objective']): ?>
                                                            <p class="mb-2"><strong>Objective (Pemeriksaan Fisik):</strong></p>
                                                            <p class="ml-3 small"><?= nl2br(esc($kunjungan['objective'])) ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <p class="mb-2"><strong>Assessment (Diagnosis):</strong></p>
                                                        <p class="ml-3 small"><?= nl2br(esc($kunjungan['assessment'])) ?></p>
                                                        
                                                        <?php if ($kunjungan['prognosis']): ?>
                                                            <p class="mb-2"><strong>Prognosis:</strong></p>
                                                            <p class="ml-3 small"><?= nl2br(esc($kunjungan['prognosis'])) ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($kunjungan['plan']): ?>
                                                            <p class="mb-2"><strong>Plan (Terapi/Edukasi):</strong></p>
                                                            <p class="ml-3 small"><?= nl2br(esc($kunjungan['plan'])) ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($kunjungan['nama_dokter']): ?>
                                                            <p class="small text-muted mt-3">
                                                                <i class="fas fa-user-md mr-1"></i>
                                                                Dokter: <strong><?= $kunjungan['nama_dokter'] ?></strong>
                                                            </p>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <div class="alert alert-warning small mb-0">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            Belum ada catatan SOAP dari dokter
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Resep & Biaya -->
                                                    <div class="mt-3">
                                                        <?php if ($kunjungan['jumlah_resep'] > 0): ?>
                                                            <span class="badge badge-success mr-2">
                                                                <i class="fas fa-pills mr-1"></i>
                                                                <?= $kunjungan['jumlah_resep'] ?> Resep
                                                            </span>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($kunjungan['total_biaya']): ?>
                                                            <span class="badge badge-info">
                                                                <i class="fas fa-money-bill-wave mr-1"></i>
                                                                Rp <?= number_format($kunjungan['total_biaya'], 0, ',', '.') ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.border-left-primary {
    border-left: 4px solid #667eea;
}

.timeline-item {
    position: relative;
}

.timeline-badge {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
</style>
<?= $this->endSection() ?>
