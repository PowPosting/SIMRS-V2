<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title; ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('pasien/create'); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Pasien Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?= session()->getFlashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <table id="tabelPasien" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No RM</th>
                                <th>Nama Lengkap</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>No. HP</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pasien as $p) : ?>
                                <tr>
                                    <td><?= $p['no_rekam_medis']; ?></td>
                                    <td><?= esc($p['nama_lengkap']); ?></td>
                                    <td><?= $p['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                    <td><?= date('d-m-Y', strtotime($p['tanggal_lahir'])); ?></td>
                                    <td><?= esc($p['nomor_hp']); ?></td>
                                    <td>
                                        <span class="badge badge-<?= $p['status_aktif'] == 'aktif' ? 'success' : 'danger'; ?>">
                                            <?= ucfirst($p['status_aktif']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('pasien/detail/' . $p['id']); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('pasien/edit/' . $p['id']); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusPasien(<?= $p['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data pasien ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnHapus">Hapus</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#tabelPasien').DataTable();
});

function hapusPasien(id) {
    $('#modalHapus').modal('show');
    $('#btnHapus').click(function() {
        $.ajax({
            url: '<?= base_url('pasien/delete/'); ?>' + id,
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus data: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus data');
            }
        });
    });
}
</script>
<?= $this->endSection(); ?>

<?= $this->endSection(); ?>
