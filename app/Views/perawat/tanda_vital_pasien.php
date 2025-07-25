<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-heartbeat mr-2"></i>Form Tanda Vital Pasien</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($antrian)): ?>
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong>Nama Pasien:</strong> <?= esc($antrian['nama_pasien'] ?? '-') ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong>No. RM:</strong> <?= esc($antrian['no_rm'] ?? '-') ?>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong>No. Antrian:</strong> <?= esc($antrian['no_antrian'] ?? '-') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong>Poli Tujuan:</strong> <?= esc($antrian['poli_tujuan'] ?? '-') ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Status:</strong> <?= esc($antrian['status'] ?? '-') ?>
                                </div>
                            </div>
                        </div>
                        <form id="formTandaVital" method="post" action="<?= base_url('perawat/simpan-pemeriksaan') ?>">
                            <input type="hidden" name="id_antrian" value="<?= esc($antrian['id']) ?>">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="tekanan_darah">Tekanan Darah (mmHg)</label>
                                    <input type="text" class="form-control" id="tekanan_darah" name="tekanan_darah" placeholder="120/80" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="detak_jantung">Detak Jantung (x/menit)</label>
                                    <input type="text" class="form-control" id="detak_jantung" name="detak_jantung" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="suhu_tubuh">Suhu Tubuh (°C)</label>
                                    <input type="number" step="0.1" min="30" max="45" class="form-control" id="suhu_tubuh" name="suhu_tubuh" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="pernafasan">Pernafasan (x/menit)</label>
                                    <input type="number" min="10" max="60" class="form-control" id="pernafasan" name="pernafasan" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="berat_badan">Berat Badan (kg)</label>
                                    <input type="number" step="0.1" min="1" max="300" class="form-control" id="berat_badan" name="berat_badan" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tinggi_badan">Tinggi Badan (cm)</label>
                                    <input type="number" min="30" max="250" class="form-control" id="tinggi_badan" name="tinggi_badan" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keluhan">Keluhan</label>
                                <textarea class="form-control" id="keluhan" name="keluhan" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-3">
                                <i class="fas fa-save mr-2"></i>Simpan Tanda Vital
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger">Data antrian tidak ditemukan.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

