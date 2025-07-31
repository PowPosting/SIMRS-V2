<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4" style="max-width: 1200px;">
    <div class="card shadow-sm modern-card">
        <div class="modern-header">
            <div class="d-flex align-items-center">
                <i class="bi bi-file-earmark-medical-fill me-3" style="font-size: 28px;"></i>
                <h2 class="m-0" style="font-size: 20px; font-weight: 800; letter-spacing: -0.5px;">DETAIL PEMERIKSAAN PASIEN</h2>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Data Pasien (Header) -->
            <div class="mb-4 pb-3 border-bottom">
                <div class="row g-0 align-items-center">
                    <div class="col-12 col-md-7 d-flex flex-row align-items-center justify-content-center justify-content-md-start mb-2 mb-md-0">
                        <span class="info-badge badge-info mr-3 mb-0" style="font-size:1.15rem; font-weight:500; background:#007bff; color:#fff; border-radius:1.2rem; padding:12px 24px; box-shadow:0 2px 8px rgba(0,123,255,0.10); min-width:120px; max-width:100%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:center; border:none; letter-spacing:1.2px;">
                            <?= esc($pemeriksaan['no_rekam_medis'] ?? '-') ?>
                        </span>
                        <h3 class="gradient-text mb-0 ml-2" style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.5px;">
                            <?= esc($pemeriksaan['nama_lengkap'] ?? '-') ?>
                        </h3>
                        <button class="btn btn-sm btn-outline-primary ml-3" id="btnExportWordSOAP" type="button" style="white-space:nowrap;">
                            <i class="fas fa-file-word"></i> Export Word
                        </button>
                        <!-- Modal Error ID Pemeriksaan -->
                        <div class="modal fade" id="modalIdPemeriksaanNotFound" tabindex="-1" aria-labelledby="modalIdPemeriksaanNotFoundLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalIdPemeriksaanNotFoundLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Data Tidak Ditemukan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                ID Pemeriksaan tidak ditemukan!
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var btn = document.getElementById('btnExportWordSOAP');
                            if (btn) {
                                btn.onclick = function() {
                                    var idPemeriksaan = "<?= isset($pemeriksaan['id']) ? esc($pemeriksaan['id']) : '' ?>";
                                    if (!idPemeriksaan || idPemeriksaan === '-') {
                                        var modal = new bootstrap.Modal(document.getElementById('modalIdPemeriksaanNotFound'));
                                        modal.show();
                                        return;
                                    }
                                    var url = '/exportword/pemeriksaansoap/' + encodeURIComponent(idPemeriksaan);
                                    window.open(url, '_blank');
                                };
                            }
                        });
                        </script>
                    </div>
                    <div class="col-12 col-md-5 d-flex flex-column align-items-center align-items-md-end justify-content-center">
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-end mb-1" style="gap: 10px;">
                            <span class="info-badge badge-info" style="min-width: 80px;"><i class="bi bi-person"></i> <?= esc($pemeriksaan['jenis_kelamin'] ?? '-') ?></span>
                            <span class="info-badge badge-warning" style="min-width: 100px;"><i class="bi bi-calendar-heart"></i> <?= esc($pemeriksaan['usia'] ?? '-') ?> Tahun</span>
                            <?php if (!empty($pemeriksaan['tanggal_lahir'])): ?>
                                <span class="info-badge badge-info" style="min-width: 110px;"><i class="bi bi-cake2"></i> <?= esc($pemeriksaan['tanggal_lahir']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-end" style="gap: 10px;">
                            <span class="info-badge badge-success" style="min-width: 120px;"><i class="bi bi-clock"></i> <?= esc($pemeriksaan['waktu_pemeriksaan'] ?? '-') ?></span>
                            <span class="info-badge badge-warning" style="min-width: 120px;"><i class="bi bi-hospital"></i> <?= esc($pemeriksaan['poli'] ?? '-') ?></span>
                            <span class="info-badge badge-info" style="min-width: 120px;"><i class="bi bi-person-badge"></i> <?= esc($pemeriksaan['dokter'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- S -->
            <div class="mb-4">
                <div class="section-header section-subjective"><i class="bi bi-chat-left-text-fill me-2"></i> S – Subjective</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="mb-2"><span style="font-weight:400;">Keluhan Utama:</span></div>
                        <div><?= nl2br(esc($pemeriksaan['keluhan_utama'] ?? '-')) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-2"><span style="font-weight:400;">Riwayat Penyakit:</span></div>
                        <div><?= nl2br(esc($pemeriksaan['riwayat_penyakit'] ?? '-')) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-2"><span style="font-weight:400;">Riwayat Alergi:</span></div>
                        <div><?= nl2br(esc($pemeriksaan['riwayat_alergi'] ?? '-')) ?></div>
                    </div>
                </div>
            </div>

            <!-- O -->
            <div class="mb-4">
                <div class="section-header section-objective"><i class="bi bi-activity me-2"></i> O – Objective</div>
                <div class="row g-3">
                    <div class="col-md-3"><span style="font-weight:400;">Tekanan Darah:</span><br><?= esc($pemeriksaan['tekanan_darah'] ?? '-') ?></div>
                    <div class="col-md-3"><span style="font-weight:400;">Denyut Nadi:</span><br><?= esc($pemeriksaan['denyut_nadi'] ?? '-') ?></div>
                    <div class="col-md-3"><span style="font-weight:400;">Suhu Tubuh:</span><br><?= esc($pemeriksaan['suhu_tubuh'] ?? '-') ?></div>
                    <div class="col-md-3"><span style="font-weight:400;">Respirasi:</span><br><?= esc($pemeriksaan['respirasi'] ?? '-') ?></div>
                </div>
                <div class="mt-3"><span style="font-weight:400;">Pemeriksaan Fisik:</span><br><?= nl2br(esc($pemeriksaan['pemeriksaan_fisik'] ?? '-')) ?></div>
            </div>

            <!-- A -->
            <div class="mb-4">
                <div class="section-header section-assessment"><i class="bi bi-clipboard2-pulse me-2"></i> A – Assessment</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-2"><span style="font-weight:400;">Diagnosis:</span></div>
                        <div><?= nl2br(esc($pemeriksaan['diagnosis'] ?? '-')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2"><span style="font-weight:400;">Prognosis:</span></div>
                        <div><?= nl2br(esc($pemeriksaan['prognosis'] ?? '-')) ?></div>
                    </div>
                </div>
            </div>

            <!-- P -->
            <div class="mb-4">
                <div class="section-header section-plan"><i class="bi bi-calendar2-check me-2"></i> P – Plan</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-2"><span style="font-weight:400;">Daftar Obat :</span></div>
                        <?php if (!empty($pemeriksaan['obat_list']) && is_array($pemeriksaan['obat_list'])): ?>
                            <ul class="mb-2">
                                <?php foreach ($pemeriksaan['obat_list'] as $obat): ?>
                                    <li><?= esc($obat) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div>-</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2"><span style="font-weight:400;">Catatan Obat dan Edukasi Pasien:</span></div>
                        <div><?= nl2br(esc($pemeriksaan['edukasi'] ?? '-')) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.modern-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: box-shadow 0.3s;
}
.modern-header {
    background: linear-gradient(90deg, #007bff 0%, #6f42c1 100%);
    color: #fff;
    padding: 10px 20px 18px 20px;
    position: relative;
}
.patient-avatar {
    background: #fff;
    color: #007bff;
    border-radius: 1rem;
    width: 120px;
    height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0.25rem 0.75rem rgba(0,123,255,0.10);
    /* border: none; */
    position: relative;
    overflow: hidden;
}
.form-group {
    margin-bottom: 1.2rem;
}
.form-label {
    font-weight: 400;
    color: #343a40;
    margin-bottom: 0.5rem;
    font-size: 15px;
    display: block;
}
.form-control {
    border: 1.5px solid #ced4da;
    border-radius: 0.75rem;
    padding: 10px 14px;
    font-size: 15px;
    background: #fff;
    width: 100%;
    box-shadow: none;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.08);
}

select.form-control {
    font-size: 15px;
    padding: 10px 14px;
    line-height: 1.5;
    height: auto;
    min-height: 44px;
    box-sizing: border-box;
    background-color: #fff;
    color: #343a40;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.08);
}
.form-control::placeholder {
    color: #adb5bd;
    font-style: italic;
}

.section-header {
    display: flex;
    align-items: center;
    padding: 16px 32px 16px 32px;
    margin: 0 0 24px 0;
    border-radius: 0.75rem 1.5rem 1.5rem 0.75rem;
    font-weight: 700;
    font-size: 18px;
    position: relative;
    background: #f8fafc;
    color: #343a40;
    border-left-width: 8px;
    border-left-style: solid;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(0,123,255,0.04);
}
.section-header i {
    margin-right: 12px;
    font-size: 1.3em;
    min-width: 24px;
    text-align: center;
}
.section-header span, .section-header strong {
    margin-left: 2px;
}
.section-subjective { border-left-color: #007bff; }
.section-subjective { border-left-color: #007bff; color: #007bff; }
.section-objective { border-left-color: #28a745; }
.section-objective { border-left-color: #28a745; color: #28a745; }
.section-assessment { border-left-color: #ffc107; }
.section-assessment { border-left-color: #ffc107; color: #ffc107; }
.section-plan { border-left-color: #17a2b8; }
.section-plan { border-left-color: #17a2b8; color: #17a2b8; }
.btn-modern {
    border: none;
    border-radius: 0.75rem;
    padding: 12px 24px;
    font-weight: 400;
    transition: box-shadow 0.2s, transform 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.07);
}
.btn-primary {
    background: linear-gradient(90deg, #007bff 0%, #6f42c1 100%);
    color: #fff;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.13);
}
.btn-success {
    background: #28a745;
    color: #fff;
}
.btn-danger {
    background: #dc3545;
    color: #fff;
}
.btn-sm {
    padding: 7px 12px;
    font-size: 14px;
    border-radius: 0.5rem;
}
.obat-row {
    background: #f8fafc;
    border: 1.5px dashed #b6c2d2;
    border-radius: 0.75rem;
    padding: 12px 8px;
    margin-bottom: 10px;
    transition: border-color 0.2s;
}
.obat-row:hover {
    border-color: #007bff;
    background: #e9f5ff;
}
.info-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 1rem;
    font-size: 13px;
    font-weight: 400;
    margin-bottom: 4px;
    background: #f1f3f5;
    color: #495057;
}
.badge-info { background: #e3f0fa; color: #007bff; }
.badge-warning { background: #fff3cd; color: #856404; }
.badge-success { background: #d4edda; color: #155724; }
.badge-danger { background: #f8d7da; color: #721c24; }
.floating-save {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1000;
    border-radius: 2rem;
    padding: 16px 32px;
    font-size: 16px;
    font-weight: 400;
    font-weight: 400;
    font-weight: 400;
    font-weight: 400;
    font-weight: 400;
    box-shadow: 0 8px 25px rgba(0,123,255,0.13);
}
@media (max-width: 768px) {
    .patient-avatar {
        width: 80px;
        height: 80px;
    }
    .floating-save {
        position: relative;
        bottom: auto;
        right: auto;
        width: 100%;
        margin-top: 24px;
    }
}
.gradient-text {
    background: linear-gradient(90deg, #007bff 0%, #6f42c1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.pulse {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}
</style>
<?= $this->endSection() ?>
