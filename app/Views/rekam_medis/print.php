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
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            flex: 1;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 3px;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            font-size: 10px;
            color: #333;
            margin: 1px 0;
            line-height: 1.4;
        }

        .header-right {
            text-align: right;
            border-left: 2px solid #000;
            padding-left: 15px;
        }

        .rm-number {
            font-size: 14px;
            font-weight: bold;
            color: #000;
        }

        .rm-label {
            font-size: 10px;
            color: #666;
            display: block;
            margin-bottom: 3px;
        }

        .doc-info {
            margin-top: 5px;
            font-size: 9px;
            color: #666;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #e8e8e8;
            color: #000;
            padding: 6px 10px;
            font-weight: bold;
            margin-bottom: 8px;
            margin-top: 15px;
            font-size: 12px;
            border-left: 4px solid #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-table td:first-child {
            width: 28%;
            font-weight: normal;
            color: #666;
            font-size: 11px;
        }

        .info-table td:nth-child(2) {
            width: 2%;
            color: #666;
        }

        .info-table td:nth-child(3) {
            font-weight: 500;
            color: #000;
        }

        .kunjungan-item {
            border: 1px solid #ccc;
            margin-bottom: 12px;
            page-break-inside: avoid;
            background: #fff;
        }

        .kunjungan-header {
            background: #f5f5f5;
            padding: 8px 10px;
            border-bottom: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kunjungan-header-left {
            flex: 1;
        }

        .kunjungan-header-left h4 {
            margin: 0;
            font-size: 12px;
            color: #000;
            font-weight: bold;
        }

        .kunjungan-header-left p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: #555;
        }

        .kunjungan-header-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #333;
            color: white;
            border-radius: 2px;
            font-size: 10px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
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
            background: #fafafa;
            padding: 6px 10px;
            border-radius: 0;
            border: 1px solid #ddd;
            border-left: 3px solid #333;
            flex: 0 0 calc(33.333% - 10px);
        }

        .vital-label {
            font-size: 9px;
            color: #666;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .vital-value {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-top: 2px;
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
            font-size: 11px;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .soap-content {
            padding: 8px 10px;
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-left: 3px solid #333;
            font-size: 11px;
            white-space: pre-line;
            line-height: 1.6;
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
<body onload="window.print();">
    <div class="header">
        <div class="header-left">
             <div>RS Hamori</div>
                <div>Jl.Pagaden Ds.Jabong, Kota Subang</div>
                <div>Telp: 0260-4250 888</div>
        </div>
        <div class="header-right">
            <span class="rm-label">No. Rekam Medis</span>
            <div class="rm-number"><?= $pasien['no_rekam_medis'] ?></div>
            <div class="doc-info">Dicetak: <?= date('d/m/Y H:i') ?></div>
        </div>
    </div>

    <!-- Data Pasien -->
    <div class="section">
        <div class="section-title">I. Data Identitas Pasien</div>
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
        <div class="section-title">II. Riwayat Kunjungan & Pemeriksaan (<?= count($riwayatKunjungan) ?> Kunjungan)</div>
        
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
        <p style="margin-bottom: 5px; font-weight: bold; color: #333;">Dokumen Rahasia - Rekam Medis Pasien</p>
        <p style="font-size: 9px;">Dokumen ini dicetak pada <?= date('d F Y, H:i:s') ?> WIB dari SIMRS Hamori</p>
        <p style="font-size: 9px; margin-top: 3px;">Dokumen ini adalah milik Rumah Sakit Hamori dan bersifat rahasia. Dilarang memperbanyak tanpa izin.</p>
    </div>
</body>
</html>
