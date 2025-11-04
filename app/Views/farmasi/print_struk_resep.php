<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Struk Resep Obat' ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 14px; margin: 0; padding: 0; }
        .struk-container { max-width: 400px; margin: 0 auto; background: #fff; border: 1px solid #ccc; padding: 20px; }
        .struk-header { text-align: center; margin-bottom: 15px; }
        .struk-header h2 { margin: 0 0 5px 0; font-size: 20px; }
        .struk-header small { color: #888; }
        .struk-info { margin-bottom: 15px; }
        .struk-info table { width: 100%; }
        .struk-info td { padding: 3px 0; }
        .struk-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .struk-table th, .struk-table td { border-bottom: 1px dashed #bbb; padding: 6px 0; text-align: left; }
        .struk-table th { font-size: 13px; font-weight: bold; }
        .struk-table td { font-size: 13px; }
        .struk-total { text-align: right; font-weight: bold; font-size: 15px; margin-top: 10px; border-top: 2px solid #333; padding-top: 8px; }
        .struk-footer { text-align: center; margin-top: 20px; font-size: 12px; color: #888; }
        .obat-instruksi { font-size: 11px; color: #666; font-style: italic; }
        @media print {
            body { background: #fff; }
            .struk-container { box-shadow: none; border: none; }
            .struk-footer { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
<div class="struk-container">
    <div class="struk-header">
        <h2>STRUK RESEP OBAT</h2>
        <small>Farmasi - <?= date('d/m/Y H:i') ?></small>
    </div>
    
    <div class="struk-info">
        <table>
            <tr>
                <td width="30%">Nama Pasien</td>
                <td>: <b><?= $resep['nama_pasien'] ?? '-' ?></b></td>
            </tr>
            <tr>
                <td>No. RM</td>
                <td>: <?= $resep['no_rm'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Umur</td>
                <td>: <?= $umur ?></td>
            </tr>
            <tr>
                <td>Dokter</td>
                <td>: <?= $resep['nama_dokter'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: <?= date('d/m/Y H:i', strtotime($resep['tanggal_resep'])) ?></td>
            </tr>
        </table>
    </div>
    
    <table class="struk-table">
        <thead>
            <tr>
                <th>Nama Obat</th>
                <th>Jml</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($semua_resep ?? []) as $item): ?>
            <tr>
                <td>
                    <strong><?= $item['nama_obat_final'] ?></strong>
                    <?php if (!empty($item['dosis'])): ?>
                        <br><small><?= $item['dosis'] ?></small>
                    <?php endif; ?>
                    <?php if (!empty($item['instruksi']) || !empty($item['cara_pakai'])): ?>
                        <br><span class="obat-instruksi"><?= $item['instruksi'] ?? $item['cara_pakai'] ?></span>
                    <?php endif; ?>
                </td>
                <td><?= $item['jumlah'] ?? '0' ?> <?= $item['satuan_final'] ?></td>
                <td>
                    <?php if ($item['subtotal'] > 0): ?>
                        Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="struk-total">
        Total Biaya: Rp <?= number_format($total_biaya, 0, ',', '.') ?>
    </div>
    
    <div class="struk-footer">
        <strong>CATATAN PENTING:</strong><br>
        - Simpan obat di tempat sejuk dan kering<br>
        - Ikuti petunjuk penggunaan dengan benar<br>
        - Hubungi dokter jika ada efek samping<br><br>
        Terima kasih telah menggunakan layanan farmasi kami.<br>
        SIMRS - <?= date('Y') ?>
    </div>
</div>
</body>
</html>
