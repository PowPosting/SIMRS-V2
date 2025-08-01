-- Data Dummy untuk Tabel Obat (Sesuai Form Tambah Obat)
-- SIMRS v2 - Sistem Informasi Manajemen Rumah Sakit

-- Bersihkan data lama (opsional)
-- TRUNCATE TABLE obat;

-- Insert data dummy obat sesuai dengan form tambahobat.php
INSERT INTO obat (kode_obat, nama_obat, jenis_obat, kategori, satuan, harga_beli, harga_jual, stok, stok_minimal, tanggal_expired, produsen, lokasi_penyimpanan, status, created_at, updated_at) VALUES 

-- Obat Generik - Tablet
('GEN001', 'Paracetamol 500mg', 'Tablet', 'Generik', 'Tablet', 2000.00, 3500.00, 500, 50, '2025-12-31', 'Kimia Farma', 'Rak A1', 'Aktif', NOW(), NOW()),
('GEN002', 'Ibuprofen 400mg', 'Tablet', 'Generik', 'Tablet', 4000.00, 6500.00, 300, 30, '2026-06-15', 'Dexa Medica', 'Rak A2', 'Aktif', NOW(), NOW()),
('GEN003', 'Asam Mefenamat 500mg', 'Tablet', 'Generik', 'Tablet', 3500.00, 5500.00, 200, 25, '2026-03-20', 'Sanbe', 'Rak A3', 'Aktif', NOW(), NOW()),
('GEN004', 'Antasida DOEN', 'Tablet', 'Generik', 'Tablet', 3000.00, 5000.00, 150, 20, '2025-09-30', 'Kimia Farma', 'Rak A4', 'Aktif', NOW(), NOW()),
('GEN005', 'Vitamin C 500mg', 'Tablet', 'Generik', 'Tablet', 3500.00, 6000.00, 400, 40, '2026-11-10', 'Sido Muncul', 'Rak A5', 'Aktif', NOW(), NOW()),

-- Obat Generik - Kapsul  
('GEN006', 'Amoxicillin 500mg', 'Kapsul', 'Generik', 'Kapsul', 8000.00, 12000.00, 250, 25, '2026-08-25', 'Indofarma', 'Rak B1', 'Aktif', NOW(), NOW()),
('GEN007', 'Omeprazole 20mg', 'Kapsul', 'Generik', 'Kapsul', 8500.00, 13000.00, 180, 20, '2026-07-15', 'Dexa Medica', 'Rak B2', 'Aktif', NOW(), NOW()),
('GEN008', 'Piroxicam 20mg', 'Kapsul', 'Generik', 'Kapsul', 6000.00, 9000.00, 120, 15, '2025-12-05', 'Sanbe', 'Rak B3', 'Aktif', NOW(), NOW()),

-- Obat Paten - Tablet
('PAT001', 'Panadol 500mg', 'Tablet', 'Paten', 'Tablet', 8000.00, 12000.00, 100, 10, '2026-04-30', 'GSK', 'Rak C1', 'Aktif', NOW(), NOW()),
('PAT002', 'Bodrex Extra', 'Tablet', 'Paten', 'Tablet', 6000.00, 9000.00, 80, 10, '2025-11-20', 'Tempo Scan', 'Rak C2', 'Aktif', NOW(), NOW()),
('PAT003', 'Neuralgin RX', 'Tablet', 'Paten', 'Tablet', 7500.00, 11000.00, 90, 12, '2026-02-14', 'Tempo Scan', 'Rak C3', 'Aktif', NOW(), NOW()),

-- Obat Sirup
('SIR001', 'Paracetamol Sirup 60ml', 'Sirup', 'Generik', 'Botol', 8000.00, 12000.00, 75, 10, '2025-10-15', 'Sanbe', 'Rak D1', 'Aktif', NOW(), NOW()),
('SIR002', 'Ambroxol Sirup 60ml', 'Sirup', 'Generik', 'Botol', 15000.00, 22000.00, 60, 8, '2026-05-22', 'Kalbe', 'Rak D2', 'Aktif', NOW(), NOW()),
('SIR003', 'OBH Combi Sirup', 'Sirup', 'Paten', 'Botol', 18000.00, 25000.00, 45, 6, '2025-09-18', 'OBH', 'Rak D3', 'Aktif', NOW(), NOW()),
('SIR004', 'Tempra Sirup 60ml', 'Sirup', 'Paten', 'Botol', 22000.00, 30000.00, 35, 5, '2026-01-12', 'Taisho', 'Rak D4', 'Aktif', NOW(), NOW()),

-- Obat Injeksi
('INJ001', 'Ceftriaxone 1g', 'Injeksi', 'Generik', 'Vial', 25000.00, 35000.00, 50, 5, '2026-03-28', 'Bernofarm', 'Kulkas A', 'Aktif', NOW(), NOW()),
('INJ002', 'Ketorolac 30mg/ml', 'Injeksi', 'Generik', 'Ampul', 12000.00, 18000.00, 40, 5, '2025-08-30', 'Phapros', 'Kulkas A', 'Aktif', NOW(), NOW()),
('INJ003', 'Dexamethasone 5mg/ml', 'Injeksi', 'Generik', 'Ampul', 8000.00, 12000.00, 60, 8, '2026-06-10', 'Kimia Farma', 'Kulkas B', 'Aktif', NOW(), NOW()),
('INJ004', 'Vitamin B Complex', 'Injeksi', 'Generik', 'Ampul', 15000.00, 22000.00, 30, 5, '2025-12-20', 'Combiphar', 'Kulkas B', 'Aktif', NOW(), NOW()),

