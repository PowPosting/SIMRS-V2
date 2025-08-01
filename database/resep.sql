-- Tabel resep untuk menyimpan permintaan obat dari dokter
CREATE TABLE resep (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pasien INT NOT NULL,
    id_dokter INT NOT NULL,
    id_rekam_medis INT NULL,
    id_obat INT NULL,
    nama_obat VARCHAR(100) NOT NULL,
    jumlah INT NOT NULL DEFAULT 0,
    satuan VARCHAR(20) NULL,
    instruksi TEXT NULL,
    dosis VARCHAR(50) NULL,
    cara_pakai VARCHAR(100) NULL,
    tanggal_resep DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tanggal_diproses DATETIME NULL,
    tanggal_selesai DATETIME NULL,
    status ENUM('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
    diproses_oleh INT NULL,
    diselesaikan_oleh INT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_id_pasien (id_pasien),
    INDEX idx_id_dokter (id_dokter),
    INDEX idx_status (status),
    INDEX idx_tanggal_resep (tanggal_resep),
    FOREIGN KEY (id_pasien) REFERENCES pasien(id) ON DELETE CASCADE,
    FOREIGN KEY (id_dokter) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_rekam_medis) REFERENCES pemeriksaan_soap(id) ON DELETE SET NULL,
    FOREIGN KEY (id_obat) REFERENCES obat(id_obat) ON DELETE SET NULL,
    FOREIGN KEY (diproses_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (diselesaikan_oleh) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data dummy untuk testing
INSERT INTO resep (id_pasien, id_dokter, id_rekam_medis, nama_obat, jumlah, satuan, instruksi, dosis, cara_pakai, tanggal_resep, status) VALUES
(1, 1, 1, 'Amlodipine 5mg', 30, 'tablet', '1x1 sehari setelah makan', '5mg', '1 tablet sekali sehari', NOW(), 'pending'),
(2, 2, 2, 'Metformin 500mg', 60, 'tablet', '2x1 sebelum makan', '500mg', '1 tablet 2 kali sehari', DATE_SUB(NOW(), INTERVAL 1 HOUR), 'processing'),
(3, 3, 3, 'Omeprazole 20mg', 14, 'kapsul', '1x1 pagi sebelum makan', '20mg', '1 kapsul sekali sehari pagi', DATE_SUB(NOW(), INTERVAL 2 HOUR), 'completed');
