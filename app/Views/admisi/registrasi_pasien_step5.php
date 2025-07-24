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

.final-confirmation {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
                                    <option value="">-- Pilih Poliklinik --</option>
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
                            
                            <?php if (session('error')): ?>
                            <div class="alert alert-danger">
                                <?= session('error') ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="konfirmasi_data" name="konfirmasi_data" value="1" required>
                                    <label class="custom-control-label" for="konfirmasi_data">
                                        Saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan
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
                                    Daftar & Ke Nurse Station
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('finalForm');
    const checkbox = document.getElementById('konfirmasi_data');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Disable submit button initially
    submitBtn.disabled = true;
    
    // Enable/disable submit button based on checkbox and poliklinik
    function validateForm() {
        const poliklinik = document.getElementById('id_poli');
        const isValid = checkbox.checked && poliklinik.value !== '';
        submitBtn.disabled = !isValid;
        return isValid;
    }
    
    checkbox.addEventListener('change', validateForm);
    document.getElementById('id_poli').addEventListener('change', validateForm);
});
</script>
<?= $this->endSection() ?>
