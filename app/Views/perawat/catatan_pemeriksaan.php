<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Catatan Pemeriksaan Pasien</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover" id="tabelCatatan">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Tanggal Pemeriksaan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($list_pemeriksaan)): $no=1; foreach($list_pemeriksaan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="badge bg-light text-primary"><?= esc($row['no_rm']) ?></span></td>
                                <td><?= esc($row['nama_lengkap']) ?></td>
                                <td>
                                    <?php
                                    // Format tanggal ke WIB dan format Indonesia
                                    $createdAt = $row['created_at'];
                                    try {
                                        $dt = new DateTime($createdAt, new DateTimeZone('UTC'));
                                        $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                        $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                        $tgl = (int)$dt->format('d');
                                        $bln = $bulan[(int)$dt->format('m')];
                                        $thn = $dt->format('Y');
                                    $jam = $dt->format('H:i');
                                    $tanggalWIB = $tgl . ' ' . $bln . ' ' . $thn . ' ' . $jam ;
                                    echo esc($tanggalWIB);
                                    } catch (Exception $e) {
                                        echo esc($createdAt);
                                    }
                                    ?>
                                </td>
                                <td><button class="btn btn-info btn-sm btnDetail" data-id="<?= esc($row['id']) ?>"><i class="fas fa-eye"></i> Lihat Detail</button></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-muted">Belum ada data pemeriksaan.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pemeriksaan -->
<div class="modal fade" id="modalDetailPemeriksaan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Hasil Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailBody">
                <!-- Konten detail akan dimuat di sini -->
                <div class="text-center text-muted">Pilih pasien untuk melihat detail hasil pemeriksaan.</div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Handler tombol detail dinamis (delegasi event)
    $('#tabelCatatan').on('click', '.btnDetail', function() {
        var id = $(this).data('id');
        $('#detailBody').html('<div class="text-center text-muted">Memuat data...</div>');
        $.get('/perawat/detail-pemeriksaan/' + id, function(res) {
            $('#detailBody').html(res);
        }).fail(function() {
            $('#detailBody').html('<div class="alert alert-danger">Gagal memuat detail pemeriksaan.</div>');
        });
        $('#modalDetailPemeriksaan').modal('show');
    });
});
</script>
<?= $this->endSection() ?>
