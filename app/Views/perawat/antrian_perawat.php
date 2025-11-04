<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Dokter - <?= $no_antrian ?? '-' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .struk {
            width: 300px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .hospital-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .hospital-info {
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 15px 0 8px 0;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        
        .info-row {
            display: flex;
            font-size: 11px;
            margin-bottom: 5px;
            line-height: 1.5;
        }
        
        .info-label {
            width: 100px;
            flex-shrink: 0;
        }
        
        .info-value {
            flex: 1;
            font-weight: bold;
        }
        
        .antrian-box {
            text-align: center;
            border: 3px solid #000;
            padding: 15px;
            margin: 20px 0;
            background: #f9f9f9;
        }
        
        .antrian-label {
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .antrian-number {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 3px;
        }
        
        .footer {
            border-top: 2px dashed #000;
            padding-top: 15px;
            margin-top: 15px;
            text-align: center;
            font-size: 11px;
            line-height: 1.6;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #000;
            padding: 8px;
            margin: 10px 0;
            font-size: 10px;
            text-align: center;
        }
        
        /* Print styles */
        @media print {
            body {
                background: white;
                padding: 0;
                display: block;
            }
            
            .struk {
                box-shadow: none;
                margin: 0 auto;
            }
            
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="struk">
        <!-- Header -->
        <div class="header">
            <div class="hospital-name">RS HAMORI</div>
            <div class="hospital-info">
                Jl. Raya Pagaden-Subang, Ds.Jabong<br>
                Telp: 0260-4250888<br>
                Email: customerservice@rshamori.co.id
            </div>
        </div>

        <!-- Nomor Antrian -->
        <div class="antrian-box">
            <div class="antrian-label">NO ANTRIAN DOKTER</div>
            <div class="antrian-number"><?= $no_antrian ?? '-' ?></div>
        </div>

        <!-- Info Pasien -->
        <div class="section-title">Data Pasien</div>
        <?php
        // Debug: Log data yang diterima
        log_message('info', '[View antrian_perawat] Data antrian: ' . json_encode($antrian));
        ?>
        <div class="info-row">
            <div class="info-label">No. RM</div>
            <div class="info-value">: <?= $antrian['no_rm'] ?? '-' ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Nama</div>
            <div class="info-value">: <?= strtoupper($antrian['nama_lengkap'] ?? '-') ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Tgl Lahir</div>
            <div class="info-value">: <?= isset($antrian['tanggal_lahir']) ? date('d-m-Y', strtotime($antrian['tanggal_lahir'])) : '-' ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Jenis Kelamin</div>
            <div class="info-value">: <?= ($antrian['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
        </div>

        <!-- Info Kunjungan -->
        <div class="section-title">Kunjungan</div>
        <?php
        // Set timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('d-m-Y');
        $jam = date('H:i');
        ?>
        <div class="info-row">
            <div class="info-label">Tanggal</div>
            <div class="info-value">: <?= $tanggal ?> <?= $jam ?> WIB</div>
        </div>
        <div class="info-row">
            <div class="info-label">Poli</div>
            <div class="info-value">: <?= strtoupper($antrian['nama_poli'] ?? '-') ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Status</div>
            <div class="info-value">: Menuju Pemeriksaan Dokter</div>
        </div>

        <!-- Warning -->
        <div class="warning">
            <strong>PERHATIAN!</strong><br>
            Harap menunggu panggilan dokter.<br>
            Simpan struk ini sampai selesai pemeriksaan.
        </div>

        <!-- Footer -->
        <div class="footer">
            Terima kasih atas kunjungan Anda<br>
            Semoga lekas sembuh<br>
            <br>
            <?php
            // Set timezone dan format footer
            date_default_timezone_set('Asia/Jakarta');
            echo date('d/m/Y H:i:s');
            ?>
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
