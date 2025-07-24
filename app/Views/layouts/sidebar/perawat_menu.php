<!-- Perawat Menu -->
<li class="nav-header">NURSE STATION</li>

<!-- Antrian Pendaftaran -->
<li class="nav-item">
    <a href="<?= base_url('perawat/antrian-pendaftaran') ?>" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Antrian dari Pendaftaran">
        <i class="fas fa-list-ol nav-icon"></i>
        <span class="nav-text">Antrian Pendaftaran</span>
        <span class="badge badge-danger right" id="jumlah_antrian_baru">0</span>
    </a>
</li>

<!-- Pemeriksaan Awal -->
<li class="nav-item">
    <a href="<?= base_url('perawat/pemeriksaan-awal') ?>" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Pemeriksaan Awal Pasien">
        <i class="fas fa-stethoscope nav-icon"></i>
        <span class="nav-text">Pemeriksaan Awal</span>
    </a>
</li>

<!-- Antrian Poli -->
<li class="nav-item">
    <a href="<?= base_url('perawat/antrian-poli') ?>" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Antrian Poliklinik">
        <i class="fas fa-procedures nav-icon"></i>
        <span class="nav-text">Antrian Poli</span>
    </a>
</li>

<!-- Riwayat Tindakan -->
<li class="nav-item">
    <a href="<?= base_url('perawat/riwayat-tindakan') ?>" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Riwayat Tindakan">
        <i class="fas fa-history nav-icon"></i>
        <span class="nav-text">Riwayat Tindakan</span>
    </a>
</li>

<!-- Jadwal Shift -->
<li class="nav-item">
    <a href="<?= base_url('perawat/jadwal-shift') ?>" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Jadwal Shift">
        <i class="fas fa-clock nav-icon"></i>
        <span class="nav-text">Jadwal Shift</span>
    </a>
</li>

<script>
function updateJumlahAntrian() {
    $.ajax({
        url: '<?= base_url('perawat/get-jumlah-antrian') ?>',
        method: 'GET',
        success: function(response) {
            $('#jumlah_antrian_baru').text(response.jumlah);
            if(response.jumlah > 0) {
                $('#jumlah_antrian_baru').show();
            } else {
                $('#jumlah_antrian_baru').hide();
            }
        }
    });
}

// Update setiap 30 detik
setInterval(updateJumlahAntrian, 30000);
// Update pertama kali saat halaman dimuat
$(document).ready(function() {
    updateJumlahAntrian();
});
</script>
