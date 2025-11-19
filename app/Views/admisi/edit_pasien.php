<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .page-header-card {
        background-color: #007BFF;
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .page-header-card h1 {
        margin: 0;
        font-weight: 700;
        font-size: 1.8rem;
    }

    .section-card {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .section-header {
        background-color: #007BFF;
        color: white;
        padding: 1.5rem;
        border-bottom: none;
    }

    .section-header h6 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .section-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        font-weight: 600 !important;
        color: #495057 !important;
        font-size: 0.95rem !important;
        margin-bottom: 0.5rem !important;
        display: block !important;
    }

    .form-control {
        border-radius: 10px !important;
        border: 2px solid #e9ecef !important;
        padding: 10px 15px !important;
        transition: all 0.3s ease !important;
        font-size: 0.95rem !important;
        line-height: 1.5 !important;
        height: auto !important;
        min-height: 38px !important;
        width: 100% !important;
        display: block !important;
    }

    .form-control:focus {
        border-color: #007BFF !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        outline: none !important;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        opacity: 1 !important;
    }

    select.form-control {
        padding-right: 30px !important;
        appearance: auto !important;
        -webkit-appearance: menulist !important;
        -moz-appearance: menulist !important;
    }

    textarea.form-control {
        min-height: 100px !important;
        resize: vertical !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        border: none !important;
        padding: 12px 30px !important;
        border-radius: 25px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        color: white !important;
    }

    .btn-primary:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3) !important;
        color: white !important;
    }

    .btn-secondary {
        padding: 12px 30px !important;
        border-radius: 25px !important;
        font-weight: 600 !important;
        border: 2px solid #6c757d !important;
        background: transparent !important;
        color: #6c757d !important;
        transition: all 0.3s ease !important;
    }

    .btn-secondary:hover {
        background: #6c757d !important;
        color: white !important;
        transform: translateY(-2px) !important;
    }

    .text-danger {
        color: #e74c3c;
    }

    .action-buttons {
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-top: 2rem;
    }

    .form-control.is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff5f5 !important;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .alert {
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* SweetAlert2 Custom Styles */
    .swal2-popup.animated-popup {
        border-radius: 15px !important;
        padding: 2rem !important;
    }

    .swal2-title {
        font-size: 1.75rem !important;
        font-weight: 700 !important;
        color: #2c3e50 !important;
    }

    .swal2-html-container {
        font-size: 1rem !important;
        color: #6c757d !important;
    }

    .swal2-confirm {
        border-radius: 25px !important;
        padding: 10px 30px !important;
        font-weight: 600 !important;
        font-size: 1rem !important;
    }

    .swal2-icon {
        border-width: 4px !important;
    }

    @media (max-width: 768px) {
        .section-body {
            padding: 1.5rem;
        }
        
        .page-header-card {
            padding: 1.5rem;
        }
        
        .page-header-card h1 {
            font-size: 1.5rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card">
        <h1>
            <i class="fas fa-user-edit mr-3"></i>Edit Data Pasien
        </h1>
        <p class="mb-0 mt-2" style="opacity: 0.9;">Perbarui informasi data pasien dengan lengkap dan akurat</p>
    </div>


    
    <!-- Form -->
    <form action="<?= base_url('admisi/update-pasien/' . $pasien['id']) ?>" method="post" id="editPasienForm">
        <?= csrf_field() ?>
        
        <!-- Informasi Utama -->
        <div class="section-card">
            <div class="section-header">
                <h6><i class="fas fa-user mr-2"></i>Informasi Utama Pasien</h6>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_rm">Nomor Rekam Medis</label>
                            <input type="text" class="form-control" id="no_rm" value="<?= esc($pasien['no_rekam_medis'] ?? '') ?>" readonly disabled>
                            <small class="text-muted">Nomor RM tidak dapat diubah</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Titel Pasien <span class="text-danger">*</span></label>
                            <select class="form-control" id="title" name="title" required>
                                <option value="">Pilih Titel</option>
                                <option value="Tn" <?= $pasien['title'] == 'Tn' ? 'selected' : '' ?>>Tn (Tuan)</option>
                                <option value="Ny" <?= $pasien['title'] == 'Ny' ? 'selected' : '' ?>>Ny (Nyonya)</option>
                                <option value="Nn" <?= $pasien['title'] == 'Nn' ? 'selected' : '' ?>>Nn (Nona)</option>
                                <option value="An" <?= $pasien['title'] == 'An' ? 'selected' : '' ?>>An (Anak)</option>
                                <option value="By" <?= $pasien['title'] == 'By' ? 'selected' : '' ?>>By (Bayi)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap Pasien <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?= esc($pasien['nama_lengkap']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= $pasien['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $pasien['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                                   value="<?= esc($pasien['tempat_lahir']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                   value="<?= esc($pasien['tanggal_lahir']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_identitas">Nomor KTP/SIM/Passport <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nomor_identitas" name="nomor_identitas" 
                                   value="<?= esc($pasien['nomor_identitas']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="agama">Agama</label>
                            <select class="form-control" id="agama" name="agama">
                                <option value="">Pilih Agama</option>
                                <option value="Islam" <?= ($info_tambahan['agama'] ?? '') == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                <option value="Kristen" <?= ($info_tambahan['agama'] ?? '') == 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                                <option value="Katolik" <?= ($info_tambahan['agama'] ?? '') == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                <option value="Hindu" <?= ($info_tambahan['agama'] ?? '') == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                <option value="Buddha" <?= ($info_tambahan['agama'] ?? '') == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                <option value="Konghucu" <?= ($info_tambahan['agama'] ?? '') == 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status_perkawinan">Status Perkawinan</label>
                            <select class="form-control" id="status_perkawinan" name="status_perkawinan">
                                <option value="">Pilih Status Perkawinan</option>
                                <option value="menikah" <?= ($pasien['status_perkawinan'] ?? '') == 'menikah' ? 'selected' : '' ?>>Menikah</option>
                                <option value="belum_menikah" <?= ($pasien['status_perkawinan'] ?? '') == 'belum_menikah' ? 'selected' : '' ?>>Belum Menikah</option>
                                <option value="janda" <?= ($pasien['status_perkawinan'] ?? '') == 'janda' ? 'selected' : '' ?>>Janda</option>
                                <option value="duda" <?= ($pasien['status_perkawinan'] ?? '') == 'duda' ? 'selected' : '' ?>>Duda</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Kontak -->
        <div class="section-card">
            <div class="section-header">
                <h6><i class="fas fa-phone mr-2"></i>Informasi Kontak</h6>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_hp">Nomor HP/WA <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" 
                                   value="<?= esc($pasien['nomor_hp']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= esc($pasien['email'] ?? '') ?>"
                                   placeholder="contoh@email.com">
                            <small class="text-muted">Format email harus lengkap (contoh: nama@domain.com)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="section-card">
            <div class="section-header">
                <h6><i class="fas fa-info-circle mr-2"></i>Informasi Tambahan</h6>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="golongan_darah">Golongan Darah</label>
                            <select class="form-control" id="golongan_darah" name="golongan_darah">
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A" <?= ($info_medis['golongan_darah'] ?? '') == 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= ($info_medis['golongan_darah'] ?? '') == 'B' ? 'selected' : '' ?>>B</option>
                                <option value="AB" <?= ($info_medis['golongan_darah'] ?? '') == 'AB' ? 'selected' : '' ?>>AB</option>
                                <option value="O" <?= ($info_medis['golongan_darah'] ?? '') == 'O' ? 'selected' : '' ?>>O</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                            <select class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir">
                                <option value="">Pilih Pendidikan Terakhir</option>
                                <option value="Tidak Sekolah" <?= ($info_tambahan['pendidikan_terakhir'] ?? '') == 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                                <option value="SD" <?= ($info_tambahan['pendidikan_terakhir'] ?? '') == 'SD' ? 'selected' : '' ?>>SD</option>
                                <option value="SMP" <?= ($info_tambahan['pendidikan_terakhir'] ?? '') == 'SMP' ? 'selected' : '' ?>>SMP</option>
                                <option value="SMA" <?= ($info_tambahan['pendidikan_terakhir'] ?? '') == 'SMA' ? 'selected' : '' ?>>SMA</option>
                                <option value="Diploma" <?= ($info_tambahan['pendidikan_terakhir'] ?? '') == 'Diploma' ? 'selected' : '' ?>>Diploma</option>
                                <option value="Sarjana" <?= ($info_tambahan['pendidikan_terakhir'] ?? '') == 'Sarjana' ? 'selected' : '' ?>>Sarjana</option>
                                <option value="Magister" <?= ($info_tambahan['pendidikan_terakhir'] ?? '') == 'Magister' ? 'selected' : '' ?>>Magister</option>
                                <option value="Doktor" <?= ($info_tambahan['pendidikan_terakhir'] ?? '') == 'Doktor' ? 'selected' : '' ?>>Doktor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pekerjaan">Pekerjaan</label>
                            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" 
                                   value="<?= esc($info_tambahan['pekerjaan'] ?? '') ?>" 
                                   placeholder="Contoh: Pegawai Swasta, Wiraswasta, PNS, dll">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kewarganegaraan">Kewarganegaraan</label>
                            <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" 
                                   value="<?= esc($info_tambahan['kewarganegaraan'] ?? 'Indonesia') ?>" 
                                   placeholder="Contoh: Indonesia">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="suku">Suku</label>
                            <input type="text" class="form-control" id="suku" name="suku" 
                                   value="<?= esc($info_tambahan['suku'] ?? '') ?>" 
                                   placeholder="Contoh: Jawa, Batak, Minang, dll">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Alamat -->
        <div class="section-card">
            <div class="section-header">
                <h6><i class="fas fa-map-marker-alt mr-2"></i>Alamat Lengkap</h6>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="alamat_lengkap">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3" required><?= esc($alamat['alamat_lengkap'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_pos">Kode Pos</label>
                            <input type="text" class="form-control" id="kode_pos" name="kode_pos" 
                                   value="<?= esc($alamat['kode_pos'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan/Desa</label>
                            <input type="text" class="form-control" id="kelurahan" name="kelurahan" 
                                   value="<?= esc($alamat['kelurahan'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <input type="text" class="form-control" id="kecamatan" name="kecamatan" 
                                   value="<?= esc($alamat['kecamatan'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kabupaten_kota">Kabupaten/Kota</label>
                            <input type="text" class="form-control" id="kabupaten_kota" name="kabupaten_kota" 
                                   value="<?= esc($alamat['kabupaten_kota'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <input type="text" class="form-control" id="provinsi" name="provinsi" 
                                   value="<?= esc($alamat['provinsi'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak Darurat -->
        <div class="section-card">
            <div class="section-header">
                <h6><i class="fas fa-phone-volume mr-2"></i>Kontak Darurat</h6>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_kontak">Nama Kontak Darurat</label>
                            <input type="text" class="form-control" id="nama_kontak" name="nama_kontak" 
                                   value="<?= esc($kontak_darurat['nama_kontak'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hubungan">Hubungan</label>
                            <input type="text" class="form-control" id="hubungan" name="hubungan" 
                                   value="<?= esc($kontak_darurat['hubungan'] ?? '') ?>" 
                                   placeholder="Contoh: Orang Tua, Suami, Istri, Anak, Saudara">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_hp_darurat">Nomor HP Kontak Darurat</label>
                            <input type="text" class="form-control" id="nomor_hp_darurat" name="nomor_hp_darurat" 
                                   value="<?= esc($kontak_darurat['nomor_hp'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_darurat">Alamat Kontak Darurat</label>
                            <input type="text" class="form-control" id="alamat_darurat" name="alamat_darurat" 
                                   value="<?= esc($kontak_darurat['alamat'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <div class="d-flex justify-content-between">
                <a href="<?= base_url('admisi/datapasien') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    console.log('Form loaded, checking data...');
    console.log('Form action:', $('#editPasienForm').attr('action'));
    console.log('Form method:', $('#editPasienForm').attr('method'));
    
    // Show SweetAlert for session messages
    <?php if (session()->has('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session('success') ?>',
            confirmButtonColor: '#28a745',
            timer: 3000,
            timerProgressBar: true
        });
    <?php endif; ?>
    
    <?php if (session()->has('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan Data!',
            html: '<p><?= addslashes(session('error')) ?></p>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545'
        });
    <?php endif; ?>
    
    <?php if (session()->has('errors')): ?>
        <?php 
        $errorList = '';
        foreach (session('errors') as $error) {
            $errorList .= '<div style="margin-bottom: 10px; padding-left: 10px; text-align: left;">' .
                         '<span style="color: #dc3545; font-weight: bold;">✖</span> ' . 
                         '<span>' . esc($error) . '</span>' .
                         '</div>';
        }
        ?>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: '<div style="text-align: left; margin: 0 auto; display: inline-block;"><?= $errorList ?></div>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545'
        });
    <?php endif; ?>
    
    // Form validation
    $('#editPasienForm').on('submit', function(e) {
        console.log('Form submit triggered');
        let isValid = true;
        let invalidFields = [];
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
                invalidFields.push($(this).attr('name') || $(this).attr('id'));
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Validate email format if filled
        const emailField = $('#email');
        const emailValue = emailField.val().trim();
        if (emailValue !== '') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailValue)) {
                isValid = false;
                emailField.addClass('is-invalid');
                invalidFields.push('Email (format tidak valid - harus: nama@domain.com)');
                console.log('Email validation failed:', emailValue);
                
                // Add error message below email field
                if (!emailField.next('.invalid-feedback').length) {
                    emailField.after('<div class="invalid-feedback d-block">Format email tidak valid. Contoh: nama@email.com</div>');
                }
            } else {
                emailField.removeClass('is-invalid');
                emailField.next('.invalid-feedback').remove();
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            console.log('Form validation failed. Invalid fields:', invalidFields);
            
            // Scroll to first invalid field
            const firstInvalid = $('.is-invalid').first();
            if (firstInvalid.length) {
                $('html, body').animate({
                    scrollTop: firstInvalid.offset().top - 100
                }, 500);
            }
            
            // Show simple SweetAlert
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#667eea'
            });
            return false;
        }
        
        console.log('Form validation passed, submitting...');
        console.log('Form data:', $(this).serialize());
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
        
        // Show loading SweetAlert
        Swal.fire({
            title: 'Menyimpan Data',
            html: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Let form submit naturally
        return true;
    });
    
    // Remove invalid class on input
    $('.form-control').on('input change', function() {
        if ($(this).val() !== '') {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    
    // Real-time email validation
    $('#email').on('blur', function() {
        const emailValue = $(this).val().trim();
        if (emailValue !== '') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailValue)) {
                $(this).addClass('is-invalid');
                if (!$(this).next('.invalid-feedback').length) {
                    $(this).after('<div class="invalid-feedback d-block"><i class="fas fa-exclamation-triangle mr-1"></i>Format email tidak valid. Contoh: nama@email.com</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
});
</script>
<?= $this->endSection() ?>
