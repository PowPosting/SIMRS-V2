<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PemeriksaanAwalModel;

class Perawat extends ResourceController
{
    use ResponseTrait;

    protected $db;
    protected $session;
    protected $request;
    protected $response;
    protected $pemeriksaanModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->request = service('request');
        $this->response = service('response');
        $this->pemeriksaanModel = new PemeriksaanAwalModel();
        helper(['form', 'url']);
        
        // Check if user is logged in and has perawat role OR admin role
        $userRole = $this->session->get('role');
        if (!$this->session->get('isLoggedIn') || ($userRole !== 'perawat' && $userRole !== 'admin')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Antrian Pendaftaran - SIMRS',
            'pageTitle' => 'Antrian Pendaftaran'
        ];
        return view('perawat/antrian_pendaftaran_new', $data);
    }
    // Tampilkan halaman antrian pendaftaran
    public function antrianPendaftaran()
    {
        $data = [
            'title' => 'Antrian Pendaftaran - SIMRS',
            'pageTitle' => 'Antrian Pendaftaran'
        ];
        return view('perawat/antrian_pendaftaran_new', $data);
    }

    public function getAntrianPendaftaran()
    {
        try {
            // Gunakan raw query untuk menghindari masalah collation
            $sql = "SELECT 
                    a.id,
                    a.no_antrian,
                    a.no_rm,
                    p.nama_lengkap as nama_pasien,
                    pol.nama as poli_tujuan,
                    a.status
                FROM antrian a
                LEFT JOIN pasien p ON BINARY p.no_rekam_medis = BINARY a.no_rm
                LEFT JOIN poliklinik pol ON pol.id = a.id_poli
                WHERE a.status = 'Menunggu Pemeriksaan'
                  AND DATE(a.created_at) = CURDATE()
                ORDER BY a.created_at ASC";
            
            $query = $this->db->query($sql);
            $data = $query->getResultArray();
            
            // Log current builder state
            log_message('debug', '[Perawat::getAntrianPendaftaran] Builder state before execute');
            
            // Debug logs
            log_message('debug', '[Perawat::getAntrianPendaftaran] SQL: ' . $sql);
            log_message('debug', '[Perawat::getAntrianPendaftaran] Data count: ' . count($data));
            log_message('debug', '[Perawat::getAntrianPendaftaran] Data: ' . json_encode($data));
            
            $response = [
                'data' => $data,
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data),
                'debug' => [
                    'sql' => $sql,
                    'error' => null
                ]
            ];
            
            log_message('debug', 'Response: ' . json_encode($response));
            
            return $this->response->setJSON($response);
        } catch (\Exception $e) {
            log_message('error', '[Perawat::getAntrianPendaftaran] Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function simpanPemeriksaan()
    {
        try {
            $rules = [
                'id_antrian' => 'required|numeric',
                'tekanan_darah' => 'required',
                'suhu_tubuh' => 'required|numeric',
                'berat_badan' => 'required|numeric',
                'tinggi_badan' => 'required|numeric',
                'keluhan' => 'required'
            ];

            if (!$this->validate($rules)) {
                return $this->fail($this->validator->getErrors());
            }

            $data = [
                'id_antrian' => $this->request->getPost('id_antrian'),
                'tekanan_darah' => $this->request->getPost('tekanan_darah'),
                'suhu_tubuh' => $this->request->getPost('suhu_tubuh'),
                'berat_badan' => $this->request->getPost('berat_badan'),
                'tinggi_badan' => $this->request->getPost('tinggi_badan'),
                'keluhan' => $this->request->getPost('keluhan')
            ];

            $this->db->transStart();
            
            // Simpan data pemeriksaan
            $this->pemeriksaanModel->insert($data);
            
            // Update status antrian
            $this->db->table('antrian')
                    ->where('id', $data['id_antrian'])
                    ->update(['status' => 'Menunggu Dokter']);
            
            $this->db->transCommit();
            return $this->respond(['success' => true]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->fail('Gagal menyimpan pemeriksaan');
        }
    }

     public function simpanTriase()
    {
        $rules = [
            'id_antrian' => 'required|numeric',
            'tekanan_darah' => 'required',
            'suhu_tubuh' => 'required|numeric',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'keluhan' => 'required'
        ];
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $this->validator->getErrors()
            ]);
        }
        $data = [
            'id_antrian' => $this->request->getPost('id_antrian'),
            'tekanan_darah' => $this->request->getPost('tekanan_darah'),
            'suhu_tubuh' => $this->request->getPost('suhu_tubuh'),
            'berat_badan' => $this->request->getPost('berat_badan'),
            'tinggi_badan' => $this->request->getPost('tinggi_badan'),
            'keluhan' => $this->request->getPost('keluhan')
        ];
        $this->db->transStart();
        $this->pemeriksaanModel->insert($data);
        $this->db->table('antrian')
            ->where('id', $data['id_antrian'])
            ->update(['status' => 'Menunggu Dokter']);
        $this->db->transCommit();
        return $this->response->setJSON(['success' => true]);
    }
    // Tampilkan form triase dan update status antrian
    public function triase($id_antrian)
    {
        // Ambil data antrian
        $antrian = $this->db->table('antrian a')
            ->select('a.*, p.nama_lengkap as nama_pasien, pol.nama as poli_tujuan')
            ->join('pasien p', 'p.no_rekam_medis = a.no_rm', 'left')
            ->join('poliklinik pol', 'pol.id = a.id_poli', 'left')
            ->where('a.id', $id_antrian)
            ->get()->getRowArray();
        if (!$antrian) {
            return redirect()->to(base_url('perawat/antrianPendaftaran'))->with('error', 'Data antrian tidak ditemukan');
        }
        // Update status antrian ke "Dalam Pemeriksaan" jika masih "Menunggu Pemeriksaan"
        if ($antrian['status'] === 'Menunggu Pemeriksaan') {
            $this->db->table('antrian')->where('id', $id_antrian)->update(['status' => 'Dalam Pemeriksaan']);
            $antrian['status'] = 'Dalam Pemeriksaan';
        }
        return view('perawat/triase', ['antrian' => $antrian]);
    }

      // Tampilkan daftar pasien yang sudah diperiksa
    public function catatanPemeriksaan()
    {
        // Contoh: ambil data dari tabel pemeriksaan_awal join pasien
        $dataPemeriksaan = $this->db->table('pemeriksaan_awal pa')
            ->select('pa.id, pa.id_antrian, pa.created_at, pa.tekanan_darah, pa.suhu_tubuh, pa.berat_badan, pa.tinggi_badan, pa.keluhan, a.no_rm, p.nama_lengkap')
            ->join('antrian a', 'a.id = pa.id_antrian', 'left')
            ->join('pasien p', 'LOWER(p.no_rekam_medis) = LOWER(a.no_rm)', 'left')
            ->orderBy('pa.created_at', 'DESC')
            ->get()->getResultArray();
        $data = [
            'list_pemeriksaan' => $dataPemeriksaan
        ];
        return view('perawat/catatan_pemeriksaan', $data);
    }

    // Ambil detail hasil pemeriksaan (untuk modal/detail)
    public function detailPemeriksaan($id)
    {
        $row = $this->db->table('pemeriksaan_awal pa')
            ->select('pa.*, a.no_rm, p.nama_lengkap')
            ->join('antrian a', 'a.id = pa.id_antrian', 'left')
            ->join('pasien p', 'LOWER(p.no_rekam_medis) = LOWER(a.no_rm)', 'left')
            ->where('pa.id', $id)
            ->get()->getRowArray();
        if (!$row) {
            return '<div class="alert alert-danger">Data tidak ditemukan</div>';
        }
        // Tampilkan detail dalam bentuk grid modern (untuk modal)
        $html = '<div class="container-fluid">';
        $html .= '<div class="row mb-2">';
        $html .= '<div class="col-md-6 mb-2"><span class="text-muted small"><i class="fas fa-id-card mr-1"></i> No. RM</span><br><span class="font-weight-bold">' . esc($row['no_rm']) . '</span></div>';
        $html .= '<div class="col-md-6 mb-2"><span class="text-muted small"><i class="fas fa-user mr-1"></i> Nama Pasien</span><br><span class="font-weight-bold">' . esc($row['nama_lengkap']) . '</span></div>';
        $html .= '<div class="col-md-6 mb-2"><span class="text-muted small"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Pemeriksaan</span><br><span class="font-weight-bold">' . esc($row['created_at']) . '</span></div>';
        $html .= '<div class="col-md-6 mb-2"><span class="text-muted small"><i class="fas fa-heartbeat mr-1"></i> Tekanan Darah</span><br><span class="font-weight-bold">' . esc($row['tekanan_darah']) . ' mmHg</span></div>';
        $html .= '<div class="col-md-6 mb-2"><span class="text-muted small"><i class="fas fa-thermometer-half mr-1"></i> Suhu Tubuh</span><br><span class="font-weight-bold">' . esc($row['suhu_tubuh']) . ' °C</span></div>';
        $html .= '<div class="col-md-6 mb-2"><span class="text-muted small"><i class="fas fa-weight mr-1"></i> Berat Badan</span><br><span class="font-weight-bold">' . esc($row['berat_badan']) . ' kg</span></div>';
        $html .= '<div class="col-md-6 mb-2"><span class="text-muted small"><i class="fas fa-ruler-vertical mr-1"></i> Tinggi Badan</span><br><span class="font-weight-bold">' . esc($row['tinggi_badan']) . ' cm</span></div>';
        $html .= '<div class="col-md-12 mb-2"><span class="text-muted small"><i class="fas fa-notes-medical mr-1"></i> Keluhan</span><br><span class="font-weight-bold">' . esc($row['keluhan']) . '</span></div>';
        $html .= '</div></div>';
        return $html;
    }
}