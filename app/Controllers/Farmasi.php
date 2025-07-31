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
   
}
