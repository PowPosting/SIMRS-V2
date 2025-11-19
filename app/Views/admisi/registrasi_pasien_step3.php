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

.step.completed + .step-line {
    background-color: #28a745;
}
.form-group {
    margin-bottom: 1rem;
}
/* Custom button style */
.btn-primary {
    background: #28a745 !important;
    border: none !important;
    color: white !important;
    padding: 10px 20px !important;
    transition: all 0.3s ease !important;
}

.btn-primary:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(43, 50, 128, 0.2) !important;
}

.btn-primary:active {
    transform: translateY(0) !important;
}

label {
    margin-bottom: 0.5rem !important;
    font-weight: 500 !important;
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
                                    <div class="step active">
                                        <div class="step-number">3</div>
                                        <div class="step-title">Informasi Penunjang</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step">
                                        <div class="step-number">4</div>
                                        <div class="step-title">Kontak Darurat</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step">
                                        <div class="step-number">5</div>
                                        <div class="step-title">Konfirmasi Akhir</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body pt-0">
                    <form action="<?= base_url('admisi/registrasi-pasien/save-step3') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agama">Agama</label>
                                    <select class="form-control" id="agama" name="agama">
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" <?= ($step3_data['agama'] ?? '') == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                        <option value="Kristen Protestan" <?= ($step3_data['agama'] ?? '') == 'Kristen Protestan' ? 'selected' : '' ?>>Kristen Protestan</option>
                                        <option value="Kristen Katolik" <?= ($step3_data['agama'] ?? '') == 'Kristen Katolik' ? 'selected' : '' ?>>Kristen Katolik</option>
                                        <option value="Hindu" <?= ($step3_data['agama'] ?? '') == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                        <option value="Buddha" <?= ($step3_data['agama'] ?? '') == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                        <option value="Khonghucu" <?= ($step3_data['agama'] ?? '') == 'Khonghucu' ? 'selected' : '' ?>>Khonghucu</option>
                                        <option value="Lainnya" <?= ($step3_data['agama'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="golongan_darah">Golongan Darah</label>
                                    <select class="form-control" id="golongan_darah" name="golongan_darah">
                                        <option value="">Pilih Golongan Darah</option>
                                        <?php
                                        $goldarOptions = ['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Tidak Tahu'];
                                        foreach ($goldarOptions as $opt) {
                                            $selected = ($step3_data['golongan_darah'] ?? '') == $opt ? 'selected' : '';
                                            echo "<option value='$opt' $selected>$opt</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                    <select class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir">
                                        <option value="">Pilih Pendidikan Terakhir</option>
                                        <?php
                                        $pendidikanOptions = ['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Lainnya'];
                                        foreach ($pendidikanOptions as $opt) {
                                            $selected = ($step3_data['pendidikan_terakhir'] ?? '') == $opt ? 'selected' : '';
                                            echo "<option value='$opt' $selected>$opt</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kewarganegaraan">Kewarganegaraan</label>
                                    <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" placeholder="Contoh: Indonesia" value="<?= $step3_data['kewarganegaraan'] ?? 'Indonesia' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="suku">Suku</label>
                                    <input type="text" class="form-control" id="suku" name="suku" placeholder="Contoh: Jawa, Batak, Minang, dll" value="<?= $step3_data['suku'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_di_kartu_pasien">Nama Di Kartu Pasien</label>
                                    <input type="text" class="form-control" id="nama_di_kartu_pasien" name="nama_di_kartu_pasien" placeholder="Akan diisi otomatis dari nama lengkap" value="<?= $step3_data['nama_di_kartu_pasien'] ?? '' ?>">
                                    <small class="form-text text-muted">Nama ini akan diambil dari data step 1</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_telepon_rumah">No Telepon Rumah</label>
                                    <input type="text" class="form-control" id="no_telepon_rumah" name="no_telepon_rumah" placeholder="Contoh: 021-1234567" value="<?= $step3_data['no_telepon_rumah'] ?? '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pekerjaan">Pekerjaan</label>
                                    <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Contoh: Pegawai Swasta, Wiraswasta, PNS, dll" value="<?= $step3_data['pekerjaan'] ?? '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <a href="<?= base_url('admisi/registrasi-pasien/step2') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnNext">
                                <i class="fas fa-arrow-right mr-2"></i>
                                Selanjutnya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill nama di kartu pasien dari session data step 1
    const namaKartuPasien = document.getElementById('nama_di_kartu_pasien');
    
    // Simulasi mengambil data dari session atau local storage
    // Nanti akan diisi dari controller dengan data session
    const namaLengkap = "<?= session('pasien_step1')['nama_lengkap'] ?? '' ?>";
    if (namaLengkap) {
        namaKartuPasien.value = namaLengkap;
    }
    
    // Debug form submission
    const form = document.querySelector('form');
    const btnNext = document.getElementById('btnNext');
    
    // Tidak ada field required di step 3, validasi dihapus
});
</script>
<?= $this->endSection() ?>
