<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pasien</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('pasien'); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= base_url('pasien/edit/' . $pasien['id']); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Data Pribadi</h5>
                            <table class="table">
                                <tr>
                                    <th width="200">No. Rekam Medis</th>
                                    <td><?= esc($pasien['no_rekam_medis']); ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td><?= esc($pasien['title'] . ' ' . $pasien['nama_lengkap']); ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td><?= $pasien['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                </tr>
                                <tr>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <td><?= esc($pasien['tempat_lahir']) . ', ' . date('d-m-Y', strtotime($pasien['tanggal_lahir'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Status Perkawinan</th>
                                    <td><?= esc($pasien['status_perkawinan']); ?></td>
                                </tr>
                                <tr>
                                    <th>No. Identitas</th>
                                    <td><?= esc($pasien['nomor_identitas']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= esc($pasien['email']); ?></td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td><?= esc($pasien['nomor_hp']); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Alamat</h5>
                            <table class="table">
                                <tr>
                                    <th width="200">Alamat Lengkap</th>
                                    <td><?= esc($pasien['alamat_lengkap']); ?></td>
                                </tr>
                                <tr>
                                    <th>RT/RW</th>
                                    <td><?= esc($pasien['rt'] . '/' . $pasien['rw']); ?></td>
                                </tr>
                                <tr>
                                    <th>Kelurahan</th>
                                    <td><?= esc($pasien['kelurahan']); ?></td>
                                </tr>
                                <tr>
                                    <th>Kecamatan</th>
                                    <td><?= esc($pasien['kecamatan']); ?></td>
                                </tr>
                                <tr>
                                    <th>Kabupaten/Kota</th>
                                    <td><?= esc($pasien['kabupaten_kota']); ?></td>
                                </tr>
                                <tr>
                                    <th>Provinsi</th>
                                    <td><?= esc($pasien['provinsi']); ?></td>
                                </tr>
                                <tr>
                                    <th>Kode Pos</th>
                                    <td><?= esc($pasien['kode_pos']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Informasi Medis</h5>
                            <table class="table">
                                <tr>
                                    <th width="200">Golongan Darah</th>
                                    <td><?= esc($pasien['golongan_darah'] . ' ' . $pasien['rhesus']); ?></td>
                                </tr>
                                <tr>
                                    <th>Alergi</th>
                                    <td><?= nl2br(esc($pasien['alergi'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Riwayat Penyakit</th>
                                    <td><?= nl2br(esc($pasien['riwayat_penyakit'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Catatan Medis</th>
                                    <td><?= nl2br(esc($pasien['catatan_medis'])); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Tambahan</h5>
                            <table class="table">
                                <tr>
                                    <th width="200">Agama</th>
                                    <td><?= esc($pasien['agama']); ?></td>
                                </tr>
                                <tr>
                                    <th>Pendidikan Terakhir</th>
                                    <td><?= esc($pasien['pendidikan_terakhir']); ?></td>
                                </tr>
                                <tr>
                                    <th>Pekerjaan</th>
                                    <td><?= esc($pasien['pekerjaan']); ?></td>
                                </tr>
                                <tr>
                                    <th>Kewarganegaraan</th>
                                    <td><?= esc($pasien['kewarganegaraan']); ?></td>
                                </tr>
                                <tr>
                                    <th>Suku</th>
                                    <td><?= esc($pasien['suku']); ?></td>
                                </tr>
                                <tr>
                                    <th>Bahasa</th>
                                    <td><?= esc($pasien['bahasa']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Kontak Darurat</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Hubungan</th>
                                        <th>No. HP</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kontak_darurat as $kontak) : ?>
                                        <tr>
                                            <td><?= esc($kontak['nama_kontak']); ?></td>
                                            <td><?= esc($kontak['hubungan']); ?></td>
                                            <td><?= esc($kontak['nomor_hp']); ?></td>
                                            <td><?= esc($kontak['alamat']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
