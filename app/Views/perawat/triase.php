
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow border-0">
                <div class="card-header text-white text-center rounded-top" style="background: linear-gradient(135deg, #2b3280 0%, #2d8ba6 100%); box-shadow: 0 2px 8px rgba(43,50,128,0.08);">
                    <h4 class="mb-0 font-weight-bold" style="letter-spacing:0.5px;"><i class="fas fa-briefcase-medical mr-2"></i>Pemeriksaan Triase</h4>
                </div>
                <div class="card-body px-4 py-4">
                    <form id="formTriase" autocomplete="off">
                        <input type="hidden" name="id_antrian" value="<?= esc($antrian['id'] ?? '') ?>">
                        <div class="row mb-3">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="font-weight-bold small mb-1">No. Rekam Medis</label>
                                <input type="text" class="form-control-plaintext font-weight-bold text-primary" value="<?= esc($antrian['no_rm'] ?? '') ?>" readonly>
                            </div>
                            <div class="col-md-5 mb-2 mb-md-0">
                                <label class="font-weight-bold small mb-1">Nama Pasien</label>
                                <input type="text" class="form-control-plaintext font-weight-bold text-dark" value="<?= esc($antrian['nama_pasien'] ?? '') ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold small mb-1">Poli Tujuan</label>
                                <input type="text" class="form-control-plaintext font-weight-bold text-info" value="<?= esc($antrian['poli_tujuan'] ?? '') ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Tekanan Darah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="tekanan_darah" placeholder="... / ... mmHg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Suhu Tubuh <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="suhu_tubuh" placeholder="... °C" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Berat Badan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="berat_badan" placeholder="... kg" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Tinggi Badan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="tinggi_badan" placeholder="... cm" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Keluhan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="keluhan" rows="3" placeholder="Keluhan utama pasien ..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="submit" class="btn btn-success px-4 font-weight-bold">
                                <i class="fas fa-save mr-2"></i>Simpan Pemeriksaan
                            </button>
                            <a href="<?= base_url('perawat/antrian-pendaftaran') ?>" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
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
$(function() {
    $('#formTriase').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2"></span>Memproses...');
        $.ajax({
            url: '<?= base_url('perawat/simpanTriase') ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Pemeriksaan triase berhasil disimpan!',
                        timer: 1800,
                        showConfirmButton: false
                    });
                    setTimeout(function(){
                        window.location.href = '<?= base_url('perawat/antrian-pendaftaran') ?>';
                    }, 1800);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Gagal menyimpan data.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan server.'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Simpan Pemeriksaan');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
