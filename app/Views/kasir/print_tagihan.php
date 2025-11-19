<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - <?= $pasien['no_rm'] ?? '-' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            background: #fff;
        }
        
        .struk-container {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 15px;
            border: 1px solid #000;
        }
        
        .struk-header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .struk-header h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .struk-header .subtitle {
            font-size: 10px;
            color: #333;
        }
        
        .struk-header .klinik-info {
            margin-top: 8px;
            font-size: 11px;
        }
        
        .info-section {
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .info-row {
            margin-bottom: 3px;
            line-height: 1.6;
        }
        
        .info-label {
            display: inline-block;
            width: 120px;
        }
        
        .info-value {
            display: inline;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin: 15px 0 8px 0;
            padding: 5px 0;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
        }
        
        .items-table {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .items-table .item-row {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #ccc;
        }
        
        .item-name {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 2px;
        }
        
        .item-details {
            font-size: 10px;
            color: #333;
            margin-top: 2px;
            padding-left: 10px;
        }
        
        .item-qty {
            display: inline-block;
            width: 60%;
        }
        
        .item-price {
            display: inline-block;
            width: 38%;
            text-align: right;
        }
        
        .biaya-row {
            padding: 5px 0;
            font-size: 11px;
            line-height: 1.6;
        }
        
        .biaya-label {
            display: inline-block;
            width: 60%;
        }
        
        .biaya-value {
            display: inline-block;
            width: 38%;
            text-align: right;
        }
        
        .biaya-row.total {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            margin-top: 8px;
            padding: 8px 0;
            font-weight: bold;
            font-size: 13px;
        }
        
        .biaya-row.bayar {
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .biaya-row.kembalian {
            font-weight: bold;
            font-size: 12px;
            border-top: 1px dashed #000;
            padding-top: 8px;
            margin-top: 5px;
        }
        
        .struk-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 10px;
        }
        
        .struk-footer .terima-kasih {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .struk-footer .info {
            margin-top: 8px;
            font-style: italic;
            color: #666;
        }
        
        .timestamp {
            text-align: center;
            font-size: 10px;
            margin: 10px 0;
            color: #666;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .struk-container {
                border: none;
                padding: 10px;
                max-width: 100%;
            }
            
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
        
        @media screen {
            .struk-container {
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="struk-container">
        <!-- Header -->
        <div class="struk-header">
            <h2>STRUK PEMBAYARAN</h2>
            <div class="subtitle">SIMRS - Sistem Informasi Manajemen Rumah Sakit</div>
            <div class="klinik-info">
                <div>RS Hamori</div>
                <div>Jl.Pagaden Ds.Jabong, Kota Subang</div>
                <div>Telp: 0260-4250 888</div>
            </div>
        </div>
        
        <!-- Timestamp -->
        <div class="timestamp">
            <?= date('d/m/Y H:i:s') ?>
        </div>
        
        <!-- Informasi Pasien -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">No. RM</span>
                <span class="info-value">: <?= esc($pasien['no_rm'] ?? '-') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Pasien</span>
                <span class="info-value">: <?= esc($pasien['nama_pasien'] ?? '-') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Umur</span>
                <span class="info-value">: <?= esc($pasien['umur'] ?? '-') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Kelamin</span>
                <span class="info-value">: <?= ($pasien['jenis_kelamin'] ?? '-') === 'L' ? 'Laki-laki' : (($pasien['jenis_kelamin'] ?? '-') === 'P' ? 'Perempuan' : '-') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Dokter</span>
                <span class="info-value">: <?= esc($pasien['nama_dokter'] ?? 'Dr. -') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">: <?= date('d/m/Y', strtotime($pasien['tanggal_resep'] ?? date('Y-m-d'))) ?></span>
            </div>
        </div>
        
        <!-- Rincian Obat -->
        <?php if (!empty($resep_list)): ?>
        <div class="section-title">Obat & Farmasi</div>
        <div class="items-table">
            <?php foreach ($resep_list as $resep): ?>
                <?php 
                $subtotal = ($resep['harga_jual'] ?? 0) * ($resep['jumlah'] ?? 0);
                ?>
                <div class="item-row">
                    <div class="item-name"><?= esc($resep['nama_obat']) ?></div>
                    <div class="item-details">
                        <span class="item-qty"><?= esc($resep['jumlah'] ?? 0) ?> <?= esc($resep['satuan'] ?? 'pcs') ?> x Rp <?= number_format($resep['harga_jual'] ?? 0, 0, ',', '.') ?></span>
                        <span class="item-price">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- Biaya Layanan -->
        <div class="section-title">Biaya Layanan</div>
        <div class="biaya-row">
            <span class="biaya-label">Administrasi</span>
            <span class="biaya-value">Rp <?= number_format($biaya_layanan['administrasi'] ?? 35000, 0, ',', '.') ?></span>
        </div>
        <div class="biaya-row">
            <span class="biaya-label">Konsultasi Dokter</span>
            <span class="biaya-value">Rp <?= number_format($biaya_layanan['dokter'] ?? 100000, 0, ',', '.') ?></span>
        </div>
        <?php if (!empty($resep_list)): ?>
        <div class="biaya-row">
            <span class="biaya-label">Obat & Farmasi</span>
            <span class="biaya-value">Rp <?= number_format($total_obat ?? 0, 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>
        
        <!-- Total -->
        <div class="biaya-row total">
            <span class="biaya-label">TOTAL TAGIHAN</span>
            <span class="biaya-value">Rp <?= number_format($total_tagihan ?? 0, 0, ',', '.') ?></span>
        </div>
        
        <!-- Pembayaran -->
        <?php if (isset($jumlah_bayar) && $jumlah_bayar > 0): ?>
        <div class="biaya-row bayar">
            <span class="biaya-label">Jumlah Bayar</span>
            <span class="biaya-value">Rp <?= number_format($jumlah_bayar, 0, ',', '.') ?></span>
        </div>
        <div class="biaya-row kembalian">
            <span class="biaya-label">Kembalian</span>
            <span class="biaya-value">Rp <?= number_format($kembalian ?? 0, 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>
        
        <!-- Metode Pembayaran -->
        <?php if (isset($metode_pembayaran) && !empty($metode_pembayaran)): ?>
        <div style="text-align: center; margin-top: 10px; font-size: 11px;">
            <strong>Metode Pembayaran: <?= strtoupper(esc($metode_pembayaran)) ?></strong>
        </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="struk-footer">
            <div class="terima-kasih">TERIMA KASIH</div>
            <div>Semoga Lekas Sembuh</div>
            <div class="info">
                * Simpan struk ini sebagai bukti pembayaran *<br>
                * Hubungi kami jika ada pertanyaan *
            </div>
        </div>
    </div>
    
    <script>
        // Auto close after print
        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>
</html>
