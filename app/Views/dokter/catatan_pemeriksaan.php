<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.2);
        margin-top: 2rem;
    }
    .badge {
        font-size: 0.875rem !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Catatan Pemeriksaan Pasien</h3>
                </div>
                <div class="card-body table-responsive">
                    <div class="mb-3 row justify-content-start align-items-center">
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari data pemeriksaan..." style="width: 220px;">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white border-left-0"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <td><span class="badge bg-light text-dark"><?= esc($row['no_rm']) ?></span></td>
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
                                <td><button class="btn btn-primary btn-sm btnDetail" data-id="<?= esc($row['id']) ?>"><i class="fas fa-eye"></i> Lihat Detail</button></td>
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
            <div class="modal-footer justify-content-end">
                <div id="exportBtnContainer" style="display:none;">
                    <a href="#" id="btnExportWord" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file-word mr-1"></i> Export Word
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Filter manual tanpa DataTables
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#tabelCatatan tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    // Handler tombol detail dinamis (delegasi event)
    $('#tabelCatatan').on('click', '.btnDetail', function() {
        var id = $(this).data('id');
        $('#detailBody').html('<div class="text-center text-muted">Memuat data...</div>');
        $.get('/dokter/detail-pemeriksaan/' + id, function(res) {
            $('#detailBody').html(res);
            // Tampilkan tombol export jika id ada
            $('#exportBtnContainer').show();
            $('#btnExportWord').attr('href', '/exportword/pemeriksaan/' + id);
        }).fail(function() {
            $('#detailBody').html('<div class="alert alert-danger">Gagal memuat detail pemeriksaan.</div>');
            $('#exportBtnContainer').hide();
        });
        $('#modalDetailPemeriksaan').modal('show');
    });
});
</script>
<?= $this->endSection() ?>
