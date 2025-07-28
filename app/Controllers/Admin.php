<?php

namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        
        // Check if user is logged in and has admin role
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Admin - SIMRS',
            'pageTitle' => 'Dashboard Admin',
            'stats' => [
                'total_users' => $this->userModel->countAll(),
                'active_users' => $this->userModel->where('status_aktif', 1)->countAllResults(),
                'total_doctors' => $this->userModel->where('role', 'dokter')->countAllResults(),
                'total_nurses' => $this->userModel->where('role', 'perawat')->countAllResults(),
                'total_pasien' => $this->db->table('pasien')->countAllResults(),
                'total_antrian' => $this->db->table('antrian')->countAllResults()
            ]
        ];

        return view('admin/dashboard', $data);
    }

    public function users()
    {
        $users = $this->userModel->findAll();
        
        // Ambil daftar spesialisasi unik untuk filter
        $specializations = $this->userModel->select('spesialisasi')
                                          ->where('role', 'dokter')
                                          ->where('spesialisasi IS NOT NULL')
                                          ->distinct()
                                          ->findAll();
        
        $data = [
            'title' => 'Manajemen User - SIMRS',
            'pageTitle' => 'Manajemen User',
            'users' => $users,
            'specializations' => array_column($specializations, 'spesialisasi')
        ];

        return view('admin/users', $data);
    }

    public function addUser()
    {
        $data = [
            'title' => 'Tambah User - SIMRS',
            'pageTitle' => 'Tambah User Baru',
            'roles' => ['admin', 'farmasi', 'kasir', 'dokter', 'perawat', 'admisi', 'manajemen'],
            'specializations' => [
                'Dokter Umum',
                'Dokter Anak', 
                'Dokter Jantung', 
                'Dokter Mata', 
                'Dokter Kandungan', 
                'Dokter Bedah', 
                'Dokter Kulit dan Kelamin', 
                'Dokter Gigi', 
                'Dokter Ortopedi', 
                'Dokter Saraf', 
                'Dokter Penyakit Dalam', 
                'Dokter THT', 
                'Dokter Radiologi'
            ]
        ];

        return view('admin/add_user', $data);
    }

    public function saveUser()
    {
        $validation = \Config\Services::validation();
        
        // Base validation rules
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,farmasi,kasir,dokter,perawat,admisi,manajemen]'
        ];
        
        // Add specialization validation if role is dokter
        $role = $this->request->getPost('role');
        if ($role === 'dokter') {
            $rules['spesialisasi'] = 'required|in_list[Dokter Umum,Dokter Anak,Dokter Jantung,Dokter Mata,Dokter Kandungan,Dokter Bedah,Dokter Kulit dan Kelamin,Dokter Gigi,Dokter Ortopedi,Dokter Saraf,Dokter Penyakit Dalam,Dokter THT,Dokter Radiologi]';
        }
        
        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('errors', $validation->getErrors());
            return redirect()->back()->withInput();
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'role' => $this->request->getPost('role'),
            'status_aktif' => $this->request->getPost('status_aktif') ? 1 : 0,
            'telepon' => $this->request->getPost('telepon'),
            'alamat' => $this->request->getPost('alamat')
        ];
        
        // Add specialization if role is dokter
        if ($role === 'dokter') {
            $userData['spesialisasi'] = $this->request->getPost('spesialisasi');
        }

        if ($this->userModel->save($userData)) {
            $this->session->setFlashdata('success', 'User berhasil ditambahkan');
        } else {
            $this->session->setFlashdata('error', 'Gagal menambahkan user');
        }

        return redirect()->to('/admin/users');
    }

    public function editUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title' => 'Edit User - SIMRS',
            'pageTitle' => 'Edit User',
            'user' => $user,
            'roles' => ['admin', 'farmasi', 'kasir', 'dokter', 'perawat', 'admisi', 'manajemen'],
            'specializations' => [
                'Dokter Umum',
                'Dokter Anak', 
                'Dokter Jantung', 
                'Dokter Mata', 
                'Dokter Kandungan', 
                'Dokter Bedah', 
                'Dokter Kulit dan Kelamin', 
                'Dokter Gigi', 
                'Dokter Ortopedi', 
                'Dokter Saraf', 
                'Dokter Penyakit Dalam', 
                'Dokter THT', 
                'Dokter Radiologi'
            ]
        ];

        return view('admin/edit_user', $data);
    }

    public function updateUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,farmasi,kasir,dokter,perawat,admisi,manajemen]'
        ];

        // Add password validation only if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }
        
        // Add specialization validation if role is dokter
        $role = $this->request->getPost('role');
        if ($role === 'dokter') {
            $rules['spesialisasi'] = 'required|in_list[Dokter Umum,Dokter Anak,Dokter Jantung,Dokter Mata,Dokter Kandungan,Dokter Bedah,Dokter Kulit dan Kelamin,Dokter Gigi,Dokter Ortopedi,Dokter Saraf,Dokter Penyakit Dalam,Dokter THT,Dokter Radiologi]';
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('errors', $validation->getErrors());
            return redirect()->back()->withInput();
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'role' => $this->request->getPost('role'),
            'status_aktif' => $this->request->getPost('status_aktif') ? 1 : 0,
            'telepon' => $this->request->getPost('telepon'),
            'alamat' => $this->request->getPost('alamat')
        ];

        // Update password only if provided
        if ($this->request->getPost('password')) {
            $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        // Add specialization if role is dokter, or clear it if not
        if ($role === 'dokter') {
            $userData['spesialisasi'] = $this->request->getPost('spesialisasi');
        } else {
            $userData['spesialisasi'] = null;
        }

        if ($this->userModel->update($id, $userData)) {
            $this->session->setFlashdata('success', 'User berhasil diupdate');
        } else {
            $this->session->setFlashdata('error', 'Gagal mengupdate user');
        }

        return redirect()->to('/admin/users');
    }

    public function deleteUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $this->session->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/admin/users');
        }

        // Prevent deleting current admin user
        if ($id == $this->session->get('user_id')) {
            $this->session->setFlashdata('error', 'Tidak dapat menghapus akun sendiri');
            return redirect()->to('/admin/users');
        }

        if ($this->userModel->delete($id)) {
            $this->session->setFlashdata('success', 'User berhasil dihapus');
        } else {
            $this->session->setFlashdata('error', 'Gagal menghapus user');
        }

        return redirect()->to('/admin/users');
    }

    public function toggleUserStatus($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        // Prevent deactivating current admin user
        if ($id == $this->session->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak dapat menonaktifkan akun sendiri']);
        }

        $newStatus = $user['status_aktif'] ? 0 : 1;
        
        if ($this->userModel->update($id, ['status_aktif' => $newStatus])) {
            $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            return $this->response->setJSON(['success' => true, 'message' => "User berhasil {$statusText}", 'new_status' => $newStatus]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status user']);
        }
    }

    

    public function database()
    {
        $data = [
            'title' => 'Database Management - SIMRS',
            'pageTitle' => 'Database Management'
        ];

        return view('admin/database', $data);
    }

    // Admin access to other role dashboards
    public function farmasi()
    {
        // Redirect to farmasi dashboard with admin privileges
        return redirect()->to('/farmasi');
    }

    public function kasir()
    {
        // Redirect to kasir dashboard with admin privileges
        return redirect()->to('/kasir');
    }

    public function dokter()
    {
        // Redirect to dokter dashboard with admin privileges
        return redirect()->to('/dokter');
    }

    public function perawat()
    {
        // Redirect to perawat dashboard with admin privileges
        return redirect()->to('/perawat');
    }

    public function admisi()
    {
        // Redirect to admisi dashboard with admin privileges
        return redirect()->to('/admisi');
    }

    public function manajemen()
    {
        // Redirect to manajemen dashboard with admin privileges
        return redirect()->to('/manajemen');
    }

    public function deletePasien($id)
    {
        // Prevent deleting current admin user
        if ($id == $this->session->get('user_id')) {
            $this->session->setFlashdata('error', 'Tidak dapat menghapus akun sendiri');
            return redirect()->to('/admisi/datapasien');
        }

        if ($this->userModel->delete($id)) {
            $this->session->setFlashdata('success', 'User berhasil dihapus');
        } else {
            $this->session->setFlashdata('error', 'Gagal menghapus user');
        }

        return redirect()->to('/admisi/datapasien');
    }
    

    // Tampilkan daftar dokter dan jadwal praktik
    public function datadokter()
    {
        $db = \Config\Database::connect();
        // Ambil semua dokter
        $dokterList = $this->userModel->where('role', 'dokter')->findAll();
        // Ambil jadwal praktik dokter
        $jadwalList = [];
        if (!empty($dokterList)) {
            $dokterIds = array_column($dokterList, 'id');
            // Join ke poliklinik untuk ambil nama poliklinik
            $jadwalRows = $db->table('dokter_jadwal')
                ->select('dokter_jadwal.*, poliklinik.nama AS nama_poli')
                ->whereIn('dokter_jadwal.dokter_id', $dokterIds)
                ->join('poliklinik', 'poliklinik.id = dokter_jadwal.poliklinik_id')
                ->orderBy('hari', 'asc')
                ->orderBy('jam_mulai', 'asc')
                ->get()->getResultArray();
            foreach ($jadwalRows as $jadwal) {
                $jadwalList[$jadwal['dokter_id']][] = $jadwal;
            }
        }
        $data = [
            'title' => 'Daftar Dokter & Jadwal Praktik',
            'pageTitle' => 'Daftar Dokter',
            'dokterList' => $dokterList,
            'jadwalList' => $jadwalList
        ];
        return view('admin/datadokter', $data);
    }

    // Tampilkan form tambah jadwal praktik dokter
    public function tambahJadwalDokter()
    {
        $dokterList = $this->userModel->where('role', 'dokter')->findAll();
        $db = \Config\Database::connect();
        $poliklinikList = $db->table('poliklinik')->get()->getResultArray();
        $data = [
            'title' => 'Tambah Jadwal Praktik Dokter',
            'pageTitle' => 'Tambah Jadwal Dokter',
            'dokterList' => $dokterList,
            'poliklinikList' => $poliklinikList
        ];
        return view('admin/tambah_jadwal_dokter', $data);
    }

    // Proses simpan jadwal praktik dokter
    public function saveJadwalDokter()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'dokter_id' => 'required|is_natural_no_zero',
            'hari' => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu]',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ];
        $validation->setRules($rules);
        if (!$validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('errors', $validation->getErrors());
            return redirect()->back()->withInput();
        }
        $db = \Config\Database::connect();
        $data = [
            'dokter_id' => $this->request->getPost('dokter_id'),
            'poliklinik_id' => $this->request->getPost('poliklinik_id'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];
        if ($db->table('dokter_jadwal')->insert($data)) {
            $this->session->setFlashdata('success', 'Jadwal praktik dokter berhasil ditambahkan');
        } else {
            $this->session->setFlashdata('error', 'Gagal menambahkan jadwal praktik dokter');
        }
        return redirect()->to('/admin/datadokter');
    }

    // Tampilkan form edit jadwal praktik dokter
    public function editJadwalDokter()
    {
        $db = \Config\Database::connect();
        $dokterId = $this->request->getGet('dokter_id');
        $hari = $this->request->getGet('hari');
        $poliId = $this->request->getGet('poli');
        $jadwalRow = $db->table('dokter_jadwal')
            ->where('dokter_id', $dokterId)
            ->where('hari', $hari)
            ->where('poliklinik_id', $poliId)
            ->get()->getFirstRow('array');

        $errorMsg = '';
        if (!$jadwalRow) {
            $errorMsg = 'Jadwal tidak ditemukan. Pastikan parameter dokter, hari, dan poliklinik sesuai.';
        }

        // Ambil nama dokter
        $dokter = $db->table('users')->where('id', $dokterId)->get()->getFirstRow('array');
        $namaDokter = $dokter ? $dokter['nama_lengkap'] : '';

        // Ambil nama poliklinik
        $poliklinik = $db->table('poliklinik')->where('id', $poliId)->get()->getFirstRow('array');
        $namaPoli = $poliklinik ? $poliklinik['nama'] : '';

        // Ambil daftar poliklinik untuk dropdown
        $poliklinikList = $db->table('poliklinik')->get()->getResultArray();

        $data = [
            'title' => 'Edit Jadwal Praktik Dokter',
            'jadwal' => $jadwalRow,
            'namaDokter' => $namaDokter,
            'namaPoli' => $namaPoli,
            'poliklinikList' => $poliklinikList,
            'errorMsg' => $errorMsg
        ];
        return view('admin/edit_jadwal_dokter', $data);
    }

    // Proses update jadwal praktik dokter
    public function updateJadwalDokter()
    {
        $db = \Config\Database::connect();
        $dokterId = $this->request->getPost('dokter_id');
        $hari = $this->request->getPost('hari');
        $poliId = $this->request->getPost('poli'); // pastikan input form 'poli' adalah poliklinik_id
        $jamMulai = $this->request->getPost('jam_mulai');
        $jamSelesai = $this->request->getPost('jam_selesai');
        $keterangan = $this->request->getPost('keterangan');
        $db->table('dokter_jadwal')
            ->where('dokter_id', $dokterId)
            ->where('hari', $hari)
            ->where('poliklinik_id', $poliId)
            ->update([
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'keterangan' => $keterangan
            ]);
        return redirect()->to(base_url('admin/datadokter'))->with('success', 'Jadwal berhasil diupdate');
    }

    // Proses hapus jadwal praktik dokter
    public function hapusJadwalDokter()
    {
        $db = \Config\Database::connect();
        $jadwalId = $this->request->getGet('id');
        if ($jadwalId) {
            $db->table('dokter_jadwal')->where('id', $jadwalId)->delete();
            return redirect()->to(base_url('admin/datadokter'))->with('success', 'Jadwal berhasil dihapus');
        } else {
            return redirect()->to(base_url('admin/datadokter'))->with('error', 'ID jadwal tidak ditemukan');
        }
    }
}
