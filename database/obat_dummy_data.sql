-- Data Dummy untuk Tabel Obat
-- SIMRS v2 - Sistem Informasi Manajemen Rumah Sakit

-- Bersihkan data lama (opsional)
-- TRUNCATE TABLE obat;

-- Insert data dummy obat
INSERT INTO obat (kode_obat, nama_obat, jenis_obat, kategori, satuan, harga_beli, harga_jual, stok, stok_minimal, tanggal_expired, produsen, lokasi_penyimpanan, status, created_at, updated_at) VALUES 

-- Obat Generik Umum
('GEN001', 'Paracetamol 500mg', 'Tablet', 'Analgesik', 'Strip', 2000, 3500, 500, 50, '2025-12-31', 'Kimia Farma', 'Rak A1', 'Aktif', NOW(), NOW()),
('GEN002', 'Amoxicillin 500mg', 'Kapsul', 'Antibiotik', 'Strip', 8000, 12000, 200, 25, '2026-06-30', 'Indofarma', 'Rak A2', 'Aktif', NOW(), NOW()),
('GEN003', 'ORS (Oralit)', 'Serbuk', 'Elektrolit', 'Sachet', 1500, 2500, 300, 30, '2027-03-15', 'Pharos', 'Rak B1', 'Aktif', NOW(), NOW()),
('GEN004', 'Antasida DOEN', 'Tablet', 'Antasida', 'Strip', 3000, 5000, 150, 20, '2025-09-20', 'Kimia Farma', 'Rak B2', 'Aktif', NOW(), NOW()),
('GEN005', 'Ibuprofen 400mg', 'Tablet', 'NSAID', 'Strip', 4000, 6500, 100, 15, '2026-01-10', 'Dexa Medica', 'Rak A3', 'Aktif', NOW(), NOW()),

-- Obat Sirup dan Suspensi
('SIR001', 'Paracetamol Sirup 60ml', 'Sirup', 'Analgesik', 'Botol', 8000, 12000, 80, 10, '2025-11-30', 'Sanbe', 'Rak C1', 'Aktif', NOW(), NOW()),
('SIR002', 'Ambroxol Sirup 60ml', 'Sirup', 'Batuk', 'Botol', 15000, 22000, 60, 8, '2026-08-15', 'Kalbe', 'Rak C2', 'Aktif', NOW(), NOW()),

-- Obat Injeksi
('INJ001', 'Ceftriaxone 1g', 'Injeksi', 'Antibiotik', 'Vial', 25000, 35000, 50, 5, '2026-04-20', 'Bernofarm', 'Kulkas A', 'Aktif', NOW(), NOW()),
('INJ002', 'Ketorolac 30mg/ml', 'Injeksi', 'Analgesik', 'Ampul', 12000, 18000, 40, 5, '2025-10-31', 'Phapros', 'Kulkas B', 'Aktif', NOW(), NOW()),

-- Obat Salep dan Krim
('TOP001', 'Betadine Salep 5g', 'Salep', 'Antiseptik', 'Tube', 8000, 12000, 70, 10, '2026-02-28', 'Mahakam Beta Farma', 'Rak D1', 'Aktif', NOW(), NOW()),
('TOP002', 'Hydrocortisone Cream 2.5%', 'Krim', 'Steroid', 'Tube', 15000, 22000, 30, 5, '2025-12-15', 'Soho', 'Rak D2', 'Aktif', NOW(), NOW()),

-- Vitamin dan Suplemen
('VIT001', 'Vitamin B Complex', 'Tablet', 'Vitamin', 'Strip', 5000, 8000, 120, 15, '2026-07-31', 'Combiphar', 'Rak E1', 'Aktif', NOW(), NOW()),
('VIT002', 'Vitamin C 500mg', 'Tablet', 'Vitamin', 'Strip', 3500, 6000, 200, 20, '2026-05-20', 'Sido Muncul', 'Rak E2', 'Aktif', NOW(), NOW()),

-- Obat Khusus
('SPE001', 'Insulin Rapid Acting', 'Injeksi', 'Diabetes', 'Pen', 85000, 120000, 20, 3, '2025-08-30', 'Novo Nordisk', 'Kulkas Khusus', 'Aktif', NOW(), NOW()),
('SPE002', 'Captopril 25mg', 'Tablet', 'Hipertensi', 'Strip', 6000, 9000, 80, 10, '2026-03-10', 'Dexa Medica', 'Rak F1', 'Aktif', NOW(), NOW()),

