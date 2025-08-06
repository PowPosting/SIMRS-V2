
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Cetak Invoice' ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 14px; margin: 0; padding: 0; }
        .struk-container { max-width: 380px; margin: 0 auto; background: #fff; border: 1px solid #ccc; padding: 18px 18px 8px 18px; }
        .struk-header { text-align: center; margin-bottom: 10px; }
        .struk-header h2 { margin: 0 0 4px 0; font-size: 20px; }
        .struk-header small { color: #888; }
        .struk-info { margin-bottom: 10px; }
        .struk-info table { width: 100%; }
        .struk-info td { padding: 2px 0; }
        .struk-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .struk-table th, .struk-table td { border-bottom: 1px dashed #bbb; padding: 4px 0; text-align: left; }
        .struk-table th { font-size: 13px; }
        .struk-table td { font-size: 13px; }
        .struk-total { text-align: right; font-weight: bold; font-size: 15px; margin-top: 10px; }
        .struk-footer { text-align: center; margin-top: 18px; font-size: 12px; color: #888; }
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
        <h2>STRUK OBAT FARMASI</h2>
        <small><?= date('d/m/Y H:i') ?></small>
    </div>
    <div class="struk-info">
        <table>
            <tr>
                <td>Nama Pasien</td>
                <td>: <b><?= $pasien['nama_pasien'] ?? '-' ?></b></td>
            </tr>
            <tr>
                <td>No. RM</td>
                <td>: <?= $pasien['no_rm'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Dokter</td>
                <td>: <?= $pasien['nama_dokter'] ?? '-' ?></td>
            </tr>
        </table>
    </div>
    <table class="struk-table">
        <thead>
            <tr>
                <th>Obat</th>
                <th>Jml</th>
                <th>Tagihan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            foreach (($permintaan_list ?? []) as $item): 
                $harga = isset($item['harga_jual']) ? $item['harga_jual'] : null;
                $subtotal = ($harga !== null && isset($item['jumlah'])) ? ($harga * $item['jumlah']) : null;
                $total += $subtotal ?? 0;
            ?>
            <tr>
                <td><?= $item['nama_obat'] ?? '-' ?>
                    <?php if (!empty($item['instruksi'])): ?>
                        <br><small><?= $item['instruksi'] ?></small>
                    <?php endif; ?>
                </td>
                <td><?= $item['jumlah'] ?? '0' ?> <?= $item['satuan'] ?? '' ?></td>
                <td><?= $subtotal !== null ? 'Rp ' . number_format($subtotal,0,',','.') : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="struk-total">
        Total Tagihan: 
        <?= $total > 0 ? 'Rp ' . number_format($total,0,',','.') : '-' ?>
    </div>
    <div class="struk-footer">
        Terima kasih telah menggunakan layanan farmasi kami.<br>
        SIMRS - <?= date('Y') ?>
    </div>
</div>
</body>
</html>
