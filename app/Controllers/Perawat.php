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
                    a.status,
                    a.created_at
                FROM antrian a
                LEFT JOIN pasien p ON BINARY p.no_rekam_medis = BINARY a.no_rm
                LEFT JOIN poliklinik pol ON pol.id = a.id_poli
                WHERE a.status = 'Menunggu Pemeriksaan'
                  AND DATE(a.created_at) = CURDATE()
                ORDER BY a.created_at ASC";
            
            $query = $this->db->query($sql);
            $data = $query->getResultArray();
            // Convert created_at to ISO 8601 (Z) for timeago.js
            foreach ($data as &$row) {
                if (isset($row['created_at']) && $row['created_at']) {
                    $dt = new \DateTime($row['created_at'], new \DateTimeZone('UTC'));
                    $row['created_at'] = $dt->format('Y-m-d\TH:i:s\Z');
                }
            }
            
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
                'detak_jantung' => 'required',
                'suhu_tubuh' => 'required|numeric',
                'pernafasan' => 'required|numeric',
                'berat_badan' => 'required|numeric',
                'tinggi_badan' => 'required|numeric',
            ];
            if (!$this->validate($rules)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $this->validator->getErrors()
                    ]);
                } else {
                    return redirect()->back()->withInput()->with('error', 'Validasi gagal');
                }
            }
            $data = [
                'id_antrian' => $this->request->getPost('id_antrian'),
                'tekanan_darah' => $this->request->getPost('tekanan_darah'),
                'detak_jantung' => $this->request->getPost('detak_jantung'),
                'suhu_tubuh' => $this->request->getPost('suhu_tubuh'),
                'pernafasan' => $this->request->getPost('pernafasan'),
                'berat_badan' => $this->request->getPost('berat_badan'),
                'tinggi_badan' => $this->request->getPost('tinggi_badan'),
                'keluhan' => $this->request->getPost('keluhan'),
            ];
            log_message('debug', '[Perawat::simpanPemeriksaan] Data masuk: ' . json_encode($data));
            $this->db->transStart();
            $this->pemeriksaanModel->insert($data);
            $this->db->table('antrian')
                ->where('id', $data['id_antrian'])
                ->update(['status' => 'Menunggu Dokter']);

            // Ambil data antrian lama
            $antrianLama = $this->db->table('antrian')->where('id', $data['id_antrian'])->get()->getRowArray();
            if ($antrianLama) {
                // Generate nomor antrian poli dengan prefix kode poli
                $idPoli = $antrianLama['id_poli'];
                $date = date('Y-m-d');
                // Ambil kode poli dari tabel poliklinik
                $poliRow = $this->db->table('poliklinik')->where('id', $idPoli)->get()->getRowArray();
                $kodePoli = isset($poliRow['kode']) ? $poliRow['kode'] : 'X';
                $lastAntrianPoli = $this->db->table('antrian_poli')
                    ->where('id_poli', $idPoli)
                    ->where('DATE(created_at)', $date)
                    ->orderBy('created_at', 'DESC')
                    ->get()->getRowArray();
                $counter = 1;
                if ($lastAntrianPoli && isset($lastAntrianPoli['no_antrian'])) {
                    $lastNo = (int)preg_replace('/[^0-9]/', '', $lastAntrianPoli['no_antrian']);
                    $counter = $lastNo + 1;
                }
                $noAntrianPoli = $kodePoli . str_pad($counter, 4, '0', STR_PAD_LEFT);

                // Insert ke antrian_poli
                $this->db->table('antrian_poli')->insert([
                    'no_antrian' => $noAntrianPoli,
                    'no_rm' => $antrianLama['no_rm'],
                    'id_poli' => $idPoli,
                    'id_antrian_perawat' => $antrianLama['id'],
                    'status' => 'Menunggu Pemeriksaan',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            $this->db->transCommit();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'no_antrian_poli' => $noAntrianPoli ?? null]);
            } else {
                // Redirect ke halaman sukses antrian poli
                return redirect()->to(base_url('perawat/antrian-poli-sukses?no_antrian=' . ($noAntrianPoli ?? '')));
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', '[Perawat::simpanPemeriksaan] Error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan pemeriksaan: ' . $e->getMessage()
                ]);
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pemeriksaan: ' . $e->getMessage());
            }
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
    // Tampilkan form tanda vital pasien (tanpa update status antrian di GET)
    public function tandavitalpasien($id_antrian)
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
        // Tidak update status antrian di sini!
        log_message('debug', '[Perawat::tandaVitalPasien] Data ke view: ' . print_r($antrian, true));
        return view('perawat/tanda_vital_pasien', ['antrian' => $antrian]);
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
        // Format tanggal ke WIB dan format Indonesia
        $createdAt = $row['created_at'];
        $dt = new \DateTime($createdAt, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone('Asia/Jakarta'));
        // Format: 25 Juli 2025 09:35 WIB
        $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $tgl = (int)$dt->format('d');
        $bln = $bulan[(int)$dt->format('m')];
        $thn = $dt->format('Y');
        $jam = $dt->format('H:i');
        $tanggalWIB = $tgl . ' ' . $bln . ' ' . $thn . ' ' . $jam ;

        $html = '<div class="container-fluid">';
        $html .= '<div class="row mb-2">';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-id-card mr-1"></i> No. RM</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['no_rm']) . '</span></div>';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-user mr-1"></i> Nama Pasien</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['nama_lengkap']) . '</span></div>';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Pemeriksaan</span><br><span style="color:#1781FF;font-weight:400;">' . esc($tanggalWIB) . '</span></div>';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-heartbeat mr-1"></i> Tekanan Darah</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['tekanan_darah']) . ' mmHg</span></div>';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-heart mr-1"></i> Detak Jantung</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['detak_jantung']) . ' x/menit</span></div>';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-thermometer-half mr-1"></i> Suhu Tubuh</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['suhu_tubuh']) . ' °C</span></div>';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-lungs mr-1"></i> Pernafasan</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['pernafasan']) . ' x/menit</span></div>';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-weight mr-1"></i> Berat Badan</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['berat_badan']) . ' kg</span></div>';
        $html .= '<div class="col-md-6 mb-3"><span class="text-muted small"><i class="fas fa-ruler-vertical mr-1"></i> Tinggi Badan</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['tinggi_badan']) . ' cm</span></div>';
        $html .= '<div class="col-md-12 mb-2"><span class="text-muted small"><i class="fas fa-notes-medical mr-1"></i> Keluhan</span><br><span style="color:#1781FF;font-weight:400;">' . esc($row['keluhan']) . '</span></div>';
        $html .= '</div></div>';
        return $html;
    }


     // Halaman sukses antrian poli
    public function antrianPoliSukses()
    {
        $noAntrian = $this->request->getGet('no_antrian');
        return view('perawat/antrian_poli_sukses', ['no_antrian' => $noAntrian]);
    }
}