-- Obat dengan stok rendah (untuk testing alert)
('LOW001', 'Domperidone 10mg', 'Tablet', 'Mual', 'Strip', 7000, 10000, 8, 15, '2025-12-31', 'Kalbe', 'Rak G1', 'Aktif', NOW(), NOW()),
('EXP001', 'Cetirizine 10mg', 'Tablet', 'Alergi', 'Strip', 5000, 8000, 45, 10, '2025-08-15', 'Hexpharm', 'Rak G2', 'Aktif', NOW(), NOW()),

-- Obat Tambahan untuk Variasi Data
('GEN006', 'Asam Mefenamat 500mg', 'Tablet', 'NSAID', 'Strip', 3500, 5500, 90, 12, '2026-02-20', 'Sanbe', 'Rak A4', 'Aktif', NOW(), NOW()),
('GEN007', 'Ciprofloxacin 500mg', 'Tablet', 'Antibiotik', 'Strip', 12000, 18000, 75, 10, '2025-11-15', 'Bayer', 'Rak A5', 'Aktif', NOW(), NOW()),
('GEN008', 'Omeprazole 20mg', 'Kapsul', 'PPI', 'Strip', 8500, 13000, 85, 12, '2026-09-10', 'Dexa Medica', 'Rak B3', 'Aktif', NOW(), NOW()),

-- Obat Anak
('PED001', 'Paracetamol Drop 15ml', 'Drop/Tetes', 'Analgesik', 'Botol', 12000, 18000, 45, 8, '2025-10-25', 'Tempra', 'Rak C3', 'Aktif', NOW(), NOW()),
('PED002', 'Zinc Sirup 100ml', 'Sirup', 'Suplemen', 'Botol', 18000, 25000, 35, 6, '2026-01-30', 'Kalbe', 'Rak C4', 'Aktif', NOW(), NOW()),

-- Obat Mata dan THT
('OPH001', 'Chloramphenicol Eye Drop', 'Tetes Mata', 'Antibiotik', 'Botol', 15000, 22000, 25, 5, '2025-12-01', 'Cendo', 'Rak H1', 'Aktif', NOW(), NOW()),
('ENT001', 'Otopain Ear Drop', 'Tetes Telinga', 'Analgesik', 'Botol', 20000, 28000, 20, 4, '2026-04-15', 'Kimia Farma', 'Rak H2', 'Aktif', NOW(), NOW()),

-- Obat Emergency
('EMG001', 'Epinephrine 1mg/ml', 'Injeksi', 'Emergency', 'Ampul', 45000, 65000, 15, 3, '2025-09-30', 'Kalbe', 'Emergency Kit', 'Aktif', NOW(), NOW()),
('EMG002', 'Atropine 0.25mg/ml', 'Injeksi', 'Emergency', 'Ampul', 25000, 35000, 12, 3, '2025-11-20', 'Phapros', 'Emergency Kit', 'Aktif', NOW(), NOW()),

-- Obat Kulit
('DER001', 'Ketoconazole Cream 2%', 'Krim', 'Antifungi', 'Tube', 18000, 25000, 40, 6, '2026-05-25', 'Interbat', 'Rak D3', 'Aktif', NOW(), NOW()),
('DER002', 'Calamine Lotion 60ml', 'Lotion', 'Gatel', 'Botol', 8000, 12500, 55, 8, '2025-08-20', 'Kimia Farma', 'Rak D4', 'Aktif', NOW(), NOW());

-- Query untuk cek data yang berhasil diinsert
-- SELECT COUNT(*) as total_obat FROM obat;

-- Query untuk cek obat dengan stok rendah
-- SELECT kode_obat, nama_obat, stok, stok_minimal FROM obat WHERE stok <= stok_minimal;

-- Query untuk cek obat yang akan expired dalam 3 bulan
-- SELECT kode_obat, nama_obat, tanggal_expired FROM obat WHERE tanggal_expired <= DATE_ADD(CURDATE(), INTERVAL 3 MONTH) ORDER BY tanggal_expired ASC;
