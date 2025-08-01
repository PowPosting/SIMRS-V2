<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="h4 mb-0 text-gray-800">
                    <i class="fas fa-heartbeat text-danger mr-2"></i>
                    Form Tanda Vital Pasien
                </h2>
                <p class="text-muted small mb-0">Pengukuran dan pencatatan tanda-tanda vital pasien</p>
            </div>

            <div class="card">
                <?php if (isset($antrian) && !empty($antrian)): ?>
                    <!-- Patient Information -->
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-injured mr-2"></i>
                            Informasi Pasien
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <!-- Patient Details -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <strong>Nama Pasien:</strong><br>
                                <?= esc($antrian['nama_pasien'] ?? '-') ?>
                            </div>
                            <div class="col-md-2">
                                <strong>No. RM:</strong><br>
                                <?= esc($antrian['no_rm'] ?? '-') ?>
                            </div>
                            <div class="col-md-2">
                                <strong>No. Antrian:</strong><br>
                                <?= esc($antrian['no_antrian'] ?? '-') ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Poli Tujuan:</strong><br>
                                <?= esc($antrian['poli_tujuan'] ?? '-') ?>
                            </div>
                            <div class="col-md-2">
                                <strong>Status:</strong><br>
                                <span class="badge badge-primary"><?= esc($antrian['status'] ?? '-') ?></span>
                            </div>
                        </div>

                        <hr>

                        <!-- Form Header -->
                        <div class="text-center mb-4">
                            <h4 class="text-primary">
                                <i class="fas fa-heartbeat mr-2"></i>
                                Pengukuran Tanda Vital
                            </h4>
                            <p class="text-muted">Silakan isi semua field yang diperlukan dengan teliti</p>
                        </div>

                        <form id="formTandaVital" method="post" action="<?= base_url('perawat/simpan-pemeriksaan') ?>">
                            <input type="hidden" name="id_antrian" value="<?= esc($antrian['id']) ?>">

                            <!-- Tanda Vital Utama -->
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-line mr-2"></i>
                                Tanda Vital Utama
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="tekanan_darah" class="font-weight-bold">
                                        <i class="fas fa-tint text-danger mr-2"></i>Tekanan Darah
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="tekanan_darah" 
                                               name="tekanan_darah" placeholder="120/80" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">mmHg</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Format: sistol/diastol (contoh: 120/80)
                                    </small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="detak_jantung" class="font-weight-bold">
                                        <i class="fas fa-heartbeat text-danger mr-2"></i>Detak Jantung
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="detak_jantung" 
                                               name="detak_jantung" min="40" max="200" placeholder="72" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-success">
                                        <i class="fas fa-check-circle mr-1"></i>Normal: 60-100 x/menit
                                    </small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="suhu_tubuh" class="font-weight-bold">
                                        <i class="fas fa-thermometer-half text-warning mr-2"></i>Suhu Tubuh
                                    </label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" min="30" max="45" class="form-control" 
                                               id="suhu_tubuh" name="suhu_tubuh" placeholder="36.5" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">°C</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-success">
                                        <i class="fas fa-check-circle mr-1"></i>Normal: 36.1-37.2°C
                                    </small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Pengukuran Fisik -->
                            <h5 class="text-success mb-3">
                                <i class="fas fa-ruler-combined mr-2"></i>
                                Pengukuran Fisik
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="pernafasan" class="font-weight-bold">
                                        <i class="fas fa-lungs text-info mr-2"></i>Pernafasan
                                    </label>
                                    <div class="input-group">
                                        <input type="number" min="10" max="60" class="form-control" 
                                               id="pernafasan" name="pernafasan" placeholder="16" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-success">
                                        <i class="fas fa-check-circle mr-1"></i>Normal: 16-20 x/menit
                                    </small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="berat_badan" class="font-weight-bold">
                                        <i class="fas fa-weight text-success mr-2"></i>Berat Badan
                                    </label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" min="1" max="300" class="form-control" 
                                               id="berat_badan" name="berat_badan" placeholder="65.0" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Berat badan dalam kilogram
                                    </small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="tinggi_badan" class="font-weight-bold">
                                        <i class="fas fa-ruler-vertical text-success mr-2"></i>Tinggi Badan
                                    </label>
                                    <div class="input-group">
                                        <input type="number" min="30" max="250" class="form-control" 
                                               id="tinggi_badan" name="tinggi_badan" placeholder="170" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Tinggi badan dalam sentimeter
                                    </small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Keluhan & Catatan -->
                            <h5 class="text-warning mb-3">
                                <i class="fas fa-clipboard-list mr-2"></i>
                                Keluhan & Catatan
                            </h5>
                            
                            <div class="form-group">
                                <label for="keluhan" class="font-weight-bold">
                                    <i class="fas fa-comment-medical text-warning mr-2"></i>Keluhan Pasien
                                </label>
                                <textarea class="form-control" id="keluhan" name="keluhan" rows="4" 
                                          placeholder="Deskripsikan keluhan utama atau gejala yang dialami pasien..."></textarea>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>Opsional: Catat keluhan atau gejala yang perlu diperhatikan dokter
                                </small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="<?= base_url('perawat/antrian-pendaftaran') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Antrian
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-warning mr-2">
                                        <i class="fas fa-undo mr-2"></i>Reset Form
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>Simpan Tanda Vital
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                            <h4 class="text-dark">Data Antrian Tidak Ditemukan</h4>
                            <p class="text-muted mb-4">Maaf, data antrian pasien yang Anda cari tidak dapat ditemukan atau sudah tidak valid.</p>
                            <a href="<?= base_url('perawat/antrian-pendaftaran') ?>" class="btn btn-primary">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Antrian
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Form validation
    $('#formTandaVital').on('submit', function(e) {
        let isValid = true;
        
        // Validate required fields
        $(this).find('input[required]').each(function() {
            if ($(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });
        
        // Validate blood pressure format
        const bp = $('#tekanan_darah').val();
        const bpPattern = /^\d{2,3}\/\d{2,3}$/;
        if (bp && !bpPattern.test(bp)) {
            $('#tekanan_darah').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon periksa kembali data yang diinput!');
        }
    });
    
    // Real-time validation
    $('input[required]').on('blur', function() {
        if ($(this).val().trim() === '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
    
    // Blood pressure format validation
    $('#tekanan_darah').on('blur', function() {
        const bp = $(this).val();
        const bpPattern = /^\d{2,3}\/\d{2,3}$/;
        if (bp && !bpPattern.test(bp)) {
            $(this).addClass('is-invalid');
        } else if (bp) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });
});
</script>
<?= $this->endSection() ?>
