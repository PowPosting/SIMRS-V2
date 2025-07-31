<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height:80vh;">
    <div class="card shadow-lg p-4" style="border-radius:1.2rem; max-width:420px;">
        <div class="text-center mb-3">
            <i class="bi bi-check-circle-fill" style="font-size:3rem; color:#22c55e;"></i>
        </div>
        <h2 class="text-success text-center mb-2" style="font-weight:800;">Data pemeriksaan berhasil disimpan!</h2>
        <p class="text-center mb-4" style="color:#555;">Terima kasih, data SOAP pasien sudah tercatat di sistem.</p>
        <div class="d-flex justify-content-center gap-2">
            <a href="/dokter/antrian-poli" class="btn btn-outline-secondary">Kembali ke Antrian</a>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<?= $this->endSection() ?>
