<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4" style="max-width:700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Obat Baru</h4>
        </div>
        <form action="<?= base_url('farmasi/simpan-obat') ?>" method="post" autocomplete="off">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Obat</label>
                        <input type="text" name="kode_obat" class="form-control" maxlength="20">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" name="nama_obat" class="form-control" maxlength="100" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Obat <span class="text-danger">*</span></label>
                        <select name="jenis_obat" class="form-control" required>
                            <option value="">- Pilih Jenis -</option>
                            <option>Tablet</option>
                            <option>Kapsul</option>
                            <option>Sirup</option>
                            <option>Injeksi</option>
                            <option>Salep</option>
                            <option>Suppositoria</option>
                            <option>Drops</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <select name="satuan" class="form-control" required>
                            <option value="">- Pilih Satuan -</option>
                            <?php $satuanList = ['Tablet','Kapsul','Sirup','Botol','Vial','Ampul','Sachet','Tube','mL','Box'];
                            foreach($satuanList as $sat): ?>
                                <option value="<?= $sat ?>"><?= $sat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                        <input type="number" name="harga_beli" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" name="harga_jual" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok Minimal <span class="text-danger">*</span></label>
                        <input type="number" name="stok_minimal" class="form-control" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Expired</label>
                        <input type="date" name="tanggal_expired" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Produsen</label>
                        <input type="text" name="produsen" class="form-control" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Penyimpanan</label>
                        <input type="text" name="lokasi_penyimpanan" class="form-control" maxlength="50">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="<?= base_url('farmasi/data-master-obat') ?>" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
<style>
.form-label { font-weight: 600; }
</style>
<?= $this->endSection() ?>
