<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Obat Generik Umum
            [
                'kode_obat' => 'GEN001',
                'nama_obat' => 'Paracetamol 500mg',
                'jenis_obat' => 'Tablet',
                'kategori' => 'Analgesik',
                'satuan' => 'Strip',
                'harga_beli' => 2000,
                'harga_jual' => 3500,
                'stok' => 500,
                'stok_minimal' => 50,
                'tanggal_expired' => '2025-12-31',
                'produsen' => 'Kimia Farma',
                'lokasi_penyimpanan' => 'Rak A1',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'GEN002',
                'nama_obat' => 'Amoxicillin 500mg',
                'jenis_obat' => 'Kapsul',
                'kategori' => 'Antibiotik',
                'satuan' => 'Strip',
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'stok' => 200,
                'stok_minimal' => 25,
                'tanggal_expired' => '2026-06-30',
                'produsen' => 'Indofarma',
                'lokasi_penyimpanan' => 'Rak A2',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'GEN003',
                'nama_obat' => 'ORS (Oralit)',
                'jenis_obat' => 'Serbuk',
                'kategori' => 'Elektrolit',
                'satuan' => 'Sachet',
                'harga_beli' => 1500,
                'harga_jual' => 2500,
                'stok' => 300,
                'stok_minimal' => 30,
                'tanggal_expired' => '2027-03-15',
                'produsen' => 'Pharos',
                'lokasi_penyimpanan' => 'Rak B1',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'GEN004',
                'nama_obat' => 'Antasida DOEN',
                'jenis_obat' => 'Tablet',
                'kategori' => 'Antasida',
                'satuan' => 'Strip',
                'harga_beli' => 3000,
                'harga_jual' => 5000,
                'stok' => 150,
                'stok_minimal' => 20,
                'tanggal_expired' => '2025-09-20',
                'produsen' => 'Kimia Farma',
                'lokasi_penyimpanan' => 'Rak B2',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'GEN005',
                'nama_obat' => 'Ibuprofen 400mg',
                'jenis_obat' => 'Tablet',
                'kategori' => 'NSAID',
                'satuan' => 'Strip',
                'harga_beli' => 4000,
                'harga_jual' => 6500,
                'stok' => 100,
                'stok_minimal' => 15,
                'tanggal_expired' => '2026-01-10',
                'produsen' => 'Dexa Medica',
                'lokasi_penyimpanan' => 'Rak A3',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Obat Sirup dan Suspensi
            [
                'kode_obat' => 'SIR001',
                'nama_obat' => 'Paracetamol Sirup 60ml',
                'jenis_obat' => 'Sirup',
                'kategori' => 'Analgesik',
                'satuan' => 'Botol',
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'stok' => 80,
                'stok_minimal' => 10,
                'tanggal_expired' => '2025-11-30',
                'produsen' => 'Sanbe',
                'lokasi_penyimpanan' => 'Rak C1',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'SIR002',
                'nama_obat' => 'Ambroxol Sirup 60ml',
                'jenis_obat' => 'Sirup',
                'kategori' => 'Ekspektoran',
                'satuan' => 'Botol',
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'stok' => 60,
                'stok_minimal' => 8,
                'tanggal_expired' => '2026-08-15',
                'produsen' => 'Kalbe',
                'lokasi_penyimpanan' => 'Rak C2',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Obat Injeksi
            [
                'kode_obat' => 'INJ001',
                'nama_obat' => 'Ceftriaxone 1g',
                'jenis_obat' => 'Injeksi',
                'kategori' => 'Antibiotik',
                'satuan' => 'Vial',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
                'stok' => 50,
                'stok_minimal' => 5,
                'tanggal_expired' => '2026-04-20',
                'produsen' => 'Bernofarm',
                'lokasi_penyimpanan' => 'Kulkas A',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'INJ002',
                'nama_obat' => 'Ketorolac 30mg/ml',
                'jenis_obat' => 'Injeksi',
                'kategori' => 'Analgesik',
                'satuan' => 'Ampul',
                'harga_beli' => 12000,
                'harga_jual' => 18000,
                'stok' => 40,
                'stok_minimal' => 5,
                'tanggal_expired' => '2025-10-31',
                'produsen' => 'Phapros',
                'lokasi_penyimpanan' => 'Kulkas B',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Obat Salep dan Krim
            [
                'kode_obat' => 'TOP001',
                'nama_obat' => 'Betadine Salep 5g',
                'jenis_obat' => 'Salep',
                'kategori' => 'Antiseptik',
                'satuan' => 'Tube',
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'stok' => 70,
                'stok_minimal' => 10,
                'tanggal_expired' => '2026-02-28',
                'produsen' => 'Mahakam Beta Farma',
                'lokasi_penyimpanan' => 'Rak D1',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'TOP002',
                'nama_obat' => 'Hydrocortisone Cream 2.5%',
                'jenis_obat' => 'Krim',
                'kategori' => 'Kortikosteroid',
                'satuan' => 'Tube',
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'stok' => 30,
                'stok_minimal' => 5,
                'tanggal_expired' => '2025-12-15',
                'produsen' => 'Soho',
                'lokasi_penyimpanan' => 'Rak D2',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Vitamin dan Suplemen
            [
                'kode_obat' => 'VIT001',
                'nama_obat' => 'Vitamin B Complex',
                'jenis_obat' => 'Tablet',
                'kategori' => 'Vitamin',
                'satuan' => 'Strip',
                'harga_beli' => 5000,
                'harga_jual' => 8000,
                'stok' => 120,
                'stok_minimal' => 15,
                'tanggal_expired' => '2026-07-31',
                'produsen' => 'Combiphar',
                'lokasi_penyimpanan' => 'Rak E1',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'VIT002',
                'nama_obat' => 'Vitamin C 500mg',
                'jenis_obat' => 'Tablet',
                'kategori' => 'Vitamin',
                'satuan' => 'Strip',
                'harga_beli' => 3500,
                'harga_jual' => 6000,
                'stok' => 200,
                'stok_minimal' => 20,
                'tanggal_expired' => '2026-05-20',
                'produsen' => 'Sido Muncul',
                'lokasi_penyimpanan' => 'Rak E2',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Obat Khusus
            [
                'kode_obat' => 'SPE001',
                'nama_obat' => 'Insulin Rapid Acting',
                'jenis_obat' => 'Injeksi',
                'kategori' => 'Antidiabetik',
                'satuan' => 'Pen',
                'harga_beli' => 85000,
                'harga_jual' => 120000,
                'stok' => 20,
                'stok_minimal' => 3,
                'tanggal_expired' => '2025-08-30',
                'produsen' => 'Novo Nordisk',
                'lokasi_penyimpanan' => 'Kulkas Khusus',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'SPE002',
                'nama_obat' => 'Captopril 25mg',
                'jenis_obat' => 'Tablet',
                'kategori' => 'Antihipertensi',
                'satuan' => 'Strip',
                'harga_beli' => 6000,
                'harga_jual' => 9000,
                'stok' => 80,
                'stok_minimal' => 10,
                'tanggal_expired' => '2026-03-10',
                'produsen' => 'Dexa Medica',
                'lokasi_penyimpanan' => 'Rak F1',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Obat dengan stok rendah (untuk testing alert)
            [
                'kode_obat' => 'LOW001',
                'nama_obat' => 'Domperidone 10mg',
                'jenis_obat' => 'Tablet',
                'kategori' => 'Antiemetik',
                'satuan' => 'Strip',
                'harga_beli' => 7000,
                'harga_jual' => 10000,
                'stok' => 8, // Stok rendah
                'stok_minimal' => 15,
                'tanggal_expired' => '2025-12-31',
                'produsen' => 'Kalbe',
                'lokasi_penyimpanan' => 'Rak G1',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_obat' => 'EXP001',
                'nama_obat' => 'Cetirizine 10mg',
                'jenis_obat' => 'Tablet',
                'kategori' => 'Antihistamin',
                'satuan' => 'Strip',
                'harga_beli' => 5000,
                'harga_jual' => 8000,
                'stok' => 45,
                'stok_minimal' => 10,
                'tanggal_expired' => '2025-08-15', // Akan expired soon
                'produsen' => 'Hexpharm',
                'lokasi_penyimpanan' => 'Rak G2',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        $this->db->table('obat')->insertBatch($data);
        
        echo "Data dummy obat berhasil ditambahkan: " . count($data) . " record\n";
    }
}
