<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
/* Modern Success Page Styles */
.success-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 0;
}

.success-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    border: none;
    overflow: hidden;
    max-width: 600px;
    width: 100%;
}

.success-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 3rem 2rem 2rem;
    text-align: center;
    color: white;
    position: relative;
}

.success-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,100 1000,0 1000,100"/></svg>');
    background-size: cover;
}

.success-icon {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    backdrop-filter: blur(10px);
    animation: successPulse 2s infinite;
}

@keyframes successPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.success-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
}

.success-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    position: relative;
}

.success-body {
    padding: 2.5rem;
}

.info-card {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
    border: 2px solid #e3f2fd;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.info-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.info-title {
    color: #2196f3;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(33, 150, 243, 0.1);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #666;
    font-weight: 500;
}

.info-value {
    color: #2196f3;
    font-weight: 700;
    font-size: 1.1rem;
}

.next-step {
    background: #fff3e0;
    border: 1px solid #ffcc02;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.next-step-icon {
    color: #ff9800;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.next-step-text {
    color: #e65100;
    font-weight: 500;
    margin: 0;
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.btn-modern {
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
    min-width: 160px;
    justify-content: center;
}

.btn-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s;
}

.btn-modern:hover::before {
    left: 100%;
}

.btn-print {
    background: linear-gradient(135deg, #424242 0%, #212121 100%);
    color: white;
}

.btn-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(66, 66, 66, 0.3);
    color: white;
}

.btn-register {
    background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
    color: white;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
    color: white;
}

.btn-view {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    color: white;
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(33, 150, 243, 0.3);
    color: white;
}

@media (max-width: 768px) {
    .success-header {
        padding: 2rem 1.5rem 1.5rem;
    }
    
    .success-title {
        font-size: 1.5rem;
    }
    
    .success-body {
        padding: 2rem 1.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-modern {
        width: 100%;
    }
}
</style>
<div class="container-fluid">
    <div class="success-container">
        <div class="success-card">
            <!-- Header Section -->
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle" style="font-size: 3rem;"></i>
                </div>
                <h1 class="success-title">Pendaftaran Berhasil!</h1>
                <p class="success-subtitle">Selamat! Pendaftaran Anda telah berhasil diproses</p>
            </div>

            <!-- Body Section -->
            <div class="success-body">
                <!-- Registration Info -->
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-info-circle"></i>
                        Informasi Pendaftaran
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nomor Rekam Medis</span>
                        <span class="info-value"><?= $no_rm ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nomor Antrian</span>
                        <span class="info-value"><?= $no_antrian ?></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admisi/print-antrian/' . $no_antrian) ?>" 
                       class="btn-modern btn-print" target="_blank">
                        <i class="fas fa-print"></i>
                        <span>Cetak Antrian</span>
                    </a>
                    <a href="<?= base_url('admisi/registrasi-pasien') ?>" 
                       class="btn-modern btn-register">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar Baru</span>
                    </a>
                    <a href="<?= base_url('admisi/datapasien') ?>" 
                       class="btn-modern btn-view">
                        <i class="fas fa-list"></i>
                        <span>Lihat Data</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
