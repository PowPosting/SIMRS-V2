<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fas fa-notes-medical fa-lg mr-2"></i>
            <h4 class="mb-0">Hasil Pemeriksaan Dokter</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Pasien</th>
                            <th>Poliklinik</th>
                            <th>Tanggal Pemeriksaan</th>
                            <th>Dokter</th>
                            <th>Diagnosa</th>
                            <th>Tindakan</th>
                            <th>Resep</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contoh data dummy, ganti dengan data dari controller -->
                        <tr>
                            <td class="text-center">1</td>
                            <td>Andi Wijaya</td>
                            <td>Poli Gigi</td>
                            <td>28 Juli 2025</td>
                            <td>dr. Siti</td>
                            <td>Karies gigi</td>
                            <td>Tambal gigi</td>
                            <td>Paracetamol 500mg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
