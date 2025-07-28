<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h3>Tambah Jadwal Praktik Dokter</h3>
    <form action="<?= base_url('admin/tambahjadwaldokter/save') ?>" method="post">
        <div class="form-group">
            <label for="dokter_id">Nama Dokter</label>
            <select name="dokter_id" id="dokter_id" class="form-control" required>
                <option value="">- Pilih Dokter -</option>
                <?php if (!empty($dokterList)): ?>
                    <?php foreach ($dokterList as $dokter): ?>
                        <option value="<?= esc($dokter['id']) ?>"><?= esc($dokter['nama_lengkap']) ?> (<?= esc($dokter['spesialisasi']) ?>)</option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="hari">Hari</label>
            <select name="hari" id="hari" class="form-control" required>
                <option value="">- Pilih Hari -</option>
                <?php foreach (["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"] as $hari): ?>
                    <option value="<?= $hari ?>"><?= $hari ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="jam_mulai">Jam Mulai</label>
            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="jam_selesai">Jam Selesai</label>
            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="(Opsional)">
        </div>
        <div class="form-group">
            <label for="poliklinik_id">Poliklinik</label>
            <select name="poliklinik_id" id="poliklinik_id" class="form-control" required>
                <option value="">- Pilih Poliklinik -</option>
                <?php foreach ($poliklinikList as $poli): ?>
                    <option value="<?= esc($poli['id']) ?>"><?= esc($poli['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        <a href="<?= base_url('admin/datadokter') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>
