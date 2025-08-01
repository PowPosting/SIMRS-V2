<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
.progress-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 15px 0;
}

.step-container {
    display: flex;
    align-items: center;
    width: 80%;
    max-width: 600px;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
    position: relative;
}

.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 13px;
    margin-bottom: 6px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.step-title {
    font-size: 11px;
    color: #6c757d;
    font-weight: 500;
    line-height: 1.2;
}

.step.active .step-number {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
    transform: scale(1.1);
}

.step.active .step-title {
    color: #007bff;
    font-weight: bold;
}

.step.completed .step-number {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}

.step.completed .step-title {
    color: #28a745;
    font-weight: 600;
}

.step-line {
    height: 2px;
    background-color: #e9ecef;
    flex: 1;
    margin: 0 8px;
    margin-bottom: 26px;
    border-radius: 1px;
}

.step.completed ~ .step-line {
    background-color: #28a745;
}

.step.completed + .step-line {
    background-color: #28a745;
}

.step.active ~ .step-line {
    background-color: #e9ecef;
}

.step.active + .step-line {
    background-color: #e9ecef;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
}

.confirmation-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 20px;
}

.confirmation-card .card-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-bottom: none;
    border-radius: 7px 7px 0 0;
}

.confirmation-item {
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.confirmation-item:last-child {
    border-bottom: none;
}

.confirmation-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 4px;
}

.confirmation-value {
    color: #6c757d;
    font-size: 14px;
}


/* Modern Final Confirmation Card */
.final-confirmation {
    background: linear-gradient(135deg, #6ea8fe 0%, #a685e2 100%);
    color: #fff;
    border: none;
    border-radius: 13px;
    padding: 22px 18px 18px 18px;
    margin: 18px auto 18px auto;
    box-shadow: 0 6px 24px rgba(80, 80, 160, 0.13), 0 1.5px 6px rgba(0,0,0,0.07);
    position: relative;
    overflow: hidden;
    max-width: 410px;
    min-width: 220px;
}

.final-confirmation label,
.final-confirmation .form-check-label {
    color: #fff;
    text-shadow: 0 1px 2px rgba(0,0,0,0.10);
    font-weight: 500;
    font-size: 1em;
}

.final-confirmation .form-group label {
    font-size: 1em;
    font-weight: 600;
    letter-spacing: 0.01em;
}

/* Nama dokter pada daftar dokter tersedia */
.daftar-dokter-nama {
    color: #222 !important;
    font-weight: bold;
}


.final-confirmation select.form-control,
    background: #fff;
    border: 1.5px solid #d1d5db;
    border-radius: 10px;
    color: #222;
    font-size: 1.08em;
    font-weight: 500;
    box-shadow: none;
    margin-bottom: 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
    height: 2.8em;
    min-width: 120px;
    padding: 0.6em 1.1em;
    appearance: none;
}
.final-confirmation select.form-control:focus,
.final-confirmation input.form-control:focus {
    border-color: #7b2ff2;
    outline: none;
    box-shadow: 0 0 0 2px #b3c6ff44;
    background: #f7f7ff;
    padding: 0.5em 1em;
    font-size: 1em;
    color: #222;
}
}

.final-confirmation .form-group {
    margin-bottom: 1.1rem;
}

.final-confirmation .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #7b2ff2;
    border-color: #7b2ff2;
}

.final-confirmation .custom-control-label {
    font-size: 1.04em;
    font-weight: 500;
    color: #fff;
}

.final-confirmation .form-check-input {
    accent-color: #7b2ff2;
    margin-right: 12px;
}

.final-confirmation .invalid-feedback,
.final-confirmation .alert-danger {
    color: #fff;
    background: rgba(255,0,0,0.08);
    border-radius: 6px;
    padding: 6px 12px;
    margin-top: 6px;
    font-size: 0.98em;
}

