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
                <!-- Progress Steps -->
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="progress-steps mb-4">
                                <div class="step-container">
                                    <div class="step active">
                                        <div class="step-number">1</div>
                                        <div class="step-title">Informasi Utama</div>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step">
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
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('admisi/registrasi-pasien/save') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Titel Pasien <span class="text-danger">*</span></label>
                                    <select class="form-control" id="title" name="title" required>
                                        <option value="">Pilih Titel</option>
                                        <option value="Tn">Tn (Tuan)</option>
                                        <option value="Ny">Ny (Nyonya)</option>
                                        <option value="Nn">Nn (Nona)</option>
                                        <option value="An">An (Anak)</option>
                                        <option value="By">By (Bayi)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap Pasien <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_identitas">Nomor KTP/SIM/Passport <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_identitas" name="nomor_identitas" required autocomplete="off">
                                    <small id="nomor_identitas_feedback" class="form-text"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_hp">Nomor HP/WA <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_perkawinan">Status Perkawinan <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status_perkawinan" name="status_perkawinan" required autocomplete="off">
                                        <option value="">Pilih Status Perkawinan</option>
                                        <option value="menikah" <?= (old('status_perkawinan') == 'menikah') ? 'selected' : '' ?>>Menikah</option>
                                        <option value="belum_menikah" <?= (old('status_perkawinan') == 'belum_menikah') ? 'selected' : '' ?>>Belum Menikah</option>
                                        <option value="janda" <?= (old('status_perkawinan') == 'janda') ? 'selected' : '' ?>>Janda</option>
                                        <option value="duda" <?= (old('status_perkawinan') == 'duda') ? 'selected' : '' ?>>Duda</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="foto-identitas">Upload Dokumen Identitas(KTP/SIM) <span class="text-danger">*</span></label>
                                  <input type="file" class="form-control" id="foto-identitas" name="foto-identitas" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validasi AJAX nomor identitas unik
    const nomorIdentitasInput = document.getElementById('nomor_identitas');
    const feedback = document.getElementById('nomor_identitas_feedback');
    let lastCheck = '';
    nomorIdentitasInput.addEventListener('input', function() {
        const val = nomorIdentitasInput.value.trim();
        feedback.textContent = '';
        feedback.className = 'form-text';
        if (val.length < 16) {
            feedback.textContent = ' 16 karakter.';
            feedback.classList.add('text-danger');
            nomorIdentitasInput.setCustomValidity('Nomor identitas terlalu pendek');
            return;
        }
        if (val === lastCheck) return;
        lastCheck = val;
        fetch('<?= base_url('admisi/registrasi-pasien/cek-nomor-identitas') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name=csrf_token]')?.value || ''
            },
            body: 'nomor_identitas=' + encodeURIComponent(val)
        })
        .then(r => r.json())
        .then(data => {
            if (data.exists) {
                feedback.textContent = 'Nomor identitas sudah terdaftar.';
                feedback.classList.add('text-danger');
                nomorIdentitasInput.setCustomValidity('Nomor identitas sudah terdaftar');
            } else {
                feedback.textContent = 'Nomor identitas tersedia.';
                feedback.classList.add('text-success');
                nomorIdentitasInput.setCustomValidity('');
            }
        })
        .catch(() => {
            feedback.textContent = 'Gagal cek ke server.';
            feedback.classList.add('text-danger');
            nomorIdentitasInput.setCustomValidity('Gagal cek ke server');
        });
    });
});
</script>

<?= $this->endSection() ?>
