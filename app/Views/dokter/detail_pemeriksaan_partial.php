<?php
// Format tanggal ke format Indonesia
$createdAt = $pemeriksaan['created_at'] ?? '';
$tanggalWIB = '-';
if ($createdAt) {
    try {
        $dt = new DateTime($createdAt, new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
        $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $tgl = (int)$dt->format('d');
        $bln = $bulan[(int)$dt->format('m')];
        $thn = $dt->format('Y');
        $tanggalWIB = $tgl . ' ' . $bln . ' ' . $thn;
    } catch (Exception $e) {
        $tanggalWIB = $createdAt;
    }
}
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-id-card mr-1"></i> No. RM</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['no_rekam_medis'] ?? '-') ?></span>
        </div>
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-user mr-1"></i> Nama Pasien</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['nama_lengkap'] ?? '-') ?></span>
        </div>
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Pemeriksaan</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($tanggalWIB) ?></span>
        </div>
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-heartbeat mr-1"></i> Tekanan Darah</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['tekanan_darah'] ?? '-') ?> mmHg</span>
        </div>
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-heart mr-1"></i> Detak Jantung</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['detak_jantung'] ?? '-') ?> x/menit</span>
        </div>
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-thermometer-half mr-1"></i> Suhu Tubuh</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['suhu_tubuh'] ?? '-') ?> °C</span>
        </div>
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-lungs mr-1"></i> Pernafasan</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['pernafasan'] ?? '-') ?> x/menit</span>
        </div>
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-weight mr-1"></i> Berat Badan</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['berat_badan'] ?? '-') ?> kg</span>
        </div>
        <div class="col-md-6 mb-3">
            <span class="text-muted small"><i class="fas fa-ruler-vertical mr-1"></i> Tinggi Badan</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['tinggi_badan'] ?? '-') ?> cm</span>
        </div>
        <div class="col-md-12 mb-2">
            <span class="text-muted small"><i class="fas fa-notes-medical mr-1"></i> Keluhan</span><br>
            <span style="color:#1781FF;font-weight:400;"><?= esc($pemeriksaan['keluhan'] ?? '-') ?></span>
        </div>
    </div>
</div>
