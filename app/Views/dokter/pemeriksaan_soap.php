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
                <div class="mb-5 pb-4 border-bottom">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-center">
                            <span class="info-badge badge-info" style="font-size:1rem; font-weight:600; background:#007bff; color:#fff; border-radius:0.9rem; padding:10px 22px; box-shadow:0 2px 8px rgba(0,123,255,0.10); display:inline-block; min-width:70px; max-width:120px; overflow-wrap:anywhere; text-align:center; border:none; letter-spacing:1px;">
                                <?php
                                $no_rm = isset($pasien['no_rekam_medis']) ? $pasien['no_rekam_medis'] : (isset($pasien['no_rm']) ? $pasien['no_rm'] : 'RM-001');
                                $no_rm = str_replace(["\r", "\n"], ' ', $no_rm);
                                echo esc($no_rm);
                                ?>
                            </span>
                        </div>
                        <div class="col-lg-6 col-md-5 col-12">
                            <h2 class="gradient-text mb-2" style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.5px;">
                                <?= isset($pasien['nama_lengkap']) ? esc($pasien['nama_lengkap']) : 'Nama Pasien' ?>
                            </h2>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <div class="d-flex gap-2 mb-3" style="flex-wrap:nowrap;overflow-x:auto;">
                                    <span class="info-badge badge-info">
                                    <i class="bi bi-person"></i>
                                    <?= isset($pasien['jenis_kelamin']) ? esc($pasien['jenis_kelamin']) : '-' ?>
                                </span>
                                    <span class="info-badge badge-warning">
                                    <i class="bi bi-calendar-heart"></i>
                                    <?php
                                    if (isset($pasien['usia']) && $pasien['usia']) {
                                        echo esc($pasien['usia']) . ' Tahun';
                                    } elseif (isset($pasien['tgl_lahir']) && $pasien['tgl_lahir']) {
                                        $lahir = new DateTime($pasien['tgl_lahir']);
                                        $today = new DateTime();
                                        $umur = $today->diff($lahir)->y;
                                        echo $umur . ' Tahun';
                                    } else {
                                        echo '0 Tahun';
                                    }
                                    ?>
                                    </span>
                                </div>
                            </div>
                            <?php if(isset($pasien['tgl_lahir']) && $pasien['tgl_lahir']): ?>
                            <div style="color: #6b7280; font-size: 14px;">
                                <i class="bi bi-cake2"></i> <?= esc($pasien['tgl_lahir']) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="d-flex flex-column gap-2">
                                <span class="info-badge badge-success">
                                    <i class="bi bi-clock"></i>
                                    <?= isset($waktu_pemeriksaan) ? esc($waktu_pemeriksaan) : date('d-m-Y H:i') ?>
                                </span>
                                <span class="info-badge badge-warning">
                                    <i class="bi bi-hospital"></i>
                                    <?= isset($poli) ? esc($poli) : 'Poli Umum' ?>
                                </span>
                                <span class="info-badge badge-info">
                                    <i class="bi bi-person-badge"></i>
                                    <?= isset($dokter) ? esc($dokter) : 'dr. Dokter' ?>
                                </span>
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
                                <label class="form-label mb-2">
                                    <i class="bi bi-capsule me-2"></i>Resep Obat
                                </label>
                                <div id="obat-list" class="flex-grow-1">
                                    <div class="obat-row">
                                        <div class="form-row align-items-center">
                                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                <span>Tambah obat Manual</span>
                                                <textarea class="form-control" name="obat_manual[]" rows="2" placeholder="Nama obat + dosis + frekuensi"></textarea>
                                            </div>
                                            <div class="col-12 col-md-5 mb-2 mb-md-0 position-relative">
                                                <span>Cari Obat dari database</span>
                                                <input type="text" class="form-control obat-autocomplete" placeholder="Cari obat..." autocomplete="off" style="border-radius:0.75rem; min-height:40px; font-size:15px;" oninput="cariObat(this)">
                                                <input type="hidden" name="obat_db[]">
                                                <div class="autocomplete-list shadow-sm" style="position:absolute;top:100%;left:0;right:0;z-index:10;background:#fff;border-radius:0.5rem;max-height:180px;overflow-y:auto;display:none;"></div>
                                            </div>
                                            <div class="col-12 col-md-1 d-flex justify-content-end align-items-center">
                                                <span class="stok-obat badge bg-info text-dark me-2" style="font-size:13px;display:none;"></span>
                                                <button type="button" class="btn btn-success btn-sm d-flex align-items-center justify-content-center p-0" style="width:36px; height:36px; font-size:22px;" onclick="tambahObat()" title="Tambah baris obat"><i class="bi bi-plus-lg"></i></button>
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
    const wrapper = input.closest('.col-12.col-md-5');
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
        if(stokSpan) { stokSpan.style.display = 'none'; stokSpan.textContent = ''; }
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
            item.innerHTML = `<b>${obat.nama_obat}</b> <span class='badge bg-light text-dark ms-2'>Stok: ${obat.stok}</span>`;
            item.onclick = function() {
                input.value = obat.nama_obat;
                hidden.value = obat.id_obat;
                list.style.display = 'none';
                if(stokSpan) { stokSpan.textContent = 'Stok: ' + obat.stok; stokSpan.style.display = 'inline-block'; }
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
        if(stokSpan) { stokSpan.style.display = 'none'; stokSpan.textContent = ''; }
    }
    // Jika user ketik nama yang match, langsung tampilkan stok (tanpa klik)
    const match = obatList.find(obat => obat.nama_obat.toLowerCase() === val);
    if(match && stokSpan) {
        stokSpan.textContent = 'Stok: ' + match.stok;
        stokSpan.style.display = 'inline-block';
    } else if(stokSpan) {
        stokSpan.style.display = 'none';
        stokSpan.textContent = '';
    }
}

