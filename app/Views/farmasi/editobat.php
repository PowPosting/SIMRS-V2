<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4" style="max-width:700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Data Obat</h4>
        </div>
        <form action="<?= base_url('farmasi/update-obat/' . ($obat['id_obat'] ?? '')) ?>" method="post" autocomplete="off">
            <input type="hidden" name="id_obat" value="<?= esc($obat['id_obat'] ?? '') ?>">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Obat</label>
                        <input type="text" name="kode_obat" class="form-control" maxlength="20" value="<?= esc($obat['kode_obat'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" name="nama_obat" class="form-control" maxlength="100" required value="<?= esc($obat['nama_obat'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Obat <span class="text-danger">*</span></label>
                        <select name="jenis_obat" class="form-control" required>
                            <option value="">- Pilih Jenis -</option>
                            <?php $jenisList = ['Tablet','Kapsul','Sirup','Injeksi','Salep','Suppositoria','Drops','Lainnya'];
                            foreach($jenisList as $jenis): ?>
                                <option value="<?= $jenis ?>" <?= ($obat['jenis_obat']??'')===$jenis?'selected':'' ?>><?= $jenis ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <select name="satuan" class="form-control" required>
                            <option value="">- Pilih Satuan -</option>
                            <?php $satuanList = ['Tablet','Kapsul','Sirup','Botol','Vial','Ampul','Sachet','Tube','mL','Box'];
                            foreach($satuanList as $sat): ?>
                                <option value="<?= $sat ?>" <?= ($obat['satuan']??'')===$sat?'selected':'' ?>><?= $sat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                        <input type="number" name="harga_beli" class="form-control" min="0" step="0.01" required value="<?= esc($obat['harga_beli'] ?? 0) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" name="harga_jual" class="form-control" min="0" step="0.01" required value="<?= esc($obat['harga_jual'] ?? 0) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" min="0" required value="<?= esc($obat['stok'] ?? 0) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok Minimal <span class="text-danger">*</span></label>
                        <input type="number" name="stok_minimal" class="form-control" min="0" required value="<?= esc($obat['stok_minimal'] ?? 0) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Expired</label>
                        <input type="date" name="tanggal_expired" class="form-control" value="<?= esc($obat['tanggal_expired'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Produsen</label>
                        <input type="text" name="produsen" class="form-control" maxlength="100" value="<?= esc($obat['produsen'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Penyimpanan</label>
                        <input type="text" name="lokasi_penyimpanan" class="form-control" maxlength="50" value="<?= esc($obat['lokasi_penyimpanan'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="Aktif" <?= ($obat['status']??'')==='Aktif'?'selected':'' ?>>Aktif</option>
                            <option value="Tidak Aktif" <?= ($obat['status']??'')==='Tidak Aktif'?'selected':'' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="<?= base_url('farmasi/data-master-obat') ?>" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
<style>
.form-label { font-weight: 600; }
</style>
<?= $this->endSection() ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>



<?= $this->endSection() ?>
