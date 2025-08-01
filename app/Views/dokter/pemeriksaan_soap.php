<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
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
    font-weight: 600;
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
    padding: 14px 22px;
    margin: 0 -24px 24px -24px;
    border-radius: 0.75rem;
    font-weight: 700;
    font-size: 17px;
    position: relative;
    background: #f8fafc;
    color: #343a40;
}
.section-subjective { border-left: 6px solid #007bff; }
.section-objective { border-left: 6px solid #28a745; }
.section-assessment { border-left: 6px solid #ffc107; }
.section-plan { border-left: 6px solid #17a2b8; }
.btn-modern {
    border: none;
    border-radius: 0.75rem;
    padding: 12px 24px;
    font-weight: 600;
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
    font-weight: 600;
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

/* Autocomplete Styling */
.autocomplete-list {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    border: 1px solid #e0e6ed !important;
}

.autocomplete-item {
    transition: background-color 0.2s ease;
    font-size: 14px;
}

.autocomplete-item:hover {
    background-color: #e3f2fd !important;
}

.autocomplete-item:last-child {
    border-bottom: none !important;
}

.stok-obat {
    white-space: nowrap;
    font-size: 10px !important;
    padding: 2px 6px !important;
    border-radius: 10px !important;
}

/* Obat Row Hover Effects */
.obat-row {
    transition: all 0.3s ease;
}

.obat-row:hover {
    border-color: #007bff !important;
    background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,123,255,0.1);
}
</style>

<div class="container mt-4" style="max-width:1400px;">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
        </div>
    <?php endif; ?>
    <form method="post" action="/dokter/simpanPemeriksaanSoap">
        <?= csrf_field() ?>
        <input type="hidden" name="no_rm" value="<?= esc(isset($pasien['no_rekam_medis']) ? $pasien['no_rekam_medis'] : (isset($pasien['no_rm']) ? $pasien['no_rm'] : '') ) ?>">
        <?php if (isset($antrian['id'])): ?>
        <input type="hidden" name="id_antrian" value="<?= esc($antrian['id']) ?>">
        <?php endif; ?>
        <div class="modern-card">
            <div class="modern-header">
                <div class="d-flex align-items-center position-relative z-index-1">
                    <i class="bi bi-file-medical-fill me-3" style="font-size: 28px;"></i>
                    <h1 class="m-0" style="font-size: 18px; font-weight: 800; letter-spacing: -0.5px;">
                        FORM PEMERIKSAAN SOAP
                    </h1>
                </div>
            </div>
            
            <div class="card-body p-4 p-md-5" style="margin-top: -10px;">
                
                <!-- HEADER PASIEN -->
                <div class="mb-5 pb-4" style="background: #ffffff; border: 1px solid #e9ecef; border-radius: 1rem; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <div class="row g-4 align-items-center">
                        <!-- Nomor Rekam Medis -->
                        <div class="col-lg-2 col-md-3 col-12 text-center">
                            <div style="background: #007bff; color: #fff; border-radius: 0.75rem; padding: 16px; box-shadow: 0 2px 6px rgba(0,123,255,0.2);">
                                <div style="font-size: 0.75rem; opacity: 0.9; margin-bottom: 4px;">No. RM</div>
                                <div style="font-size: 1.1rem; font-weight: 700;">
                                    <?php
                                    $no_rm = isset($pasien['no_rekam_medis']) ? $pasien['no_rekam_medis'] : (isset($pasien['no_rm']) ? $pasien['no_rm'] : 'RM-001');
                                    $no_rm = str_replace(["\r", "\n"], ' ', $no_rm);
                                    echo esc($no_rm);
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Pasien -->
                        <div class="col-lg-6 col-md-5 col-12">
                            <h2 style="color: #2d3748; font-size: 1.5rem; font-weight: 700; margin-bottom: 16px;">
                                <?= isset($pasien['nama_lengkap']) ? esc($pasien['nama_lengkap']) : 'Nama Pasien' ?>
                            </h2>
                            
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span style="background: #e3f2fd; color: #1976d2; padding: 6px 12px; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600;">
                                    <i class="bi bi-person me-1"></i>
                                    <?= isset($pasien['jenis_kelamin']) ? esc($pasien['jenis_kelamin']) : '-' ?>
                                </span>
                                <span style="background: #fff3e0; color: #f57c00; padding: 6px 12px; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600;">
                                    <i class="bi bi-calendar-heart me-1"></i>
                                    <?php
                                    $umur = 0;
                                    $debug_info = '';
                                    
                                    // Debug: cek data yang ada
                                    if (isset($pasien['usia'])) {
                                        $debug_info .= "Usia field: '" . $pasien['usia'] . "' ";
                                    }
                                    if (isset($pasien['tgl_lahir'])) {
                                        $debug_info .= "Tgl lahir: '" . $pasien['tgl_lahir'] . "' ";
                                    }
                                    
                                    if (isset($pasien['usia']) && !empty($pasien['usia']) && $pasien['usia'] > 0) {
                                        $umur = (int)$pasien['usia'];
                                    } elseif (isset($pasien['tgl_lahir']) && !empty($pasien['tgl_lahir'])) {
                                        try {
                                            $lahir = new DateTime($pasien['tgl_lahir']);
                                            $today = new DateTime();
                                            $umur = $today->diff($lahir)->y;
                                        } catch (Exception $e) {
                                            $umur = 0;
                                            $debug_info .= "Error: " . $e->getMessage();
                                        }
                                    }
                                    
                                    echo $umur . ' Tahun';
                                    // Tampilkan debug info sementara
                                    if ($umur == 0 && !empty($debug_info)) {
                                        echo '<small style="display:block;font-size:0.7rem;color:#999;margin-top:2px;">' . $debug_info . '</small>';
                                    }
                                    ?>
                                </span>
                            </div>
                            
                            <?php if(isset($pasien['tgl_lahir']) && $pasien['tgl_lahir']): ?>
                            <div style="color: #718096; font-size: 0.9rem;">
                                <i class="bi bi-cake2 me-1"></i>
                                <span><?= esc($pasien['tgl_lahir']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Info Pemeriksaan -->
                        <div class="col-lg-4 col-md-4 col-12">
                            <div style="background: #f8f9fa; border-radius: 0.75rem; padding: 16px;">
                                <h6 style="color: #4a5568; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 12px;">
                                    Info Pemeriksaan
                                </h6>
                                <div class="d-flex flex-column gap-2">
                                    <div style="font-size: 0.85rem;">
                                        <i class="bi bi-clock me-2" style="color: #28a745;"></i>
                                        <span style="color: #4a5568;"><?= isset($waktu_pemeriksaan) ? esc($waktu_pemeriksaan) : date('d-m-Y H:i') ?></span>
                                    </div>
                                    <div style="font-size: 0.85rem;">
                                        <i class="bi bi-hospital me-2" style="color: #ffc107;"></i>
                                        <span style="color: #4a5568;"><?= isset($poli) ? esc($poli) : 'Poli Umum' ?></span>
                                    </div>
                                    <div style="font-size: 0.85rem;">
                                        <i class="bi bi-person-badge me-2" style="color: #007bff;"></i>
                                        <span style="color: #4a5568;"><?= isset($dokter) ? esc($dokter) : 'dr. Dokter' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- S – Subjective -->
                <div class="mb-5">
                    <div class="section-header section-subjective">
                        <i class="bi bi-chat-left-text-fill me-3" style="font-size: 24px;"></i>
                        <span>S – Subjective (Data Subjektif)</span>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="keluhan_utama">
                                    <i class="bi bi-exclamation-circle me-2"></i>Keluhan Utama
                                </label>
                                <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="4" placeholder="Deskripsikan keluhan utama pasien dengan detail..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="riwayat_penyakit">
                                    <i class="bi bi-journal-medical me-2"></i>Riwayat Penyakit
                                </label>
                                <textarea class="form-control" id="riwayat_penyakit" name="riwayat_penyakit" rows="4" placeholder="Riwayat penyakit sebelumnya, keluarga, dll..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="riwayat_alergi">
                                    <i class="bi bi-shield-exclamation me-2"></i>Riwayat Alergi
                                </label>
                                <textarea class="form-control" id="riwayat_alergi" name="riwayat_alergi" rows="4" placeholder="Alergi obat, makanan, atau zat lainnya..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- O – Objective -->
                <div class="mb-5">
                    <div class="section-header section-objective">
                        <i class="bi bi-activity me-3" style="font-size: 24px;"></i>
                        <span>O – Objective (Data Objektif)</span>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="tekanan_darah">
                                    <i class="bi bi-heart-pulse me-2"></i>Tekanan Darah
                                </label>
                                <input type="text" class="form-control" id="tekanan_darah" name="tekanan_darah" placeholder="120/80 mmHg">
                                <small class="text-muted">Format: sistole/diastole</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="denyut_nadi">
                                    <i class="bi bi-heart me-2"></i>Denyut Nadi
                                </label>
                                <input type="text" class="form-control" id="denyut_nadi" name="denyut_nadi" placeholder="80 x/menit">
                                <small class="text-muted">Normal: 60-100 x/menit</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="suhu_tubuh">
                                    <i class="bi bi-thermometer-half me-2"></i>Suhu Tubuh
                                </label>
                                <input type="text" class="form-control" id="suhu_tubuh" name="suhu_tubuh" placeholder="36.5°C">
                                <small class="text-muted">Normal: 36.1-37.2°C</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="respirasi">
                                    <i class="bi bi-lungs me-2"></i>Respirasi
                                </label>
                                <input type="text" class="form-control" id="respirasi" name="respirasi" placeholder="20 x/menit">
                                <small class="text-muted">Normal: 16-24 x/menit</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="pemeriksaan_fisik">
                                    <i class="bi bi-search me-2"></i>Pemeriksaan Fisik
                                </label>
                                <textarea class="form-control" id="pemeriksaan_fisik" name="pemeriksaan_fisik" rows="4" placeholder="Hasil pemeriksaan fisik lengkap (inspeksi, palpasi, perkusi, auskultasi)..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- A – Assessment -->
                <div class="mb-5">
                    <div class="section-header section-assessment">
                        <i class="bi bi-clipboard2-pulse me-3" style="font-size: 24px;"></i>
                        <span>A – Assessment (Asesmen)</span>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="diagnosis">
                                    <i class="bi bi-clipboard2-check me-2"></i>Diagnosis
                                </label>
                                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4" placeholder="Diagnosis utama dan diagnosis banding (sertakan kode ICD-10 jika memungkinkan)..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="prognosis">
                                    <i class="bi bi-graph-up-arrow me-2"></i>Prognosis
                                </label>
                                <textarea class="form-control" id="prognosis" name="prognosis" rows="4" placeholder="Perkiraan perjalanan penyakit dan kemungkinan kesembuhan..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- P – Plan -->
                <div class="mb-5">
                    <div class="section-header section-plan">
                        <i class="bi bi-calendar2-check me-3" style="font-size: 24px;"></i>
                        <span>P – Plan (Rencana Tindakan)</span>    
                    </div>
                    <div class="row g-4 align-items-stretch">
                        <div class="col-md-6 d-flex flex-column justify-content-between">
                            <div class="form-group h-100 d-flex flex-column">
                                <label class="form-label mb-3">
                                    <i class="bi bi-capsule mr-2"></i>Resep Obat
                                </label>
                                <div id="obat-list" class="flex-grow-1">
                                    <div class="obat-row">
                                        <div class="row align-items-end">
                                            <div class="col-12 mb-3">
                                                <label class="small text-muted mb-1">Obat Manual</label>
                                                <textarea class="form-control" name="obat_manual[]" rows="2" placeholder="Nama obat + dosis + frekuensi&#10;Contoh: Paracetamol 500mg 3x1 sehari"></textarea>
                                            </div>
                                            <div class="col-4 mb-3 position-relative">
                                                <label class="small text-muted mb-1">Cari dari Database</label>
                                                <input type="text" class="form-control obat-autocomplete" placeholder="Cari obat..." autocomplete="off" oninput="cariObat(this)">
                                                <input type="hidden" name="obat_db[]">
                                                <div class="autocomplete-list shadow-sm" style="position:absolute;top:100%;left:0;right:0;z-index:1000;background:#fff;border:1px solid #ddd;border-radius:0.5rem;max-height:200px;overflow-y:auto;display:none;"></div>
                                            </div>
                                            <div class="col-3 mb-3">
                                                <label class="small text-muted mb-1">Jumlah</label>
                                                <input type="number" class="form-control" name="jumlah_obat[]" placeholder="1" min="1" value="1">
                                            </div>
                                            <div class="col-3 mb-3">
                                                <label class="small text-muted mb-1">Instruksi</label>
                                                <input type="text" class="form-control" name="instruksi_obat[]" placeholder="3x1/hari">
                                            </div>
                                            <div class="col-2 mb-3 text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="stok-obat badge badge-info mb-1" style="font-size:10px;display:none;"></span>
                                                    <button type="button" class="btn btn-success btn-sm" style="width:32px; height:32px; padding:0;" onclick="tambahObat()" title="Tambah baris obat">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column justify-content-between">
                            <div class="form-group h-100 d-flex flex-column">
                                <label class="form-label mb-2" for="edukasi">
                                    <i class="bi bi-lightbulb me-2"></i>Catatan obat dan Edukasi
                                </label>
                                <textarea class="form-control flex-grow-1" id="edukasi" name="edukasi" rows="6" placeholder="Edukasi mengenai penyakit, cara minum obat, gaya hidup, dll..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="d-none d-md-block">
                    <button type="submit" class="btn btn-primary btn-modern floating-save pulse">
                        <i class="bi bi-cloud-check-fill"></i>
                        <span>Simpan Pemeriksaan</span>
                    </button>
                </div>
                
                <div class="d-md-none">
                    <button type="submit" class="btn btn-primary btn-modern w-100" style="padding: 16px;">
                        <i class="bi bi-cloud-check-fill"></i>
                        <span>Simpan Pemeriksaan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Data obat untuk autocomplete
const obatList = <?php echo json_encode($list_obat ?? []); ?>;

function cariObat(input) {
    const wrapper = input.closest('.col-4');
    
    if (!wrapper) {
        console.error('Wrapper tidak ditemukan! Periksa class col-4');
        return;
    }
    
    const list = wrapper.querySelector('.autocomplete-list');
    const hidden = wrapper.querySelector('input[type=hidden]');
    
    // Cari .stok-obat di baris yang sama
    let stokSpan = null;
    let parent = wrapper.parentElement;
    while (parent && !stokSpan) {
        stokSpan = parent.querySelector('.stok-obat');
        parent = parent.parentElement;
    }
    
    const val = input.value.trim().toLowerCase();
    list.innerHTML = '';
    
    if (!val) {
        list.style.display = 'none';
        hidden.value = '';
        if(stokSpan) { 
            stokSpan.style.display = 'none'; 
            stokSpan.textContent = ''; 
        }
        return;
    }
    
    let found = false;
    obatList.forEach(obat => {
        if (obat.nama_obat.toLowerCase().includes(val)) {
            found = true;
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            item.style.padding = '8px 14px';
            item.style.cursor = 'pointer';
            item.style.borderBottom = '1px solid #eee';
            item.innerHTML = `<strong>${obat.nama_obat}</strong> <span class='badge badge-light text-dark ml-2' style='font-size:11px;'>Stok: ${obat.stok}</span>`;
            
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '#fff';
            });
            
            item.onclick = function() {
                input.value = obat.nama_obat;
                hidden.value = obat.id_obat;
                list.style.display = 'none';
                if(stokSpan) { 
                    stokSpan.textContent = 'Stok: ' + obat.stok; 
                    stokSpan.style.display = 'inline-block'; 
                    stokSpan.className = 'badge badge-success mb-1';
                    stokSpan.style.fontSize = '10px';
                    stokSpan.style.padding = '2px 6px';
                    
                    // Color coding based on stock level
                    if (obat.stok <= 10) {
                        stokSpan.className = 'badge badge-danger mb-1';
                    } else if (obat.stok <= 50) {
                        stokSpan.className = 'badge badge-warning mb-1';
                    } else {
                        stokSpan.className = 'badge badge-success mb-1';
                    }
                }
            };
            list.appendChild(item);
        }
    });
    
    list.style.display = found ? 'block' : 'none';
    
    if (!found) {
        const item = document.createElement('div');
        item.className = 'autocomplete-item';
        item.style.padding = '8px 14px';
        item.style.color = '#888';
        item.textContent = 'Obat tidak ditemukan';
        list.appendChild(item);
        list.style.display = 'block';
        if(stokSpan) { 
            stokSpan.style.display = 'none'; 
            stokSpan.textContent = ''; 
        }
    }
    
    // Jika user ketik nama yang match, langsung tampilkan stok (tanpa klik)
    const match = obatList.find(obat => obat.nama_obat.toLowerCase() === val);
    if(match && stokSpan) {
        stokSpan.textContent = 'Stok: ' + match.stok;
        stokSpan.style.display = 'inline-block';
        stokSpan.style.fontSize = '10px';
        stokSpan.style.padding = '2px 6px';
        
        // Color coding based on stock level
        if (match.stok <= 10) {
            stokSpan.className = 'badge badge-danger mb-1';
        } else if (match.stok <= 50) {
            stokSpan.className = 'badge badge-warning mb-1';
        } else {
            stokSpan.className = 'badge badge-success mb-1';
        }
    } else if(stokSpan && !found) {
        stokSpan.style.display = 'none';
        stokSpan.textContent = '';
    }
}

// Tambah baris obat dinamis
function tambahObat() {
    const html = `
        <div class="obat-row">
            <div class="row align-items-end">
                <div class="col-12 mb-3">
                    <label class="small text-muted mb-1">Obat Manual</label>
                    <textarea class="form-control" name="obat_manual[]" rows="2" placeholder="Nama obat + dosis + frekuensi&#10;Contoh: Paracetamol 500mg 3x1 sehari"></textarea>
                </div>
                <div class="col-4 mb-3 position-relative">
                    <label class="small text-muted mb-1">Cari dari Database</label>
                    <input type="text" class="form-control obat-autocomplete" placeholder="Cari obat..." autocomplete="off" oninput="cariObat(this)">
                    <input type="hidden" name="obat_db[]">
                    <div class="autocomplete-list shadow-sm" style="position:absolute;top:100%;left:0;right:0;z-index:1000;background:#fff;border:1px solid #ddd;border-radius:0.5rem;max-height:200px;overflow-y:auto;display:none;"></div>
                </div>
                <div class="col-3 mb-3">
                    <label class="small text-muted mb-1">Jumlah</label>
                    <input type="number" class="form-control" name="jumlah_obat[]" placeholder="1" min="1" value="1">
                </div>
                <div class="col-3 mb-3">
                    <label class="small text-muted mb-1">Instruksi</label>
                    <input type="text" class="form-control" name="instruksi_obat[]" placeholder="3x1/hari">
                </div>
                <div class="col-2 mb-3 text-center">
                    <div class="d-flex flex-column align-items-center">
                        <span class="stok-obat badge badge-info mb-1" style="font-size:10px;display:none;"></span>
                        <button type="button" class="btn btn-danger btn-sm" style="width:32px; height:32px; padding:0;" onclick="this.closest('.obat-row').remove(); updateObatButtons();" title="Hapus baris obat">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.getElementById('obat-list').insertAdjacentHTML('beforeend', html);
    updateObatButtons();
}

function updateObatButtons() {
    const rows = document.querySelectorAll('#obat-list .obat-row');
    rows.forEach((row, idx) => {
        const btnDiv = row.querySelector('.col-2 .d-flex');
        if (!btnDiv) return;
        
        // Keep the stok span
        const stokSpan = btnDiv.querySelector('.stok-obat');
        btnDiv.innerHTML = '';
        if (stokSpan) btnDiv.appendChild(stokSpan);
        
        if (idx === 0) {
            btnDiv.insertAdjacentHTML('beforeend', `
                <button type="button" class="btn btn-success btn-sm" style="width:32px; height:32px; padding:0;" onclick="tambahObat()" title="Tambah baris obat">
                    <i class="bi bi-plus-lg"></i>
                </button>
            `);
        } else {
            btnDiv.insertAdjacentHTML('beforeend', `
                <button type="button" class="btn btn-danger btn-sm" style="width:32px; height:32px; padding:0;" onclick="this.closest('.obat-row').remove(); updateObatButtons();" title="Hapus baris obat">
                    <i class="bi bi-trash"></i>
                </button>
            `);
        }
    });
}

// Auto-resize textareas
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
    updateObatButtons();
    
    // Close autocomplete when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            document.querySelectorAll('.autocomplete-list').forEach(list => {
                list.style.display = 'none';
            });
        }
    });
    
    // Prevent autocomplete close when clicking inside
    document.addEventListener('click', function(e) {
        if (e.target.closest('.autocomplete-list')) {
            e.stopPropagation();
        }
    });
});

// Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
    const required = this.querySelectorAll('[required]');
    let isValid = true;
    
    required.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#ef4444';
            field.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
            isValid = false;
        } else {
            field.style.borderColor = '#10b981';
            field.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi!');
    }
});

// Auto-hide popup alert after 3.5 seconds
document.addEventListener('DOMContentLoaded', function() {
    var alertBox = document.getElementById('popup-alert');
    if(alertBox) {
        setTimeout(function() {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alertBox);
            bsAlert.close();
        }, 3500);
    }
});
</script>
<?= $this->endSection() ?>