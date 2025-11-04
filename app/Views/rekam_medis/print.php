<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis - <?= $pasien['no_rekam_medis'] ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background: white;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #000;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin: 2px 0;
        }

        .rm-number {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            padding: 10px;
            background: #f5f5f5;
            border: 2px dashed #333;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #000;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 6px 10px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 30%;
            font-weight: bold;
        }

        .info-table td:nth-child(2) {
            width: 5%;
        }

        .kunjungan-item {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .kunjungan-header {
            background: #f8f9fa;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kunjungan-header-left {
            flex: 1;
        }

        .kunjungan-header-left h4 {
            margin: 0;
            font-size: 14px;
            color: #000;
        }

        .kunjungan-header-left p {
            margin: 2px 0 0 0;
            font-size: 11px;
            color: #666;
        }

        .kunjungan-header-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: #007bff;
            color: white;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .kunjungan-body {
            padding: 10px;
        }

        .kunjungan-body table {
            width: 100%;
            border-collapse: collapse;
        }

        .kunjungan-body table td {
            padding: 5px;
            vertical-align: top;
        }

        .vital-signs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
        }

        .vital-item {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border-left: 3px solid #007bff;
            flex: 0 0 calc(33.333% - 10px);
        }

        .vital-label {
            font-size: 10px;
            color: #666;
            display: block;
        }

        .vital-value {
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }

        .soap-section {
            margin-top: 10px;
        }

        .soap-item {
            margin-bottom: 10px;
        }

        .soap-label {
            font-weight: bold;
            color: #000;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .soap-content {
            padding: 8px;
            background: #f8f9fa;
            border-left: 3px solid #28a745;
            font-size: 11px;
            white-space: pre-line;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }

        @media print {
            body {
                padding: 0;
            }
            
            .kunjungan-item {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 500);">
    <div class="header">
        <h1>REKAM MEDIS PASIEN</h1>
        <p>SIMRS HAMORI</p>
        <p>Jl. Contoh No. 123, Telp: (0123) 456789</p>
    </div>

    <div class="rm-number">
        NO. REKAM MEDIS: <?= $pasien['no_rekam_medis'] ?>
    </div>

    <!-- Data Pasien -->
    <div class="section">
        <div class="section-title">DATA PASIEN</div>
        <table class="info-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong><?= $pasien['title'] ?> <?= $pasien['nama_lengkap'] ?></strong></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?= $pasien['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td><?= $pasien['tempat_lahir'] ?>, <?= date('d/m/Y', strtotime($pasien['tanggal_lahir'])) ?> (<?= date_diff(date_create($pasien['tanggal_lahir']), date_create('today'))->y ?> tahun)</td>
            </tr>
            <tr>
                <td>Status Perkawinan</td>
                <td>:</td>
                <td><?= $pasien['status_perkawinan'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Golongan Darah</td>
                <td>:</td>
                <td><?= $infoMedis['golongan_darah'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td><?= $infoTambahan['agama'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= $alamat['alamat_lengkap'] ?? '-' ?>, Kel. <?= $alamat['kelurahan'] ?? '-' ?>, Kec. <?= $alamat['kecamatan'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>:</td>
                <td><?= $pasien['nomor_hp'] ?? '-' ?></td>
            </tr>
        </table>
    </div>

    <!-- Riwayat Kunjungan -->
    <div class="section">
        <div class="section-title">RIWAYAT KUNJUNGAN (<?= count($riwayatKunjungan) ?> Kunjungan Terakhir)</div>
        
        <?php if (empty($riwayatKunjungan)): ?>
            <div class="no-data">Belum ada riwayat kunjungan</div>
        <?php else: ?>
            <?php foreach ($riwayatKunjungan as $index => $kunjungan): ?>
                <div class="kunjungan-item">
                    <div class="kunjungan-header">
                        <div class="kunjungan-header-left">
                            <h4><?= $kunjungan['nama_poli'] ?></h4>
                            <p><?= date('d F Y, H:i', strtotime($kunjungan['tanggal_kunjungan'])) ?> WIB</p>
                        </div>
                        <div class="kunjungan-header-right">
                            <span class="badge"><?= $kunjungan['no_antrian'] ?></span>
                        </div>
                    </div>
                    
                    <div class="kunjungan-body">
                        <!-- Keluhan -->
                        <?php if ($kunjungan['keluhan']): ?>
                            <div class="soap-item">
                                <div class="soap-label">Keluhan Pasien:</div>
                                <div class="soap-content"><?= esc($kunjungan['keluhan']) ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Tanda Vital -->
                        <?php if ($kunjungan['tekanan_darah']): ?>
                            <div style="margin: 15px 0;">
                                <strong>Tanda Vital:</strong>
                                <div class="vital-signs">
                                    <div class="vital-item">
                                        <span class="vital-label">Tekanan Darah</span>
                                        <span class="vital-value"><?= $kunjungan['tekanan_darah'] ?> mmHg</span>
                                    </div>
                                    <?php if ($kunjungan['detak_jantung']): ?>
                                        <div class="vital-item">
                                            <span class="vital-label">Nadi</span>
                                            <span class="vital-value"><?= $kunjungan['detak_jantung'] ?> x/mnt</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($kunjungan['suhu_tubuh']): ?>
                                        <div class="vital-item">
                                            <span class="vital-label">Suhu</span>
                                            <span class="vital-value"><?= $kunjungan['suhu_tubuh'] ?>°C</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($kunjungan['pernafasan']): ?>
                                        <div class="vital-item">
                                            <span class="vital-label">Respirasi</span>
                                            <span class="vital-value"><?= $kunjungan['pernafasan'] ?> x/mnt</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($kunjungan['berat_badan']): ?>
                                        <div class="vital-item">
                                            <span class="vital-label">Berat Badan</span>
                                            <span class="vital-value"><?= $kunjungan['berat_badan'] ?> kg</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($kunjungan['tinggi_badan']): ?>
                                        <div class="vital-item">
                                            <span class="vital-label">Tinggi Badan</span>
                                            <span class="vital-value"><?= $kunjungan['tinggi_badan'] ?> cm</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- SOAP Notes -->
                        <?php if ($kunjungan['assessment']): ?>
                            <div class="soap-section">
                                <?php if ($kunjungan['subjective']): ?>
                                    <div class="soap-item">
                                        <div class="soap-label">Subjective (Keluhan Utama):</div>
                                        <div class="soap-content"><?= nl2br(esc($kunjungan['subjective'])) ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($kunjungan['riwayat_penyakit']): ?>
                                    <div class="soap-item">
                                        <div class="soap-label">Riwayat Penyakit:</div>
                                        <div class="soap-content"><?= nl2br(esc($kunjungan['riwayat_penyakit'])) ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($kunjungan['riwayat_alergi']): ?>
                                    <div class="soap-item">
                                        <div class="soap-label">Riwayat Alergi:</div>
                                        <div class="soap-content"><?= nl2br(esc($kunjungan['riwayat_alergi'])) ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($kunjungan['objective']): ?>
                                    <div class="soap-item">
                                        <div class="soap-label">Objective (Pemeriksaan Fisik):</div>
                                        <div class="soap-content"><?= nl2br(esc($kunjungan['objective'])) ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="soap-item">
                                    <div class="soap-label">Assessment (Diagnosis):</div>
                                    <div class="soap-content"><?= nl2br(esc($kunjungan['assessment'])) ?></div>
                                </div>
                                
                                <?php if ($kunjungan['prognosis']): ?>
                                    <div class="soap-item">
                                        <div class="soap-label">Prognosis:</div>
                                        <div class="soap-content"><?= nl2br(esc($kunjungan['prognosis'])) ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($kunjungan['plan']): ?>
                                    <div class="soap-item">
                                        <div class="soap-label">Plan (Terapi/Edukasi):</div>
                                        <div class="soap-content"><?= nl2br(esc($kunjungan['plan'])) ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($kunjungan['nama_dokter']): ?>
                                    <p style="margin-top: 10px; font-size: 11px; color: #666;">
                                        <strong>Dokter:</strong> <?= $kunjungan['nama_dokter'] ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Info Tambahan -->
                        <div style="margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ddd; font-size: 10px; color: #666;">
                            <?php if ($kunjungan['jumlah_resep'] > 0): ?>
                                <span style="margin-right: 15px;">📋 <?= $kunjungan['jumlah_resep'] ?> Resep Obat</span>
                            <?php endif; ?>
                            <?php if ($kunjungan['total_biaya']): ?>
                                <span> Total Biaya: Rp <?= number_format($kunjungan['total_biaya'], 0, ',', '.') ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak pada <?= date('d F Y, H:i:s') ?> WIB</p>
        <p>© <?= date('Y') ?> SIMRS HAMORI - Sistem Informasi Manajemen Rumah Sakit</p>
    </div>
</body>
</html>