-- Obat Salep
('SAL001', 'Betadine Salep 5g', 'Salep', 'Paten', 'Tube', 8000.00, 12000.00, 70, 10, '2026-02-28', 'Mahakam Beta Farma', 'Rak E1', 'Aktif', NOW(), NOW()),
('SAL002', 'Gentamicin Salep', 'Salep', 'Generik', 'Tube', 12000.00, 18000.00, 45, 6, '2025-11-05', 'Indofarma', 'Rak E2', 'Aktif', NOW(), NOW()),
('SAL003', 'Hydrocortisone Cream', 'Salep', 'Generik', 'Tube', 15000.00, 22000.00, 35, 5, '2025-10-12', 'Soho', 'Rak E3', 'Aktif', NOW(), NOW()),

-- Obat Drops/Tetes
('DRP001', 'Paracetamol Drop 15ml', 'Drops', 'Generik', 'Botol', 12000.00, 18000.00, 40, 6, '2025-09-25', 'Tempra', 'Rak F1', 'Aktif', NOW(), NOW()),
('DRP002', 'Chloramphenicol Eye Drop', 'Drops', 'Generik', 'Botol', 15000.00, 22000.00, 25, 4, '2025-12-08', 'Cendo', 'Rak F2', 'Aktif', NOW(), NOW()),
('DRP003', 'Otopain Ear Drop', 'Drops', 'Paten', 'Botol', 20000.00, 28000.00, 20, 3, '2026-04-15', 'Kimia Farma', 'Rak F3', 'Aktif', NOW(), NOW()),

-- Obat Suppositoria
('SUP001', 'Paracetamol Suppositoria', 'Suppositoria', 'Generik', 'Box', 25000.00, 35000.00, 15, 3, '2025-08-18', 'Sanbe', 'Kulkas C', 'Aktif', NOW(), NOW()),
('SUP002', 'Bisacodyl Suppositoria', 'Suppositoria', 'Generik', 'Box', 18000.00, 25000.00, 20, 4, '2026-01-22', 'Dexa Medica', 'Kulkas C', 'Aktif', NOW(), NOW()),

-- Obat Psikotropika
('PSI001', 'Diazepam 5mg', 'Tablet', 'Psikotropika', 'Tablet', 5000.00, 8000.00, 50, 5, '2026-07-30', 'Kimia Farma', 'Lemari Khusus', 'Aktif', NOW(), NOW()),
('PSI002', 'Alprazolam 0.5mg', 'Tablet', 'Psikotropika', 'Tablet', 8000.00, 12000.00, 30, 3, '2025-11-28', 'Dexa Medica', 'Lemari Khusus', 'Aktif', NOW(), NOW()),

-- Obat Narkotika  
('NAR001', 'Morphine 10mg', 'Injeksi', 'Narkotika', 'Ampul', 50000.00, 75000.00, 10, 2, '2025-10-31', 'Phapros', 'Brankas Narkotika', 'Aktif', NOW(), NOW()),
('NAR002', 'Pethidine 50mg/ml', 'Injeksi', 'Narkotika', 'Ampul', 45000.00, 65000.00, 12, 2, '2026-02-17', 'Bernofarm', 'Brankas Narkotika', 'Aktif', NOW(), NOW()),

-- Obat dengan stok rendah (untuk testing alert)
('LOW001', 'Domperidone 10mg', 'Tablet', 'Generik', 'Tablet', 7000.00, 10000.00, 8, 15, '2025-12-31', 'Kalbe', 'Rak G1', 'Aktif', NOW(), NOW()),
('LOW002', 'Cetirizine 10mg', 'Tablet', 'Generik', 'Tablet', 5000.00, 8000.00, 5, 12, '2025-08-20', 'Hexpharm', 'Rak G2', 'Aktif', NOW(), NOW()),

-- Obat Lainnya
('LAI001', 'ORS Sachet', 'Lainnya', 'Generik', 'Sachet', 1500.00, 2500.00, 300, 30, '2027-01-15', 'Pharos', 'Rak H1', 'Aktif', NOW(), NOW()),
('LAI002', 'Kasa Steril 5x5', 'Lainnya', 'Lainnya', 'Box', 8000.00, 12000.00, 100, 15, '2026-12-31', 'OneMed', 'Rak H2', 'Aktif', NOW(), NOW()),
('LAI003', 'Alkohol 70% 100ml', 'Lainnya', 'Lainnya', 'Botol', 5000.00, 8000.00, 80, 10, '2026-09-22', 'Onehealth', 'Rak H3', 'Aktif', NOW(), NOW());

-- Query untuk cek data yang berhasil diinsert
-- SELECT COUNT(*) as total_obat FROM obat;

-- Query untuk cek obat berdasarkan kategori
-- SELECT kategori, COUNT(*) as jumlah FROM obat GROUP BY kategori ORDER BY jumlah DESC;

-- Query untuk cek obat dengan stok rendah
-- SELECT kode_obat, nama_obat, stok, stok_minimal, (stok - stok_minimal) as selisih FROM obat WHERE stok <= stok_minimal ORDER BY selisih ASC;

-- Query untuk cek obat yang akan expired dalam 6 bulan
-- SELECT kode_obat, nama_obat, tanggal_expired, DATEDIFF(tanggal_expired, CURDATE()) as hari_tersisa FROM obat WHERE tanggal_expired <= DATE_ADD(CURDATE(), INTERVAL 6 MONTH) ORDER BY tanggal_expired ASC;

-- Query untuk cek obat berdasarkan jenis
-- SELECT jenis_obat, COUNT(*) as jumlah FROM obat GROUP BY jenis_obat ORDER BY jumlah DESC;
