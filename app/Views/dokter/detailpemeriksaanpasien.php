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
            <div class="mb-4 pb-4" style="background: #f8f9fa; border-radius: 1rem; padding: 24px; border: 1px solid #e9ecef;">
                <div class="row g-4 align-items-center">
                    <!-- Nomor RM dan Nama -->
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div style="background: #007bff; color: #fff; border-radius: 0.75rem; padding: 12px 16px; margin-right: 16px; box-shadow: 0 2px 6px rgba(0,123,255,0.2);">
                                <div style="font-size: 0.7rem; opacity: 0.9; margin-bottom: 2px;">No. RM</div>
                                <div style="font-size: 1rem; font-weight: 700;">
                                    <?= esc($pemeriksaan['no_rekam_medis'] ?? '-') ?>
                                </div>
                            </div>
                            <div>
                                <h3 style="color: #2d3748; font-size: 1.4rem; font-weight: 700; margin: 0;">
                                    <?= esc($pemeriksaan['nama_lengkap'] ?? '-') ?>
                                </h3>
                            </div>
                        </div>
                        
                        <!-- Info Pasien -->
                        <div class="d-flex flex-wrap gap-2">
                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 10px; border-radius: 0.4rem; font-size: 0.8rem; font-weight: 600;">
                                <i class="bi bi-person me-1"></i>
                                <?= esc($pemeriksaan['jenis_kelamin'] ?? '-') ?>
                            </span>
                            <span style="background: #fff3e0; color: #f57c00; padding: 4px 10px; border-radius: 0.4rem; font-size: 0.8rem; font-weight: 600;">
                                <i class="bi bi-calendar-heart me-1"></i>
                                <?= esc($pemeriksaan['usia'] ?? '-') ?> Tahun
                            </span>
                            <?php if (!empty($pemeriksaan['tanggal_lahir'])): ?>
                            <span style="background: #f3e5f5; color: #7b1fa2; padding: 4px 10px; border-radius: 0.4rem; font-size: 0.8rem; font-weight: 600;">
                                <i class="bi bi-cake2 me-1"></i>
                                <?= esc($pemeriksaan['tanggal_lahir']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Info Pemeriksaan -->
                    <div class="col-lg-5 col-md-5 col-12">
                        <div style="background: #ffffff; border-radius: 0.75rem; padding: 16px; border: 1px solid #e9ecef;">
                            <h6 style="color: #4a5568; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 12px;">
                                Info Pemeriksaan
                            </h6>
                            <div class="d-flex flex-column gap-2">
                                <div style="font-size: 0.85rem;">
                                    <i class="bi bi-clock me-2" style="color: #28a745;"></i>
                                    <span style="color: #4a5568;"><?= esc($pemeriksaan['waktu_pemeriksaan'] ?? '-') ?></span>
                                </div>
                                <div style="font-size: 0.85rem;">
                                    <i class="bi bi-hospital me-2" style="color: #ffc107;"></i>
                                    <span style="color: #4a5568;"><?= esc($pemeriksaan['poli'] ?? '-') ?></span>
                                </div>
                                <div style="font-size: 0.85rem;">
                                    <i class="bi bi-person-badge me-2" style="color: #007bff;"></i>
                                    <span style="color: #4a5568;"><?= esc($pemeriksaan['dokter'] ?? '-') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Export Button -->
                    <div class="col-lg-1 col-md-1 col-12 text-center">
                        <button class="btn btn-outline-primary btn-sm" id="btnExportWordSOAP" type="button" style="border-radius: 0.5rem; padding: 8px 12px;">
                            <i class="bi bi-file-earmark-word me-1"></i>
                            <span class="d-none d-md-inline">Export</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- S -->
            <div class="mb-4">
                <div class="section-header section-subjective">
                    <i class="bi bi-chat-left-text-fill me-3"></i>
                    <span>S – Subjective (Data Subjektif)</span>
                </div>
                <div style="background: #ffffff; border: 1px solid #e9ecef; border-radius: 0.75rem; padding: 20px;">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <h6 style="color: #495057; font-weight: 600; margin-bottom: 8px;">
                                <i class="bi bi-exclamation-circle me-2" style="color: #007bff;"></i>
                                Keluhan Utama
                            </h6>
                            <div style="color: #6c757d; line-height: 1.6;">
                                <?= nl2br(esc($pemeriksaan['keluhan_utama'] ?? '-')) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 style="color: #495057; font-weight: 600; margin-bottom: 8px;">
                                <i class="bi bi-journal-medical me-2" style="color: #28a745;"></i>
                                Riwayat Penyakit
                            </h6>
                            <div style="color: #6c757d; line-height: 1.6;">
                                <?= nl2br(esc($pemeriksaan['riwayat_penyakit'] ?? '-')) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 style="color: #495057; font-weight: 600; margin-bottom: 8px;">
                                <i class="bi bi-shield-exclamation me-2" style="color: #dc3545;"></i>
                                Riwayat Alergi
                            </h6>
                            <div style="color: #6c757d; line-height: 1.6;">
                                <?= nl2br(esc($pemeriksaan['riwayat_alergi'] ?? '-')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- O -->
            <div class="mb-4">
                <div class="section-header section-objective">
                    <i class="bi bi-activity me-3"></i>
                    <span>O – Objective (Data Objektif)</span>
                </div>
                <div style="background: #ffffff; border: 1px solid #e9ecef; border-radius: 0.75rem; padding: 20px;">
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div style="background: #f8f9fa; border-radius: 0.5rem; padding: 12px; text-center;">
                                <i class="bi bi-heart-pulse" style="color: #dc3545; font-size: 1.2rem; margin-bottom: 8px;"></i>
                                <div style="font-size: 0.8rem; color: #6c757d; margin-bottom: 4px;">Tekanan Darah</div>
                                <div style="font-weight: 600; color: #495057;"><?= esc($pemeriksaan['tekanan_darah'] ?? '-') ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="background: #f8f9fa; border-radius: 0.5rem; padding: 12px; text-center;">
                                <i class="bi bi-heart" style="color: #e74c3c; font-size: 1.2rem; margin-bottom: 8px;"></i>
                                <div style="font-size: 0.8rem; color: #6c757d; margin-bottom: 4px;">Denyut Nadi</div>
                                <div style="font-weight: 600; color: #495057;"><?= esc($pemeriksaan['denyut_nadi'] ?? '-') ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="background: #f8f9fa; border-radius: 0.5rem; padding: 12px; text-center;">
                                <i class="bi bi-thermometer-half" style="color: #f39c12; font-size: 1.2rem; margin-bottom: 8px;"></i>
                                <div style="font-size: 0.8rem; color: #6c757d; margin-bottom: 4px;">Suhu Tubuh</div>
                                <div style="font-weight: 600; color: #495057;"><?= esc($pemeriksaan['suhu_tubuh'] ?? '-') ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="background: #f8f9fa; border-radius: 0.5rem; padding: 12px; text-center;">
                                <i class="bi bi-lungs" style="color: #3498db; font-size: 1.2rem; margin-bottom: 8px;"></i>
                                <div style="font-size: 0.8rem; color: #6c757d; margin-bottom: 4px;">Respirasi</div>
                                <div style="font-weight: 600; color: #495057;"><?= esc($pemeriksaan['respirasi'] ?? '-') ?></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h6 style="color: #495057; font-weight: 600; margin-bottom: 12px;">
                            <i class="bi bi-search me-2" style="color: #28a745;"></i>
                            Pemeriksaan Fisik
                        </h6>
                        <div style="background: #f8f9fa; border-radius: 0.5rem; padding: 16px; color: #6c757d; line-height: 1.6;">
                            <?= nl2br(esc($pemeriksaan['pemeriksaan_fisik'] ?? '-')) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- A -->
            <div class="mb-4">
                <div class="section-header section-assessment">
                    <i class="bi bi-clipboard2-pulse me-3"></i>
                    <span>A – Assessment (Asesmen)</span>
                </div>
                <div style="background: #ffffff; border: 1px solid #e9ecef; border-radius: 0.75rem; padding: 20px;">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 style="color: #495057; font-weight: 600; margin-bottom: 12px;">
                                <i class="bi bi-clipboard2-check me-2" style="color: #ffc107;"></i>
                                Diagnosis
                            </h6>
                            <div style="background: #fff8e1; border-left: 4px solid #ffc107; border-radius: 0.5rem; padding: 16px; color: #6c757d; line-height: 1.6;">
                                <?= nl2br(esc($pemeriksaan['diagnosis'] ?? '-')) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 style="color: #495057; font-weight: 600; margin-bottom: 12px;">
                                <i class="bi bi-graph-up-arrow me-2" style="color: #28a745;"></i>
                                Prognosis
                            </h6>
                            <div style="background: #e8f5e8; border-left: 4px solid #28a745; border-radius: 0.5rem; padding: 16px; color: #6c757d; line-height: 1.6;">
                                <?= nl2br(esc($pemeriksaan['prognosis'] ?? '-')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- P -->
            <div class="mb-4">
                <div class="section-header section-plan">
                    <i class="bi bi-calendar2-check me-3"></i>
                    <span>P – Plan (Rencana Tindakan)</span>
                </div>
                <div style="background: #ffffff; border: 1px solid #e9ecef; border-radius: 0.75rem; padding: 20px;">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 style="color: #495057; font-weight: 600; margin-bottom: 12px;">
                                <i class="bi bi-capsule me-2" style="color: #17a2b8;"></i>
                                Daftar Obat
                            </h6>
                            <div style="background: #e3f2fd; border-left: 4px solid #17a2b8; border-radius: 0.5rem; padding: 16px;">
                                <?php if (!empty($pemeriksaan['obat_list']) && is_array($pemeriksaan['obat_list'])): ?>
                                    <ul style="margin: 0; padding-left: 20px; color: #6c757d; line-height: 1.8;">
                                        <?php foreach ($pemeriksaan['obat_list'] as $obat): ?>
                                            <li style="margin-bottom: 4px;"><?= esc($obat) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <div style="color: #6c757d; font-style: italic;">Tidak ada obat yang diresepkan</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 style="color: #495057; font-weight: 600; margin-bottom: 12px;">
                                <i class="bi bi-lightbulb me-2" style="color: #fd7e14;"></i>
                                Catatan Obat dan Edukasi Pasien
                            </h6>
                            <div style="background: #fff3cd; border-left: 4px solid #fd7e14; border-radius: 0.5rem; padding: 16px; color: #6c757d; line-height: 1.6;">
                                <?= nl2br(esc($pemeriksaan['edukasi'] ?? '-')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Error ID Pemeriksaan -->
<div class="modal fade" id="modalIdPemeriksaanNotFound" tabindex="-1" aria-labelledby="modalIdPemeriksaanNotFoundLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalIdPemeriksaanNotFoundLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Data Tidak Ditemukan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
