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
                                    <div class="step completed">
                                        <div class="step-number">3</div>
                                        <div class="step-title">Informasi Penunjang</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step active">
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
                    <form action="<?= base_url('admisi/registrasi-pasien/save-step4') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Kontak Darurat/Keluarga -->
                        <h5 class="section-title"><i class="fas fa-user-friends mr-2"></i>Kontak Darurat/Keluarga</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_kontak">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_kontak" name="nama_kontak" 
                                           value="<?= old('nama_kontak', $step4_data['nama_kontak'] ?? '') ?>" required placeholder="Nama lengkap kontak darurat">
                                    <?php if (isset($errors['nama_kontak'])): ?>
                                        <div class="text-danger"><?= $errors['nama_kontak'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hubungan">Hubungan <span class="text-danger">*</span></label>
                                    <select class="form-control" id="hubungan" name="hubungan" required>
                                        <option value="">Pilih Hubungan</option>
                                        <option value="ayah" <?= (old('hubungan', $step4_data['hubungan'] ?? '') == 'ayah') ? 'selected' : '' ?>>Ayah</option>
                                        <option value="ibu" <?= (old('hubungan', $step4_data['hubungan'] ?? '') == 'ibu') ? 'selected' : '' ?>>Ibu</option>
                                        <option value="suami" <?= (old('hubungan', $step4_data['hubungan'] ?? '') == 'suami') ? 'selected' : '' ?>>Suami</option>
                                        <option value="istri" <?= (old('hubungan', $step4_data['hubungan'] ?? '') == 'istri') ? 'selected' : '' ?>>Istri</option>
                                        <option value="anak" <?= (old('hubungan', $step4_data['hubungan'] ?? '') == 'anak') ? 'selected' : '' ?>>Anak</option>
                                        <option value="saudara" <?= (old('hubungan', $step4_data['hubungan'] ?? '') == 'saudara') ? 'selected' : '' ?>>Saudara</option>
                                        <option value="lainnya" <?= (old('hubungan', $step4_data['hubungan'] ?? '') == 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                    <?php if (isset($errors['hubungan'])): ?>
                                        <div class="text-danger"><?= $errors['hubungan'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_hp">Nomor HP <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp" 
                                           value="<?= old('nomor_hp', $step4_data['nomor_hp'] ?? '') ?>" required placeholder="Contoh: 08123456789">
                                    <?php if (isset($errors['nomor_hp'])): ?>
                                        <div class="text-danger"><?= $errors['nomor_hp'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="alamat" name="alamat" 
                                              rows="3" required placeholder="Alamat lengkap"><?= old('alamat', $step4_data['alamat'] ?? '') ?></textarea>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="sama_dengan_pasien">
                                        <label class="form-check-label" for="sama_dengan_pasien">
                                            Sama dengan alamat pasien
                                        </label>
                                    </div>
                                    <?php if (isset($errors['alamat'])): ?>
                                        <div class="text-danger"><?= $errors['alamat'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <a href="<?= base_url('admisi/registrasi-pasien/step3') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
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
$(document).ready(function() {
    // Data alamat dari session step 2
    <?php 
    $session = session();
    $step2Data = $session->get('pasien_step2') ?? [];
    ?>
    const alamatPasien = {
        alamat_lengkap: <?= json_encode($step2Data['alamat_lengkap'] ?? '') ?>,
        kelurahan: <?= json_encode($step2Data['kelurahan'] ?? '') ?>,
        kecamatan: <?= json_encode($step2Data['kecamatan'] ?? '') ?>,
        kabupaten_kota: <?= json_encode($step2Data['kabupaten_kota'] ?? '') ?>,
        provinsi: <?= json_encode($step2Data['provinsi'] ?? '') ?>,
        kode_pos: <?= json_encode($step2Data['kode_pos'] ?? '') ?>
    };

    // Debug: tampilkan data di console
    console.log('Data alamat pasien:', alamatPasien);

    // Handle checkbox untuk menggunakan alamat yang sama
    $('#sama_dengan_pasien').change(function() {
        if ($(this).is(':checked')) {
            // Validasi apakah ada data
            if (!alamatPasien.alamat_lengkap) {
                alert('Data alamat pasien tidak ditemukan. Pastikan Anda sudah mengisi Step 2.');
                $(this).prop('checked', false);
                return;
            }

            // Gabungkan alamat lengkap dengan detail wilayah
            let alamatLengkap = alamatPasien.alamat_lengkap;
            if (alamatPasien.kelurahan) {
                alamatLengkap += ', Kel. ' + alamatPasien.kelurahan;
            }
            if (alamatPasien.kecamatan) {
                alamatLengkap += ', Kec. ' + alamatPasien.kecamatan;
            }
            if (alamatPasien.kabupaten_kota) {
                alamatLengkap += ', ' + alamatPasien.kabupaten_kota;
            }
            if (alamatPasien.provinsi) {
                alamatLengkap += ', ' + alamatPasien.provinsi;
            }
            if (alamatPasien.kode_pos) {
                alamatLengkap += ' ' + alamatPasien.kode_pos;
            }
            
            console.log('Alamat yang akan diisi:', alamatLengkap);
            $('#alamat').val(alamatLengkap);
            $('#alamat').prop('readonly', true);
        } else {
            $('#alamat').val('');
            $('#alamat').prop('readonly', false);
        }
    });
});
</script>
<?= $this->endSection() ?>