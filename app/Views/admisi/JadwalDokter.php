<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="mb-2 mb-md-0">
                <h2 class="mb-2">
                    <i class="bi bi-calendar-week me-2"></i>Jadwal Praktik Dokter
                </h2>
            </div>
            <div class="date-badge">
                <i class="bi bi-calendar3 me-2"></i><?= date('d M Y') ?>
            </div>
        </div>
    </div>

    <!-- Jadwal Table -->
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #52c41a 0%, #95de64 100%);">
            <h5 class="mb-0">
                <i class="bi bi-calendar-week me-2"></i>Jadwal Praktik Dokter
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered text-center mb-0" id="jadwalTable" style="font-size:14px;">
                    <thead style="background:linear-gradient(90deg,#4f8fc3 0%,#b2e0f7 100%); color:#2c3e50;">
                        <tr>
                            <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none;">Senin</th>
                            <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none;">Selasa</th>
                            <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none;">Rabu</th>
                            <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none;">Kamis</th>
                            <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none;">Jumat</th>
                            <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none;">Sabtu</th>
                            <th style="padding:16px 0; font-size:18px; font-weight:700; letter-spacing:1.5px; border:none;">Minggu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']; ?>
                        <tr>
                        <?php foreach ($hariList as $hari): ?>
                            <td class="jadwal-cell" style="vertical-align:top; min-width:110px; max-width:160px; word-break:break-word; padding:8px 4px; background:#f7fbfd; border:1px solid #e3e3e3;" data-hari="<?= $hari ?>">
                                <?php
                                $jadwalHari = [];
                                if (!empty($dokterList)) {
                                    foreach ($dokterList as $dokter) {
                                        if (!empty($jadwalList[$dokter['id']])) {
                                            foreach ($jadwalList[$dokter['id']] as $jadwal) {
                                                if (strtolower($jadwal['hari']) == strtolower($hari)) {
                                                    $jadwalHari[] = [
                                                        'dokter' => $dokter['nama_lengkap'],
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
                                    <?php foreach ($jadwalHari as $item): ?>
                                        <div class="mb-2 shadow-sm rounded jadwal-card" style="background:#fafdff; border:1px solid #c7d8e2; text-align:left; padding:8px 10px;" data-poli="<?= $item['poliklinik_id'] ?>" data-dokter="<?= strtolower($item['dokter']) ?>" data-spesialisasi="<?= strtolower($item['spesialisasi']) ?>">
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
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 15px;
    padding: 25px 30px;
    color: white;
    box-shadow: 0 8px 25px rgba(0,123,255,0.15);
}

.page-header h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.date-badge {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 25px;
    padding: 12px 20px;
    font-weight: 600;
}

.jadwal-card:hover {
    background: #f0f8ff !important;
    transform: translateY(-1px);
    transition: all 0.2s ease;
    box-shadow: 0 4px 8px rgba(0,123,255,0.1);
}

.avatar-sm {
    font-weight: bold;
}

.card {
    border: none;
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    padding: 15px 20px;
}

.table {
    font-size: 14px;
}

.table thead th {
    font-weight: 600;
    padding: 12px 8px;
}

.table tbody td {
    padding: 10px 8px;
}

@media (max-width: 768px) {
    .table {
        font-size: 12px;
    }
    
    .avatar-sm {
        width: 30px !important;
        height: 30px !important;
        font-size: 12px !important;
    }
}
</style>

<?= $this->endSection() ?>