// Tambah baris obat dinamis
function tambahObat() {
    const html = `
        <div class=\"obat-row\">
            <div class=\"form-row align-items-center\">
                <div class=\"col-12 col-md-6 mb-2 mb-md-0\">
                    <textarea class=\"form-control\" name=\"obat_manual[]\" rows=\"2\" placeholder=\"Nama obat + dosis + frekuensi\"></textarea>
                </div>
                <div class=\"col-12 col-md-5 mb-2 mb-md-0 position-relative\">
                    <input type=\"text\" class=\"form-control obat-autocomplete\" placeholder=\"Cari obat...\" autocomplete=\"off\" style=\"border-radius:0.75rem; min-height:40px; font-size:15px;\" oninput=\"cariObat(this)\">
                    <input type=\"hidden\" name=\"obat_db[]\">
                    <div class=\"autocomplete-list shadow-sm\" style=\"position:absolute;top:100%;left:0;right:0;z-index:10;background:#fff;border-radius:0.5rem;max-height:180px;overflow-y:auto;display:none;\"></div>
                </div>
                <div class=\"col-12 col-md-1 d-flex justify-content-end align-items-center\">
                    <span class=\"stok-obat badge bg-info text-dark me-2\" style=\"font-size:13px;display:none;\"></span>
                    <button type=\"button\" class=\"btn btn-danger btn-sm\" style=\"width:36px; height:36px; font-size:22px;\" onclick=\"this.closest('.obat-row').remove(); updateObatButtons();\" title=\"Hapus baris obat\"><i class=\"bi bi-trash\"></i></button>
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
        const btnCol = row.querySelector('.col-12.col-md-1');
        if (!btnCol) return;
        btnCol.innerHTML = '';
        if (idx === 0) {
            btnCol.innerHTML = `<button type="button" class="btn btn-success btn-sm" style="min-width:36px; min-height:36px; display:flex; align-items:center; justify-content:center;" onclick="tambahObat()" title="Tambah baris obat"><i class="bi bi-plus-lg"></i></button>`;
        } else {
            btnCol.innerHTML = `<button type="button" class="btn btn-danger btn-sm" style="min-width:36px; min-height:36px; display:flex; align-items:center; justify-content:center;" onclick="this.closest('.obat-row').remove(); updateObatButtons();" title="Hapus baris obat"><i class="bi bi-trash"></i></button>`;
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