<?php

namespace App\Controllers;

class RekamMedis extends BaseController
{
    protected $session;
    protected $db;
    protected $pasienModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->pasienModel = new \App\Models\PasienModel();
        
        // check akses role
        $allowedRoles = ['admin', 'dokter', 'perawat', 'admisi'];
        $userRole = $this->session->get('role');
        
        if (!$this->session->get('isLoggedIn') || !in_array($userRole, $allowedRoles)) {
            redirect()->to(base_url('login'))->send();
            exit();
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Rekam Medis Pasien - SIMRS',
            'pageTitle' => 'Rekam Medis Pasien'
        ];

        return view('rekam_medis/index', $data);
    }

    public function getPasienList()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $search = $this->request->getGet('search');
            $start = (int)$this->request->getGet('start') ?? 0;
            $length = (int)$this->request->getGet('length') ?? 10;

            $builder = $this->db->table('pasien p')
                ->select('
                    p.id,
                    p.no_rekam_medis,
                    p.title,
                    p.nama_lengkap,
                    p.jenis_kelamin,
                    p.tanggal_lahir,
                    p.nomor_hp,
                    p.tanggal_daftar,
                    COUNT(DISTINCT a.id) as total_kunjungan,
                    MAX(a.created_at) as kunjungan_terakhir
                ')
                ->join('antrian a', 'a.no_rm = p.no_rekam_medis', 'left')
                ->groupBy('p.id');

            // Search
            if ($search) {
                $builder->groupStart()
                    ->like('p.no_rekam_medis', $search)
                    ->orLike('p.nama_lengkap', $search)
                    ->orLike('p.nomor_hp', $search)
                    ->groupEnd();
            }

            // Total records
            $totalRecords = $builder->countAllResults(false);

            // Get data
            $data = $builder->orderBy('p.tanggal_daftar', 'DESC')
                ->limit($length, $start)
                ->get()
                ->getResultArray();

            // Format data
            foreach ($data as &$row) {
                if ($row['tanggal_lahir']) {
                    $umur = date_diff(date_create($row['tanggal_lahir']), date_create('today'))->y;
                    $row['umur'] = $umur . ' tahun';
                } else {
                    $row['umur'] = '-';
                }

                $row['jenis_kelamin_text'] = $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan';
                $row['kunjungan_terakhir_formatted'] = $row['kunjungan_terakhir'] 
                    ? date('d/m/Y H:i', strtotime($row['kunjungan_terakhir'])) 
                    : '-';
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $data,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ]);
        }
    }

    public function detail($no_rm = null)
    {
        if (!$no_rm) {
            return redirect()->to('rekam-medis')->with('error', 'Nomor rekam medis tidak ditemukan');
        }

        // Get patient data
        $pasien = $this->pasienModel->where('no_rekam_medis', $no_rm)->first();
        
        if (!$pasien) {
            return redirect()->to('rekam-medis')->with('error', 'Data pasien tidak ditemukan');
        }

        // Get riwayat kunjungan dengan detail lengkap
        $riwayatKunjungan = $this->db->query("
            SELECT 
                a.id as id_antrian,
                a.no_antrian,
                a.created_at as tanggal_kunjungan,
                a.status as status_antrian,
                pol.nama as nama_poli,
                pm.tekanan_darah,
                pm.detak_jantung,
                pm.suhu_tubuh,
                pm.pernafasan,
                pm.berat_badan,
                pm.tinggi_badan,
                pm.keluhan,
                s.keluhan_utama as subjective,
                s.pemeriksaan_fisik as objective,
                s.diagnosis as assessment,
                s.edukasi as plan,
                s.riwayat_penyakit,
                s.riwayat_alergi,
                s.prognosis,
                d.nama_lengkap as nama_dokter,
                (SELECT COUNT(*) FROM resep WHERE resep.id_pasien = ? AND DATE(resep.tanggal_resep) = DATE(a.created_at)) as jumlah_resep,
                (SELECT SUM(jumlah_bayar) FROM tagihan WHERE tagihan.no_rm = ? AND DATE(tagihan.tanggal_bayar) = DATE(a.created_at)) as total_biaya
            FROM antrian a
            LEFT JOIN poliklinik pol ON pol.id = a.id_poli
            LEFT JOIN pemeriksaan_awal pm ON pm.id_antrian = a.id
            LEFT JOIN pemeriksaan_soap s ON s.no_rm COLLATE utf8mb4_unicode_ci = a.no_rm COLLATE utf8mb4_unicode_ci AND DATE(s.created_at) = DATE(a.created_at)
            LEFT JOIN users d ON d.id = s.id_dokter
            WHERE a.no_rm = ?
            ORDER BY a.created_at DESC
        ", [$pasien['id'], $no_rm, $no_rm])->getResultArray();

        // Get alamat pasien
        $alamat = $this->db->table('alamat_pasien')->where('pasien_id', $pasien['id'])->get()->getRowArray();
        
        // Get info medis
        $infoMedis = $this->db->table('info_medis_pasien')->where('pasien_id', $pasien['id'])->get()->getRowArray();
        
        // Get info tambahan
        $infoTambahan = $this->db->table('info_tambahan_pasien')->where('pasien_id', $pasien['id'])->get()->getRowArray();

        $data = [
            'title' => 'Detail Rekam Medis - ' . $pasien['nama_lengkap'],
            'pageTitle' => 'Detail Rekam Medis',
            'pasien' => $pasien,
            'alamat' => $alamat,
            'infoMedis' => $infoMedis,
            'infoTambahan' => $infoTambahan,
            'riwayatKunjungan' => $riwayatKunjungan
        ];

        return view('rekam_medis/detail', $data);
    }

    public function getDetailKunjungan($id_antrian = null)
    {
        if (!$this->request->isAJAX() || !$id_antrian) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            // Get detail resep
            $resep = $this->db->query("
                SELECT 
                    r.*,
                    o.nama_obat,
                    o.satuan,
                    o.harga
                FROM resep r
                LEFT JOIN obat o ON o.id = r.id_obat
                WHERE r.id_antrian = ?
            ", [$id_antrian])->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'resep' => $resep
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengambil detail: ' . $e->getMessage()
            ]);
        }
    }

    public function print($no_rm = null)
    {
        if (!$no_rm) {
            return redirect()->to('rekam-medis')->with('error', 'Nomor rekam medis tidak ditemukan');
        }

        // Get patient data
        $pasien = $this->pasienModel->where('no_rekam_medis', $no_rm)->first();
        
        if (!$pasien) {
            return redirect()->to('rekam-medis')->with('error', 'Data pasien tidak ditemukan');
        }

        // Get riwayat kunjungan (10 terakhir untuk print)
        $riwayatKunjungan = $this->db->query("
            SELECT 
                a.id as id_antrian,
                a.no_antrian,
                a.created_at as tanggal_kunjungan,
                pol.nama as nama_poli,
                pm.tekanan_darah,
                pm.detak_jantung,
                pm.suhu_tubuh,
                pm.pernafasan,
                pm.berat_badan,
                pm.tinggi_badan,
                pm.keluhan,
                s.keluhan_utama as subjective,
                s.pemeriksaan_fisik as objective,
                s.diagnosis as assessment,
                s.edukasi as plan,
                s.riwayat_penyakit,
                s.riwayat_alergi,
                s.prognosis,
                d.nama_lengkap as nama_dokter,
                (SELECT COUNT(*) FROM resep WHERE resep.id_pasien = ? AND DATE(resep.tanggal_resep) = DATE(a.created_at)) as jumlah_resep,
                (SELECT SUM(jumlah_bayar) FROM tagihan WHERE tagihan.no_rm = ? AND DATE(tagihan.tanggal_bayar) = DATE(a.created_at)) as total_biaya
            FROM antrian a
            LEFT JOIN poliklinik pol ON pol.id = a.id_poli
            LEFT JOIN pemeriksaan_awal pm ON pm.id_antrian = a.id
            LEFT JOIN pemeriksaan_soap s ON s.no_rm COLLATE utf8mb4_unicode_ci = a.no_rm COLLATE utf8mb4_unicode_ci AND DATE(s.created_at) = DATE(a.created_at)
            LEFT JOIN users d ON d.id = s.id_dokter
            WHERE a.no_rm = ?
            ORDER BY a.created_at DESC
            LIMIT 10
        ", [$pasien['id'], $no_rm, $no_rm])->getResultArray();

        // Get alamat pasien
        $alamat = $this->db->table('alamat_pasien')->where('pasien_id', $pasien['id'])->get()->getRowArray();
        
        // Get info medis
        $infoMedis = $this->db->table('info_medis_pasien')->where('pasien_id', $pasien['id'])->get()->getRowArray();
        
        // Get info tambahan
        $infoTambahan = $this->db->table('info_tambahan_pasien')->where('pasien_id', $pasien['id'])->get()->getRowArray();

        $data = [
            'pasien' => $pasien,
            'alamat' => $alamat,
            'infoMedis' => $infoMedis,
            'infoTambahan' => $infoTambahan,
            'riwayatKunjungan' => $riwayatKunjungan
        ];

        return view('rekam_medis/print', $data);
    }
}
