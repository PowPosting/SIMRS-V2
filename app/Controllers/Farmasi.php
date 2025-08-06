<?php

namespace App\Controllers;

class Farmasi extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        
        // Check if user is logged in and has farmasi role OR admin role
        $userRole = $this->session->get('role');
        if (!$this->session->get('isLoggedIn') || ($userRole !== 'farmasi' && $userRole !== 'admin')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Farmasi - SIMRS',
            'pageTitle' => 'Dashboard Farmasi',
            'stats' => [
                'pending_prescriptions' => 15,
                'low_stock_items' => 8,
                'daily_dispensed' => 45,
                'total_medicines' => 250
            ]
        ];

        return view('farmasi/dashboard', $data);
    }

    public function stokObat()
    {
        $db = \Config\Database::connect();
        $list_obat = $db->table('obat')->orderBy('nama_obat','asc')->get()->getResultArray();
        $data = [
            'title' => 'Stok Obat - SIMRS',
            'pageTitle' => 'Stok Obat',
            'list_obat' => $list_obat
        ];
        return view('farmasi/datamasterobat', $data);
    }

    public function tambahObat()
    {
        // Logic to display form for adding new medicine
        $data = [
            'title' => 'Tambah Obat - SIMRS',
            'pageTitle' => 'Tambah Obat'
        ];
        // Load the view for adding medicine
        return view('farmasi/tambahobat', $data);
    }

    public function editObat($id_obat)
    {
        $db = \Config\Database::connect();
        $obat = $db->table('obat')->where('id_obat', $id_obat)->get()->getRowArray();
        if (!$obat) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Obat not found');
        }
        $data = [
            'title' => 'Edit Obat - SIMRS',
            'pageTitle' => 'Edit Obat',
            'obat' => $obat
        ];
        return view('farmasi/editobat', $data);
    }

    public function deleteObat($id_obat)
    {
        $db = \Config\Database::connect();
        $db->table('obat')->where('id_obat', $id_obat)->delete();

        //message to show success
        $this->session->setFlashdata('success', 'Obat berhasil dihapus');
        // Redirect back to the list of medicines
        return redirect()->to('/farmasi/data-master-obat')->with('success', 'Obat berhasil dihapus');
    }

    public function simpanObat()
    {
        // Logic to save new or edited medicine
        $db = \Config\Database::connect();
        $data = [
            'kode_obat' => $this->request->getPost('kode_obat'),
            'nama_obat' => $this->request->getPost('nama_obat'),
            'jenis_obat' => $this->request->getPost('jenis_obat'),
            'kategori' => $this->request->getPost('kategori'),
            'satuan' => $this->request->getPost('satuan'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'stok' => $this->request->getPost('stok'),
            'stok_minimal' => $this->request->getPost('stok_minimal'),
            'tanggal_expired' => $this->request->getPost('tanggal_expired'),
            'produsen' => $this->request->getPost('produsen'),
            'lokasi_penyimpanan' => $this->request->getPost('lokasi_penyimpanan'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->request->getPost('id_obat')) {
            // Update existing medicine
            $db->table('obat')->update($data, ['id_obat' => $this->request->getPost('id_obat')]);
        } else {
            // Insert new medicine
            $db->table('obat')->insert($data);
        }

        return redirect()->to('/farmasi/data-master-obat')->with('success', 'Obat berhasil disimpan');
    }

    public function updateObat()
    {
        // Logic to update existing medicine
        $id_obat = $this->request->getPost('id_obat');
        $db = \Config\Database::connect();
        $data = [
            'kode_obat' => $this->request->getPost('kode_obat'),
            'nama_obat' => $this->request->getPost('nama_obat'),
            'jenis_obat' => $this->request->getPost('jenis_obat'),
            'kategori' => $this->request->getPost('kategori'),
            'satuan' => $this->request->getPost('satuan'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'stok' => $this->request->getPost('stok'),
            'stok_minimal' => $this->request->getPost('stok_minimal'),
            'tanggal_expired' => $this->request->getPost('tanggal_expired'),
            'produsen' => $this->request->getPost('produsen'),
            'lokasi_penyimpanan' => $this->request->getPost('lokasi_penyimpanan'),
            'status' => $this->request->getPost('status')
        ];

        if ($id_obat) {
            // Update existing medicine
            $db->table('obat')->update($data, ['id_obat' => $id_obat]);
        } else {
            // Insert new medicine
            $db->table('obat')->insert($data);
        }
        //message to show success
        $this->session->setFlashdata('success', 'Obat berhasil diperbarui');
        // Redirect back to the list of medicines
        return redirect()->to('/farmasi/data-master-obat')->with('success', 'Obat berhasil diperbarui');
    }
   

    //permintaan obat
    public function permintaanObat()
    {     
        $resepModel = new \App\Models\ResepModel();
        
        // Ambil semua permintaan obat dengan detail terkait
        $list_permintaan = $resepModel->getResepWithDetails();
        
        // Jika data kosong, gunakan data dummy untuk testing
        if (empty($list_permintaan)) {
            $list_permintaan = [
                [
                    'id' => 1,
                    'tanggal_resep' => date('Y-m-d H:i:s'),
                    'no_rm' => 'RM001',
                    'nama_pasien' => 'Ahmad Santoso',
                    'nama_dokter' => 'Dr. Budi Hartanto',
                    'diagnosis' => 'Hipertensi',
                    'nama_obat' => 'Amlodipine 5mg',
                    'jumlah' => 30,
                    'satuan' => 'tablet',
                    'instruksi' => '1x1 sehari setelah makan',
                    'status' => 'pending'
                ],
                [
                    'id' => 2,
                    'tanggal_resep' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'no_rm' => 'RM002',
                    'nama_pasien' => 'Siti Nurhaliza',
                    'nama_dokter' => 'Dr. Sarah Wijaya',
                    'diagnosis' => 'Diabetes Mellitus',
                    'nama_obat' => 'Metformin 500mg',
                    'jumlah' => 60,
                    'satuan' => 'tablet',
                    'instruksi' => '2x1 sebelum makan',
                    'status' => 'processing'
                ],
                [
                    'id' => 3,
                    'tanggal_resep' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'no_rm' => 'RM003',
                    'nama_pasien' => 'Andi Prasetyo',
                    'nama_dokter' => 'Dr. Eko Susanto',
                    'diagnosis' => 'Gastritis',
                    'nama_obat' => 'Omeprazole 20mg',
                    'jumlah' => 14,
                    'satuan' => 'kapsul',
                    'instruksi' => '1x1 pagi sebelum makan',
                    'status' => 'completed'
                ]
            ];
        }
        
        // Ubah nama kolom tanggal_resep menjadi tanggal_permintaan untuk konsistensi dengan view
        foreach ($list_permintaan as &$permintaan) {
            if (isset($permintaan['tanggal_resep'])) {
                $permintaan['tanggal_permintaan'] = $permintaan['tanggal_resep'];
            }
        }
        
        $data = [
            'title' => 'Permintaan Obat - SIMRS',
            'pageTitle' => 'Permintaan Obat',
            'list_permintaan' => $list_permintaan
        ];
        return view('farmasi/permintaan_obat', $data);
    }

    public function prosesPermintaan($id)
    {
        $resepModel = new \App\Models\ResepModel();
        $db = \Config\Database::connect();
        
        try {
            // Ambil data resep untuk pengecekan stok
            $resep = $resepModel->getResepWithDetails(['r.id' => $id]);
            if (empty($resep)) {
                throw new \Exception('Data permintaan obat tidak ditemukan');
            }
            
            $resepData = $resep[0];
            
            // Cek stok jika ada id_obat
            if (!empty($resepData['id_obat']) && !empty($resepData['jumlah'])) {
                $obat = $db->table('obat')
                    ->where('id_obat', $resepData['id_obat'])
                    ->get()
                    ->getRowArray();
                
                if ($obat && $obat['stok'] < $resepData['jumlah']) {
                    throw new \Exception('Stok obat ' . $resepData['nama_obat'] . ' tidak mencukupi. Stok tersedia: ' . $obat['stok'] . ', diminta: ' . $resepData['jumlah']);
                }
            }
            
            // Update status permintaan menjadi 'processing'
            $updated = $resepModel->updateStatus($id, 'processing', [
                'diproses_oleh' => $this->session->get('user_id')
            ]);
            
            if ($updated) {
                $this->session->setFlashdata('success', 'Permintaan obat berhasil diproses');
            } else {
                $this->session->setFlashdata('error', 'Gagal memproses permintaan obat');
            }
            
        } catch (\Exception $e) {
            $this->session->setFlashdata('error', 'Gagal memproses permintaan obat: ' . $e->getMessage());
        }
        
        return redirect()->to('/farmasi/permintaan-obat');
    }

    /**
     * Complete medicine request and update patient queue status
     * When all medicine requests for today are completed, patient status changes to 'Menunggu Kasir'
     */
    public function selesaiPermintaan($id)
    {
        $resepModel = new \App\Models\ResepModel();
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Ambil data resep untuk mendapatkan no_rm pasien
            $resep = $resepModel->getResepWithDetails(['r.id' => $id]);
            if (empty($resep)) {
                throw new \Exception('Data permintaan obat tidak ditemukan');
            }
            
            $no_rm = $resep[0]['no_rm'];
            log_message('info', 'Completing medicine request ID: ' . $id . ' for patient: ' . $no_rm);
            
            // Update status permintaan menjadi 'completed'
            $updated = $resepModel->updateStatus($id, 'completed', [
                'diselesaikan_oleh' => $this->session->get('user_id')
            ]);
            
            if (!$updated) {
                throw new \Exception('Gagal mengupdate status permintaan obat');
            }
            
            // IMPORTANT: Kurangi stok obat jika ada id_obat dan jumlah
            $resepData = $resep[0]; // Data permintaan obat
            if (!empty($resepData['id_obat']) && !empty($resepData['jumlah'])) {
                // Ambil data obat saat ini
                $currentObat = $db->table('obat')
                    ->where('id_obat', $resepData['id_obat'])
                    ->get()
                    ->getRowArray();
                
                if ($currentObat) {
                    $newStok = $currentObat['stok'] - $resepData['jumlah'];
                    
                    // Pastikan stok tidak negatif
                    if ($newStok < 0) {
                        throw new \Exception('Stok obat ' . $resepData['nama_obat'] . ' tidak mencukupi. Stok tersedia: ' . $currentObat['stok'] . ', diminta: ' . $resepData['jumlah']);
                    }
                    
                    // Update stok obat
                    $stokUpdated = $db->table('obat')
                        ->where('id_obat', $resepData['id_obat'])
                        ->update(['stok' => $newStok, 'updated_at' => date('Y-m-d H:i:s')]);
                    
                    if (!$stokUpdated) {
                        throw new \Exception('Gagal mengupdate stok obat');
                    }
                    
                    log_message('info', 'Updated stock for medicine ID: ' . $resepData['id_obat'] . ' from ' . $currentObat['stok'] . ' to ' . $newStok);
                }
            } else {
                // Untuk obat manual (tanpa id_obat), coba cari berdasarkan nama obat
                if (!empty($resepData['nama_obat']) && !empty($resepData['jumlah'])) {
                    $matchingObat = $db->table('obat')
                        ->like('nama_obat', $resepData['nama_obat'], 'both')
                        ->where('status', 'Aktif')
                        ->get()
                        ->getRowArray();
                    
                    if ($matchingObat) {
                        $newStok = $matchingObat['stok'] - $resepData['jumlah'];
                        
                        // Pastikan stok tidak negatif
                        if ($newStok < 0) {
                            log_message('warning', 'Insufficient stock for manual medicine: ' . $resepData['nama_obat'] . '. Available: ' . $matchingObat['stok'] . ', requested: ' . $resepData['jumlah']);
                        } else {
                            // Update stok obat
                            $stokUpdated = $db->table('obat')
                                ->where('id_obat', $matchingObat['id_obat'])
                                ->update(['stok' => $newStok, 'updated_at' => date('Y-m-d H:i:s')]);
                            
                            if ($stokUpdated) {
                                log_message('info', 'Updated stock for manual medicine: ' . $resepData['nama_obat'] . ' from ' . $matchingObat['stok'] . ' to ' . $newStok);
                            }
                        }
                    }
                }
            }
            
            // Cek apakah masih ada permintaan obat lain yang belum selesai untuk pasien ini
            $pendingResep = $resepModel->getResepWithDetails([
                'p.no_rekam_medis' => $no_rm,
                'r.status !=' => 'completed',
                'DATE(r.tanggal_resep)' => date('Y-m-d')
            ]);
            
            log_message('info', 'Remaining pending medicine requests for ' . $no_rm . ': ' . count($pendingResep));
            
            // Jika semua permintaan obat sudah selesai, update status antrian ke 'Menunggu Kasir'
            if (empty($pendingResep)) {
                // Ambil semua resep untuk pasien hari ini yang sudah completed
                $completedResep = $resepModel->getResepWithDetails([
                    'p.no_rekam_medis' => $no_rm,
                    'r.status' => 'completed',
                    'DATE(r.tanggal_resep)' => date('Y-m-d')
                ]);
                
                // Hitung total biaya obat
                $total_biaya_obat = 0;
                $detail_tagihan = [];
                
                foreach ($completedResep as $resepItem) {
                    $harga_satuan = $resepItem['harga_jual'] ?? 0;
                    $jumlah = $resepItem['jumlah'] ?? 1;
                    $subtotal = $harga_satuan * $jumlah;
                    $total_biaya_obat += $subtotal;
                    
                    $detail_tagihan[] = [
                        'nama_obat' => $resepItem['nama_obat'],
                        'jumlah' => $jumlah,
                        'satuan' => $resepItem['satuan'] ?? 'pcs',
                        'harga_satuan' => $harga_satuan,
                        'subtotal' => $subtotal
                    ];
                }
                
                // Biaya standar
                $biaya_administrasi = 50000;
                $biaya_nurs_station = 100000;
                $biaya_dokter = 250000;
                $total_biaya = $total_biaya_obat + $biaya_administrasi + $biaya_nurs_station + $biaya_dokter;
                
                // Tambahkan biaya standar ke detail tagihan
                $detail_tagihan[] = ['nama_item' => 'Biaya Administrasi', 'harga' => $biaya_administrasi];
                $detail_tagihan[] = ['nama_item' => 'Biaya Nurs Station', 'harga' => $biaya_nurs_station];
                $detail_tagihan[] = ['nama_item' => 'Biaya Dokter', 'harga' => $biaya_dokter];
                
                // Cek apakah sudah ada tagihan untuk pasien ini hari ini
                $existingTagihan = $db->table('tagihan')
                    ->where('no_rm', $no_rm)
                    ->where('DATE(tanggal_tagihan)', date('Y-m-d'))
                    ->where('status', 'pending')
                    ->get()->getRowArray();
                
                if (!$existingTagihan) {
                    // Buat tagihan baru
                    $tagihanModel = new \App\Models\TagihanModel();
                    $tagihanData = [
                        'no_rm' => $no_rm,
                        'no_resep' => $id, // ID resep terakhir
                        'total_biaya' => $total_biaya,
                        'detail_tagihan' => json_encode($detail_tagihan),
                        'status' => 'pending',
                        'tanggal_tagihan' => date('Y-m-d H:i:s')
                    ];
                    
                    $tagihanCreated = $tagihanModel->insert($tagihanData);
                    if (!$tagihanCreated) {
                        throw new \Exception('Gagal membuat tagihan untuk pasien');
                    }
                    
                    log_message('info', 'Created billing for patient ' . $no_rm . ' with total: ' . $total_biaya);
                }
                
                // Update status antrian pasien menjadi 'Menunggu Kasir'
                // Cari antrian pasien hari ini yang statusnya masih 'Menunggu Farmasi'
                $antrianUpdated = $db->table('antrian')
                    ->where('no_rm', $no_rm)
                    ->where('DATE(created_at)', date('Y-m-d'))
                    ->where('status', 'Menunggu Farmasi')
                    ->update(['status' => 'Menunggu Kasir']);
                
                // Juga update di antrian_poli jika ada
                $db->table('antrian_poli')
                    ->where('no_rm', $no_rm)
                    ->where('DATE(created_at)', date('Y-m-d'))
                    ->where('status', 'Menunggu Farmasi')
                    ->update(['status' => 'Menunggu Kasir']);
                
                log_message('info', 'Updated patient queue status to Menunggu Kasir for ' . $no_rm);
                $successMessage = 'Permintaan obat telah selesai diproses. Tagihan telah dibuat. Status pasien diubah ke Menunggu Kasir.';
            } else {
                $successMessage = 'Permintaan obat telah selesai diproses. Masih ada ' . count($pendingResep) . ' permintaan obat lain yang belum selesai.';
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan perubahan ke database');
            }
            
            $this->session->setFlashdata('success', $successMessage);
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Failed to complete medicine request: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal menyelesaikan permintaan obat: ' . $e->getMessage());
        }
        
        return redirect()->to('/farmasi/permintaan-obat');
    }

    public function batalPermintaan($id)
    {
        $resepModel = new \App\Models\ResepModel();
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Ambil data resep sebelum dibatalkan
            $resep = $resepModel->getResepWithDetails(['r.id' => $id]);
            if (empty($resep)) {
                throw new \Exception('Data permintaan obat tidak ditemukan');
            }
            
            $resepData = $resep[0];
            $statusSebelumnya = $resepData['status'];
            
            // Jika status sebelumnya 'completed', kembalikan stok obat
            if ($statusSebelumnya === 'completed') {
                if (!empty($resepData['id_obat']) && !empty($resepData['jumlah'])) {
                    // Kembalikan stok untuk obat yang ada id_obat-nya
                    $currentObat = $db->table('obat')
                        ->where('id_obat', $resepData['id_obat'])
                        ->get()
                        ->getRowArray();
                    
                    if ($currentObat) {
                        $newStok = $currentObat['stok'] + $resepData['jumlah'];
                        
                        $stokUpdated = $db->table('obat')
                            ->where('id_obat', $resepData['id_obat'])
                            ->update(['stok' => $newStok, 'updated_at' => date('Y-m-d H:i:s')]);
                        
                        if ($stokUpdated) {
                            log_message('info', 'Returned stock for medicine ID: ' . $resepData['id_obat'] . ' from ' . $currentObat['stok'] . ' to ' . $newStok);
                        }
                    }
                } else {
                    // Kembalikan stok untuk obat manual berdasarkan nama
                    if (!empty($resepData['nama_obat']) && !empty($resepData['jumlah'])) {
                        $matchingObat = $db->table('obat')
                            ->like('nama_obat', $resepData['nama_obat'], 'both')
                            ->where('status', 'Aktif')
                            ->get()
                            ->getRowArray();
                        
                        if ($matchingObat) {
                            $newStok = $matchingObat['stok'] + $resepData['jumlah'];
                            
                            $stokUpdated = $db->table('obat')
                                ->where('id_obat', $matchingObat['id_obat'])
                                ->update(['stok' => $newStok, 'updated_at' => date('Y-m-d H:i:s')]);
                            
                            if ($stokUpdated) {
                                log_message('info', 'Returned stock for manual medicine: ' . $resepData['nama_obat'] . ' from ' . $matchingObat['stok'] . ' to ' . $newStok);
                            }
                        }
                    }
                }
            }
            
            // Kembalikan status ke 'pending'
            $updated = $resepModel->updateStatus($id, 'pending', [
                'tanggal_diproses' => null,
                'tanggal_selesai' => null,
                'diproses_oleh' => null,
                'diselesaikan_oleh' => null
            ]);
            
            if (!$updated) {
                throw new \Exception('Gagal mengupdate status permintaan obat');
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan perubahan ke database');
            }
            
            $message = $statusSebelumnya === 'completed' 
                ? 'Permintaan obat dibatalkan dan stok dikembalikan' 
                : 'Proses permintaan obat dibatalkan';
                
            $this->session->setFlashdata('warning', $message);
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Failed to cancel medicine request: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal membatalkan permintaan obat: ' . $e->getMessage());
        }
        
        return redirect()->to('/farmasi/permintaan-obat');
    }

    public function detailPermintaan($id)
    {
        $resepModel = new \App\Models\ResepModel();
        
        // Ambil detail permintaan obat
        $permintaan = $resepModel->getResepWithDetails(['r.id' => $id]);
        
        if (empty($permintaan)) {
            $this->session->setFlashdata('error', 'Permintaan obat tidak ditemukan');
            return redirect()->to('/farmasi/permintaan-obat');
        }
        
        $data = [
            'title' => 'Detail Permintaan Obat - SIMRS',
            'pageTitle' => 'Detail Permintaan Obat',
            'permintaan' => $permintaan[0] // Ambil data pertama karena hasil adalah array
        ];
        
        return view('farmasi/detail_permintaan_obat', $data);
    }

    public function laporan()
    {
        // Logic to generate and display reports
        $data = [
            'title' => 'Laporan Farmasi - SIMRS',
            'pageTitle' => 'Laporan Farmasi'
        ];
        
        // Load the view for reports
        return view('farmasi/laporan_farmasi', $data);
    }

    public function riwayatPermintaan()
    {
        // Ambil data riwayat dari tabel resep yang sudah diproses farmasi
        $resepModel = new \App\Models\ResepModel();

        // Get filter parameters from request
        $tanggal_mulai = $this->request->getGet('tanggal_mulai');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        $status = $this->request->getGet('status');

        // Ambil semua riwayat resep yang sudah diproses
        $builder = $resepModel->builder();
        $builder->select('resep.*, resep.id as id, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis as no_rm, users.nama_lengkap as nama_dokter, obat.harga_jual')
            ->join('pasien', 'pasien.id = resep.id_pasien', 'left')
            ->join('users', 'users.id = resep.id_dokter', 'left')
            ->join('obat', 'obat.id_obat = resep.id_obat', 'left')
            ->whereIn('resep.status', ['processing', 'completed']) // Hanya yang sudah diproses
            ->orderBy('resep.tanggal_resep', 'desc');

        if (!empty($tanggal_mulai)) {
            $builder->where('DATE(resep.tanggal_resep) >=', $tanggal_mulai);
        }
        if (!empty($tanggal_akhir)) {
            $builder->where('DATE(resep.tanggal_resep) <=', $tanggal_akhir);
        }
        if (!empty($status)) {
            // Hanya filter status farmasi yang valid
            if (in_array($status, ['pending', 'processing', 'completed'])) {
                $builder->where('resep.status', $status);
            }
        }

        $riwayat_permintaan = $builder->get()->getResultArray();

        // Konversi data resep ke format yang diharapkan view
        foreach ($riwayat_permintaan as &$resep) {
            // Hitung total biaya berdasarkan harga obat * jumlah
            $harga_satuan = $resep['harga_jual'] ?? 0;
            $jumlah = $resep['jumlah'] ?? 1;
            $resep['total_biaya'] = $harga_satuan * $jumlah;
        }
       
        $data = [
            'title' => 'Riwayat Permintaan Obat - SIMRS',
            'pageTitle' => 'Riwayat Permintaan Obat',
            'riwayat_permintaan' => $riwayat_permintaan,
            'filters' => [
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_akhir' => $tanggal_akhir,
                'status' => $status
            ]
        ];

        return view('farmasi/riwayat_permintaan', $data);
    }

    /**
     * Print Invoice for Tagihan (Billing/Permintaan Obat)
     */
    public function printInvoice($id)
    {
        $tagihanModel = new \App\Models\TagihanModel();
        $db = \Config\Database::connect();
        
        // Ambil data tagihan utama
        $tagihan = $tagihanModel->builder()
            ->select('tagihan.*, CAST(pasien.nama_lengkap AS CHAR) as nama_pasien, CAST(pasien.no_rekam_medis AS CHAR) as no_rm')
            ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(tagihan.no_rm AS CHAR)', 'left')
            ->where('tagihan.id_tagihan', $id)
            ->get()->getRowArray();
            
        if (empty($tagihan)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tagihan tidak ditemukan');
        }
        
        // Ambil detail resep untuk tagihan ini
        $resepModel = new \App\Models\ResepModel();
        
        // Jika ada no_resep di tagihan, coba cari berdasarkan id resep
        $permintaan_list = [];
        $nama_dokter = '-'; // Default nama dokter
        
        if (!empty($tagihan['no_resep'])) {
            $permintaan_list = $resepModel->builder()
                ->select('resep.*, obat.nama_obat, obat.harga_jual, users.nama_lengkap as nama_dokter')
                ->join('obat', 'obat.id_obat = resep.id_obat', 'left')
                ->join('users', 'users.id = resep.id_dokter', 'left')
                ->where('resep.id', $tagihan['no_resep'])
                ->get()->getResultArray();
                
            // Ambil nama dokter dari resep pertama jika ada
            if (!empty($permintaan_list) && isset($permintaan_list[0]['nama_dokter'])) {
                $nama_dokter = $permintaan_list[0]['nama_dokter'];
            }
        }
        
        // Jika masih kosong, coba cari berdasarkan no_rm pasien di hari yang sama
        if (empty($permintaan_list) && !empty($tagihan['no_rm'])) {
            // Cari pemeriksaan SOAP di hari yang sama untuk ambil nama dokter
            $pemeriksaanSoap = $db->table('pemeriksaan_soap')
                ->select('pemeriksaan_soap.*, users.nama_lengkap as nama_dokter')
                ->join('users', 'users.id = pemeriksaan_soap.id_dokter', 'left')
                ->where('pemeriksaan_soap.no_rm', $tagihan['no_rm'])
                ->where('DATE(pemeriksaan_soap.created_at)', date('Y-m-d', strtotime($tagihan['tanggal_tagihan'])))
                ->orderBy('pemeriksaan_soap.created_at', 'desc')
                ->get()->getFirstRow('array');
                
            if ($pemeriksaanSoap && !empty($pemeriksaanSoap['nama_dokter'])) {
                $nama_dokter = $pemeriksaanSoap['nama_dokter'];
            }
        }
        
        // Jika masih kosong, buat dummy data untuk testing berdasarkan tagihan
        if (empty($permintaan_list)) {
            $permintaan_list = [
                [
                    'nama_obat' => 'Obat Resep',
                    'jumlah' => 1,
                    'satuan' => 'strip',
                    'instruksi' => 'Sesuai petunjuk dokter',
                    'harga_jual' => $tagihan['total_biaya'] ?? 0
                ]
            ];
        }

        $data = [
            'title' => 'Cetak Invoice Permintaan Obat',
            'permintaan_list' => $permintaan_list,
            'pasien' => [
                'nama_pasien' => $tagihan['nama_pasien'] ?? '-',
                'no_rm' => $tagihan['no_rm'] ?? '-',
                'nama_dokter' => $nama_dokter, // Ambil dari resep atau pemeriksaan SOAP
            ]
        ];
        return view('farmasi/print_invoice', $data);
    }

    /**
     * Detail tagihan/permintaan obat untuk modal
     */
    public function detailTagihan($id)
    {
        $resepModel = new \App\Models\ResepModel();
        $db = \Config\Database::connect();
        
        // Ambil detail resep berdasarkan ID
        $resep = $resepModel->builder()
            ->select('resep.*, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis as no_rm, pasien.jenis_kelamin, pasien.tanggal_lahir, users.nama_lengkap as nama_dokter, obat.nama_obat as nama_obat_db, obat.harga_jual')
            ->join('pasien', 'pasien.id = resep.id_pasien', 'left')
            ->join('users', 'users.id = resep.id_dokter', 'left')
            ->join('obat', 'obat.id_obat = resep.id_obat', 'left')
            ->where('resep.id', $id)
            ->get()->getRowArray();
            
        if (empty($resep)) {
            return $this->response->setStatusCode(404)->setBody('<div class="alert alert-danger">Data permintaan tidak ditemukan</div>');
        }
        
        // Ambil semua resep untuk pasien yang sama di tanggal yang sama
        $semua_resep = $resepModel->builder()
            ->select('resep.*, obat.nama_obat as nama_obat_db, obat.harga_jual, obat.satuan as satuan_db')
            ->join('obat', 'obat.id_obat = resep.id_obat', 'left')
            ->where('resep.id_pasien', $resep['id_pasien'])
            ->where('DATE(resep.tanggal_resep)', date('Y-m-d', strtotime($resep['tanggal_resep'])))
            ->orderBy('resep.tanggal_resep', 'asc')
            ->get()->getResultArray();
        
        // Hitung total biaya
        $total_biaya = 0;
        foreach ($semua_resep as &$item) {
            // Pilih nama obat (prioritas obat dari DB, fallback ke manual)
            $item['nama_obat_final'] = !empty($item['nama_obat_db']) ? $item['nama_obat_db'] : $item['nama_obat'];
            $item['satuan_final'] = !empty($item['satuan_db']) ? $item['satuan_db'] : ($item['satuan'] ?? 'pcs');
            
            // Hitung subtotal
            $harga = $item['harga_jual'] ?? 0;
            $jumlah = $item['jumlah'] ?? 1;
            $item['subtotal'] = $harga * $jumlah;
            $total_biaya += $item['subtotal'];
        }
        
        // Hitung umur pasien
        $umur = '-';
        if (!empty($resep['tanggal_lahir'])) {
            $lahir = new \DateTime($resep['tanggal_lahir']);
            $sekarang = new \DateTime();
            $umur = $sekarang->diff($lahir)->y . ' tahun';
        }
        
        $data = [
            'resep' => $resep,
            'semua_resep' => $semua_resep,
            'total_biaya' => $total_biaya,
            'umur' => $umur
        ];
        
        return view('farmasi/detail_permintaan_obat', $data);
    }

    /**
     * Print Resep for medicine prescription
     */
    public function printResep($id)
    {
        $resepModel = new \App\Models\ResepModel();
        
        // Ambil detail resep berdasarkan ID
        $resep = $resepModel->builder()
            ->select('resep.*, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis as no_rm, pasien.jenis_kelamin, pasien.tanggal_lahir, users.nama_lengkap as nama_dokter, obat.nama_obat as nama_obat_db, obat.harga_jual')
            ->join('pasien', 'pasien.id = resep.id_pasien', 'left')
            ->join('users', 'users.id = resep.id_dokter', 'left')
            ->join('obat', 'obat.id_obat = resep.id_obat', 'left')
            ->where('resep.id', $id)
            ->get()->getRowArray();
            
        if (empty($resep)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Resep tidak ditemukan');
        }
        
        // Ambil semua resep untuk pasien yang sama di tanggal yang sama
        $semua_resep = $resepModel->builder()
            ->select('resep.*, obat.nama_obat as nama_obat_db, obat.harga_jual, obat.satuan as satuan_db')
            ->join('obat', 'obat.id_obat = resep.id_obat', 'left')
            ->where('resep.id_pasien', $resep['id_pasien'])
            ->where('DATE(resep.tanggal_resep)', date('Y-m-d', strtotime($resep['tanggal_resep'])))
            ->orderBy('resep.tanggal_resep', 'asc')
            ->get()->getResultArray();
        
        // Hitung total biaya
        $total_biaya = 0;
        foreach ($semua_resep as &$item) {
            // Pilih nama obat (prioritas obat dari DB, fallback ke manual)
            $item['nama_obat_final'] = !empty($item['nama_obat_db']) ? $item['nama_obat_db'] : $item['nama_obat'];
            $item['satuan_final'] = !empty($item['satuan_db']) ? $item['satuan_db'] : ($item['satuan'] ?? 'pcs');
            
            // Hitung subtotal
            $harga = $item['harga_jual'] ?? 0;
            $jumlah = $item['jumlah'] ?? 1;
            $item['subtotal'] = $harga * $jumlah;
            $total_biaya += $item['subtotal'];
        }
        
        // Hitung umur pasien
        $umur = '-';
        if (!empty($resep['tanggal_lahir'])) {
            $lahir = new \DateTime($resep['tanggal_lahir']);
            $sekarang = new \DateTime();
            $umur = $sekarang->diff($lahir)->y . ' tahun';
        }

        $data = [
            'title' => 'Cetak Resep Obat',
            'resep' => $resep,
            'semua_resep' => $semua_resep,
            'total_biaya' => $total_biaya,
            'umur' => $umur
        ];
        
        return view('farmasi/print_resep', $data);
    }

    
}
