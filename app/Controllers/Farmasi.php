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
        
        // Update status permintaan menjadi 'processing'
        $updated = $resepModel->updateStatus($id, 'processing', [
            'diproses_oleh' => $this->session->get('user_id')
        ]);
        
        if ($updated) {
            $this->session->setFlashdata('success', 'Permintaan obat berhasil diproses');
        } else {
            $this->session->setFlashdata('error', 'Gagal memproses permintaan obat');
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
            
            // Cek apakah masih ada permintaan obat lain yang belum selesai untuk pasien ini
            $pendingResep = $resepModel->getResepWithDetails([
                'p.no_rekam_medis' => $no_rm,
                'r.status !=' => 'completed',
                'DATE(r.tanggal_resep)' => date('Y-m-d')
            ]);
            
            log_message('info', 'Remaining pending medicine requests for ' . $no_rm . ': ' . count($pendingResep));
            
            // Jika semua permintaan obat sudah selesai, update status antrian ke 'Menunggu Kasir'
            if (empty($pendingResep)) {
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
                $successMessage = 'Permintaan obat telah selesai diproses. Status pasien diubah ke Menunggu Kasir.';
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
        
        // Kembalikan status ke 'pending'
        $updated = $resepModel->updateStatus($id, 'pending', [
            'tanggal_diproses' => null,
            'diproses_oleh' => null
        ]);
        
        if ($updated) {
            $this->session->setFlashdata('warning', 'Proses permintaan obat dibatalkan');
        } else {
            $this->session->setFlashdata('error', 'Gagal membatalkan permintaan obat');
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



}
