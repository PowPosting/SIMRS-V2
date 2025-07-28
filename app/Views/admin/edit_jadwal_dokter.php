<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger mb-3"><?= esc($errorMsg) ?></div>
<?php endif; ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4" style="max-width:500px;">
    <h3 class="mb-4">Edit Jadwal Praktik Dokter</h3>
    <form method="post" action="<?= base_url('admin/updatejadwaldokter') ?>">
        <div class="mb-3">
            <label class="form-label">Nama Dokter</label>
            <input type="text" class="form-control" value="<?= esc($namaDokter ?? '') ?>" readonly>
            <input type="hidden" name="dokter_id" value="<?= esc($jadwal['dokter_id'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Hari Praktik</label>
            <input type="text" class="form-control" name="hari" value="<?= esc($jadwal['hari'] ?? '') ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Poliklinik</label>
            <input type="text" class="form-control" value="<?= esc($namaPoli ?? '') ?>" readonly>
            <input type="hidden" name="poli" value="<?= esc($jadwal['poliklinik_id'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time" class="form-control" name="jam_mulai" value="<?= esc($jadwal['jam_mulai'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jam Selesai</label>
            <input type="time" class="form-control" name="jam_selesai" value="<?= esc($jadwal['jam_selesai'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <input type="text" class="form-control" name="keterangan" value="<?= esc($jadwal['keterangan'] ?? '') ?>">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="<?= base_url('admin/datadokter') ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
