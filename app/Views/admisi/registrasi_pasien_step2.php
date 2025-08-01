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
}</style>
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
                                    <div class="step active">
                                        <div class="step-number">2</div>
                                        <div class="step-title">Informasi Alamat</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step">
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
                    <form action="<?= base_url('admisi/registrasi-pasien/save-step2') ?>" method="post" class="form-horizontal">
                        <?= csrf_field() ?>
                        

                        <div class="form-group mb-4">
                            <label class="form-label mb-3">Alamat yang akan digunakan:</label>
                        </div>

                        <hr class="my-4">

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="alamat_lengkap">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3" required placeholder="Masukkan alamat lengkap (nama jalan, nomor rumah, dll)"><?= old('alamat_lengkap') ?></textarea>
                                    <small class="form-text text-muted">Masukkan alamat lengkap termasuk nama jalan dan nomor rumah</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_pos">Kode Pos <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="kode_pos" name="kode_pos" maxlength="5" pattern="[0-9]{5}" placeholder="Contoh: 12345" value="<?= old('kode_pos', isset($alamat_data) ? $alamat_data['kode_pos'] : '') ?>" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-primary" id="btn-cari-kodepos">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Masukkan 5 digit kode pos, lalu klik "Cari" untuk mengisi alamat otomatis</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kelurahan">Kelurahan/Desa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kelurahan" name="kelurahan" required placeholder="Nama kelurahan/desa" value="<?= old('kelurahan', isset($alamat_data) ? $alamat_data['kelurahan'] : '') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kecamatan">Kecamatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kecamatan" name="kecamatan" required placeholder="Nama kecamatan" value="<?= old('kecamatan', isset($alamat_data) ? $alamat_data['kecamatan'] : '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kabupaten_kota">Kabupaten/Kota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kabupaten_kota" name="kabupaten_kota" required placeholder="Nama kabupaten/kota" value="<?= old('kabupaten_kota', isset($alamat_data) ? $alamat_data['kabupaten_kota'] : '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provinsi">Provinsi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="provinsi" name="provinsi" required placeholder="Nama provinsi" value="<?= old('provinsi', $alamat_data['provinsi'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <?php if (isset($debug_info)): ?>
                        <div class="alert alert-info">
                            <h5>Debug Info:</h5>
                            <pre><?php print_r($debug_info); ?></pre>
                        </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <a href="<?= base_url('admisi/registrasi-pasien') ?>" class="btn btn-secondary">
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

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Tombol cari di field kode pos
    $('#btn-cari-kodepos').click(function() {
        const kodePos = $('#kode_pos').val();
        
        // Validasi kode pos
        if (!kodePos || kodePos.length !== 5) {
            alert('Masukkan kode pos 5 digit yang valid');
            return;
        }
        
        // Lakukan AJAX request untuk mencari data alamat
        console.log('Mencari kode pos:', kodePos);
        $.ajax({
            url: '<?= base_url('admisi/cari-kodepos') ?>',
            type: 'GET',
            data: { kode_pos: kodePos },
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    // Isi field alamat dengan data yang ditemukan
                    $('#kelurahan').val(response.data.urban);
                    $('#kecamatan').val(response.data.sub_district);
                    $('#kabupaten_kota').val(response.data.city);
                    $('#provinsi').val(response.data.province_name);
                } else {
                    alert('Data kode pos tidak ditemukan');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mencari data kode pos');
            }
        });
    });
    
    // Hanya izinkan input angka di field kode pos
    $('#kode_pos').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 5) {
            this.value = this.value.slice(0, 5);
        }
    });
    
    // Enter key untuk pencarian
    $('#kode_pos').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $('#btn-cari-kodepos').click();
        }
    });
    

});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