.final-confirmation .btn-light {
    background: linear-gradient(90deg, #7b2ff2 0%, #4f8cff 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1em;
    padding: 0.55em 1.3em;
    box-shadow: 0 1px 4px rgba(80, 80, 160, 0.07);
    transition: background 0.2s, color 0.2s;
}
.final-confirmation .btn-light:hover {
    background: linear-gradient(90deg, #4f8cff 0%, #7b2ff2 100%);
    color: #fff;
}

.final-confirmation .btn-outline-light {
    border: 1.5px solid #fff;
    color: #fff;
    background: transparent;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1em;
    padding: 0.55em 1.3em;
    transition: background 0.2s, color 0.2s;
}
.final-confirmation .btn-outline-light:hover {
    background: #fff;
    color: #7b2ff2;
}

.final-confirmation .form-control:disabled {
    background: #f3f6fa;
    color: #b0b0b0;
    font-size: 1em;
}

@media (max-width: 768px) {
    .final-confirmation {
        padding: 8px 1vw 8px 1vw;
        max-width: 98vw;
    }
    .final-confirmation .form-group label {
        font-size: 0.97em;
    }
}

.card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.card-body {
    background: #fff;
    border-radius: 8px;
}

.container-fluid {
    padding: 20px;
}

.final-confirmation select.form-control {
    background-color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    height: calc(2.5em + 0.75rem + 2px);
    font-size: 1.1em;
    color: #495057;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.final-confirmation label {
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.final-confirmation .form-check-input {
    transform: scale(1.2);
    margin-right: 12px;
}

.final-confirmation .form-check-label {
    font-size: 16px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .step-container {
        width: 95%;
    }
    
    .step-title {
        font-size: 9px;
    }
    
    .step-number {
        width: 26px;
        height: 26px;
        font-size: 11px;
    }
    
    .step-line {
        margin: 0 4px;
        margin-bottom: 22px;
    }
}

@media (max-width: 480px) {
    .step-title {
        font-size: 8px;
        max-width: 60px;
    }
    
    .step-number {
        width: 24px;
        height: 24px;
        font-size: 10px;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Progress Steps -->
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="progress-steps mb-4">
                                <div class="step-container">
                                    <div class="step completed">
                                        <div class="step-number">1</div>
                                        <div class="step-title">Informasi Utama</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step completed">
                                        <div class="step-number">2</div>
                                        <div class="step-title">Informasi Alamat</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step completed">
                                        <div class="step-number">3</div>
                                        <div class="step-title">Informasi Penunjang</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step completed">
                                        <div class="step-number">4</div>
                                        <div class="step-title">Kontak Darurat</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step active">
                                        <div class="step-number">5</div>
                                        <div class="step-title">Konfirmasi Akhir</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body pt-0">
                    <div class="text-center mb-4">
                        <div class="confirmation-icon">
                            <i class="fas fa-clipboard-check fa-4x text-success mb-3"></i>
                        </div>
                        <h4>Pendaftaran Pasien Baru</h4>
                        <p class="text-muted">Semua data telah diisi. Silakan konfirmasi untuk melanjutkan ke Nurse Station.</p>
                    </div>

                    <!-- Konfirmasi Final -->
                    <div class="final-confirmation">
                        <form action="<?= base_url('admisi/registrasi-pasien/save-step5') ?>" method="post" id="finalForm" class="needs-validation" novalidate>
                            <?= csrf_field() ?>
                            
                            <!-- Pilih Poliklinik -->
                            <div class="form-group mb-4">
                                <label for="id_poli" class="font-weight-bold">Pilih Poliklinik <span class="text-danger">*</span></label>
                                <select class="form-control" id="id_poli" name="id_poli" required>
                                    <option value="">Pilih Poliklinik</option>
                                    <?php foreach ($poli_list as $poli): ?>
                                    <option value="<?= $poli['id'] ?>"><?= esc($poli['nama']) ?> (<?= esc($poli['kode']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (session('errors.id_poli')): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= session('errors.id_poli') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Jadwal Dokter tersedia Dokter -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Daftar Dokter yang Tersedia Hari Ini</label>
                                <div id="daftar-dokter-available">
                                    <div class="text-muted">Silakan pilih poliklinik untuk melihat daftar dokter.</div>
                                </div>
                            </div>
                            
                            <?php if (session('error')): ?>
                            <div class="alert alert-danger">
                                <?= session('error') ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="konfirmasi_data" name="konfirmasi_data" value="1" required>
                                    <label class="custom-control-label" for="konfirmasi_data">
                                        Konfirmasi Bahwa Data Telah Benar
                                    </label>
                                    <div class="invalid-feedback">
                                        Anda harus menyetujui pernyataan ini
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-center mt-4">
                                <a href="<?= base_url('admisi/registrasi-pasien/step4') ?>" class="btn btn-outline-light btn-lg mr-3">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-light btn-lg">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Daftar Pasien
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> <!-- End content section -->

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
// Data dokter per poliklinik dan hari ini (server-side render, versi byPoli)
const dokterJadwalToday = <?php
    $hariMap = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];
    $hariEn = date('l');
    $hariIni = isset($hariMap[$hariEn]) ? $hariMap[$hariEn] : $hariEn;
    $dokterJadwalToday = [];
    if (!empty($jadwalListByPoli)) {
        foreach ($jadwalListByPoli as $poliId => $jadwalArr) {
            foreach ($jadwalArr as $jadwal) {
                if (strtolower($jadwal['hari']) == strtolower($hariIni)) {
                    // Cari data dokter
                    $dokter = null;
                    foreach ($dokterList as $d) {
                        if ($d['id'] == $jadwal['dokter_id']) {
                            $dokter = $d;
                            break;
                        }
                    }
                    if ($dokter) {
                        $dokterJadwalToday[$poliId][] = [
                            'id' => $dokter['id'],
                            'nama' => isset($dokter['nama_lengkap']) ? $dokter['nama_lengkap'] : (isset($dokter['nama_lengkap']) ? $dokter['nama'] : '[Tanpa Nama]'),
                            'spesialisasi' => isset($dokter['spesialisasi']) ? $dokter['spesialisasi'] : '',
                            'jam_mulai' => $jadwal['jam_mulai'],
                            'jam_selesai' => $jadwal['jam_selesai'],
                        ];
                    }
                }
            }
        }
    }
    echo json_encode($dokterJadwalToday);
?>;
console.log('dokterJadwalToday:', dokterJadwalToday);
console.log('dokterList:', <?php echo json_encode($dokterList); ?>);
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('finalForm');
    const checkbox = document.getElementById('konfirmasi_data');
    const submitBtn = form.querySelector('button[type="submit"]');
    const poliSelect = document.getElementById('id_poli');
    const daftarDokterDiv = document.getElementById('daftar-dokter-available');

    // Disable submit button initially
    submitBtn.disabled = true;

    function updateDaftarDokter() {
        const poliId = poliSelect.value;
        daftarDokterDiv.innerHTML = '';
        if (poliId && dokterJadwalToday[poliId] && dokterJadwalToday[poliId].length > 0) {
            dokterJadwalToday[poliId].forEach(function(dok) {
                const card = document.createElement('div');
                card.className = 'card mb-2';
                card.innerHTML = `
                    <div class="card-body py-2 px-3">
                        <div class="daftar-dokter-nama">${dok.nama ? dok.nama : '<span class=\'text-danger\'>[Nama dokter tidak ditemukan]</span>'}</div>
                        <div class="small text-muted">${dok.spesialisasi ? dok.spesialisasi : ''}</div>
                        <div class="small">Jam: <span class="badge badge-info">${dok.jam_mulai} - ${dok.jam_selesai}</span></div>
                    </div>
                `;
                daftarDokterDiv.appendChild(card);
            });
        } else if (poliId) {
            daftarDokterDiv.innerHTML = '<div class="text-danger">Tidak ada dokter tersedia hari ini di poliklinik ini.</div>';
        } else {
            daftarDokterDiv.innerHTML = '<div class="text-muted">Silakan pilih poliklinik untuk melihat daftar dokter.</div>';
        }
    }

    function validateForm() {
        const poliklinik = poliSelect.value;
        // Tidak perlu validasi dokter, cukup poliklinik dan konfirmasi
        const isValid = checkbox.checked && poliklinik !== '';
        submitBtn.disabled = !isValid;
        return isValid;
    }

    poliSelect.addEventListener('change', function() {
        updateDaftarDokter();
    });
    // Inisialisasi awal
    updateDaftarDokter();
    validateForm();
    checkbox.addEventListener('change', validateForm);
});
</script>
<?= $this->endSection() ?>
