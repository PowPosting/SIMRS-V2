<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4" style="max-width:700px;">
    <h2 class="mb-4 text-center" style="font-weight:700; color:#2c3e50; letter-spacing:1px;">Form Pemeriksaan SOAP</h2>
    <form method="post" action="#">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white" style="font-size:18px; font-weight:600;">Subjective</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Keluhan Utama</label>
                    <textarea class="form-control" name="keluhan_utama" rows="2" placeholder="Keluhan utama pasien..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Riwayat Alergi</label>
                    <input type="text" class="form-control" name="riwayat_alergi" placeholder="Alergi obat/makanan...">
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white" style="font-size:18px; font-weight:600;">Objective</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Pemeriksaan Fisik</label>
                    <textarea class="form-control" name="pemeriksaan_fisik" rows="3" placeholder="Hasil pemeriksaan fisik..." required></textarea>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark" style="font-size:18px; font-weight:600;">Assessment</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Diagnosis Sementara</label>
                    <input type="text" class="form-control" name="diagnosis_sementara" placeholder="Diagnosis sementara..." required>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white" style="font-size:18px; font-weight:600;">Planning</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Resep Obat</label>
                    <textarea class="form-control" name="resep_obat" rows="2" placeholder="Tulis resep obat..." required></textarea>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white" style="font-size:18px; font-weight:600;">Implementasi</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Implementasi Tindakan</label>
                    <textarea class="form-control" name="implementasi" rows="2" placeholder="Tindakan yang dilakukan..." required></textarea>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg px-5" style="font-weight:600;">Simpan Pemeriksaan</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
