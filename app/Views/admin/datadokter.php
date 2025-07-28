<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Daftar Dokter & Jadwal Praktik</h2>
    <div class="mb-3">
        <a href="<?= base_url('admin/tambahjadwaldokter') ?>" class="btn btn-primary btn-sm">
            Tambah Jadwal Dokter
        </a>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered text-center" style="font-size:14px; width:100%; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <thead style="background:linear-gradient(90deg,#4f8fc3 0%,#b2e0f7 100%); color:#2c3e50;">
            <tr>
                <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none; box-shadow:0 2px 8px rgba(0,0,0,0.04); text-shadow:0 1px 4px rgba(0,0,0,0.04); border-radius:6px 6px 0 0;">Senin</th>
                <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none; box-shadow:0 2px 8px rgba(0,0,0,0.04); text-shadow:0 1px 4px rgba(0,0,0,0.04); border-radius:6px 6px 0 0;">Selasa</th>
                <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none; box-shadow:0 2px 8px rgba(0,0,0,0.04); text-shadow:0 1px 4px rgba(0,0,0,0.04); border-radius:6px 6px 0 0;">Rabu</th>
                <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none; box-shadow:0 2px 8px rgba(0,0,0,0.04); text-shadow:0 1px 4px rgba(0,0,0,0.04); border-radius:6px 6px 0 0;">Kamis</th>
                <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none; box-shadow:0 2px 8px rgba(0,0,0,0.04); text-shadow:0 1px 4px rgba(0,0,0,0.04); border-radius:6px 6px 0 0;">Jumat</th>
                <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none; box-shadow:0 2px 8px rgba(0,0,0,0.04); text-shadow:0 1px 4px rgba(0,0,0,0.04); border-radius:6px 6px 0 0;">Sabtu</th>
                <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none; box-shadow:0 2px 8px rgba(0,0,0,0.04); text-shadow:0 1px 4px rgba(0,0,0,0.04); border-radius:6px 6px 0 0;">Minggu</th>
            </tr>
        </thead>
        <tbody>
            <?php $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']; ?>
            <tr>
            <?php foreach ($hariList as $hari): ?>
                <td style="vertical-align:top; min-width:110px; max-width:160px; word-break:break-word; padding:8px 4px; background:#f7fbfd; border:1px solid #e3e3e3; transition:background 0.2s;">
                    <?php
                    $jadwalHari = [];
                    if (!empty($dokterList)) {
                        foreach ($dokterList as $dokter) {
                            if (!empty($jadwalList[$dokter['id']])) {
                                foreach ($jadwalList[$dokter['id']] as $jadwal) {
                                    if (strtolower($jadwal['hari']) == strtolower($hari)) {
                                        $jadwalHari[] = [
                                            'id' => $jadwal['id'],
                                            'dokter' => $dokter['nama_lengkap'],
                                            'dokter_id' => $dokter['id'],
                                            'spesialisasi' => $dokter['spesialisasi'],
                                            'nama_poli' => $jadwal['nama_poli'] ?? '-',
                                            'poliklinik_id' => $jadwal['poliklinik_id'],
                                            'jam_mulai' => $jadwal['jam_mulai'],
                                            'jam_selesai' => $jadwal['jam_selesai'],
                                            'keterangan' => $jadwal['keterangan'] ?? ''
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    ?>
                    <?php if (!empty($jadwalHari)): ?>
                        <?php foreach ($jadwalHari as $idx => $item): ?>
                            <div class="mb-2 shadow-sm rounded jadwal-card" style="background:#fafdff; border:1px solid #c7d8e2; text-align:left; padding:8px 10px; position:relative;">
                                <div style="font-weight:600; color:#3a6ea5; font-size:13px; margin-bottom:2px;">
                                    <?= esc($item['dokter']) ?> <span style="color:#6c757d; font-weight:400;">(<?= esc($item['spesialisasi']) ?>)</span>
                                </div>
                                <div style="font-size:12px; color:#444; margin-bottom:2px;">
                                    <span style="font-weight:bold; color:#2196f3; background:#e3f7fa; border-radius:4px; padding:1px 6px; margin-right:4px;">
                                        <?= esc($item['nama_poli']) ?>
                                    </span>
                                    <?= esc($item['jam_mulai']) ?> - <?= esc($item['jam_selesai']) ?>
                                </div>
                                <?php if (!empty($item['keterangan'])): ?>
                                    <div style="font-size:11px; color:#888; margin-top:2px;">(<?= esc($item['keterangan']) ?>)</div>
                                <?php endif; ?>
                                <div class="d-flex mt-2" style="gap:6px;">
                                    <a href="<?= base_url('admin/editjadwaldokter') ?>?dokter_id=<?= $item['dokter_id'] ?>&hari=<?= urlencode($hari) ?>&poli=<?= $item['poliklinik_id'] ?>" class="btn btn-warning btn-sm" style="font-size:12px; min-width:48px; padding:2px 8px;">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('admin/hapusjadwaldokter') ?>?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm" style="font-size:12px; min-width:48px; padding:2px 8px;" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted" style="font-size:13px;">Tidak ada jadwal</span>
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
    </div>
</div>

<?= $this->endSection() ?>