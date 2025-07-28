<?php

namespace App\Controllers;

class Dokter extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        
        // Check if user is logged in and has dokter role OR admin role
        $userRole = $this->session->get('role');
        if (!$this->session->get('isLoggedIn') || ($userRole !== 'dokter' && $userRole !== 'admin')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Dokter - SIMRS',
            'pageTitle' => 'Dashboard Dokter',
        ];

        return view('dokter/dashboard', $data);
    }
    // Tampilan antrian dokter (poli)
    public function antrianPoli()
    {
        // Inisialisasi koneksi database dan variabel session
        $db = \Config\Database::connect();
        $userId = $this->session->get('id');
        $userRole = $this->session->get('role');
        $idPoliList = [];

        // Jika user adalah dokter, filter antrian berdasarkan poliklinik sesuai spesialisasi atau jadwal praktik
        if ($userRole === 'dokter') {
            // Ambil data user dokter dari database
            $userRow = $db->table('users')->where('id', $userId)->get()->getFirstRow('array');
            $spesialisasi = $userRow ? $userRow['spesialisasi'] : null;

            // Mapping spesialisasi ke kode poliklinik
            if (!empty($spesialisasi)) {
                $mapping = [
                    'Gigi' => 'GIGI',
                    'Dokter Gigi' => 'GIGI',
                    'Dokter Umum' => 'UMUM',
                    'Anak' => 'ANAK',
                ];
                $kodePoli = isset($mapping[$spesialisasi]) ? $mapping[$spesialisasi] : strtoupper($spesialisasi);

                // Cari poliklinik yang sesuai kode hasil mapping
                if (!empty($kodePoli)) {
                    $poliRows = $db->table('poliklinik')->where('kode', $kodePoli)->get()->getResultArray();
                    foreach ($poliRows as $poli) {
                        $idPoliList[] = $poli['id'];
                    }
                }
            }

            // Jika tidak ada poliklinik yang cocok, fallback ke jadwal praktik dokter
            if (empty($idPoliList)) {
                $jadwalRows = $db->table('dokter_jadwal')
                    ->where('dokter_id', $userId)
                    ->get()->getResultArray();
                foreach ($jadwalRows as $jadwal) {
                    if (isset($jadwal['poliklinik_id'])) {
                        $idPoliList[] = $jadwal['poliklinik_id'];
                    }
                }
            }
        }

        // Query antrian poli, join dengan pasien dan poliklinik
        $builder = $db->table('antrian_poli');
        $builder->select('antrian_poli.*, pasien.nama_lengkap as nama_pasien, poliklinik.nama as poli_tujuan');
        $builder->join('pasien', 'pasien.no_rekam_medis = antrian_poli.no_rm', 'left');
        $builder->join('poliklinik', 'poliklinik.id = antrian_poli.id_poli', false);
        $builder->where('antrian_poli.status', 'Menunggu Pemeriksaan');

        // Filter antrian hanya untuk dokter, admin melihat semua
        if ($userRole === 'dokter') {
            if (!empty($idPoliList)) {
                $builder->whereIn('antrian_poli.id_poli', $idPoliList); // hanya poli sesuai dokter
            } else {
                $builder->where('antrian_poli.id_poli', -1); // tidak ada poli, tampilkan kosong
            }
        }
        $builder->orderBy('antrian_poli.created_at', 'asc');
        $antrianPoli = $builder->get()->getResultArray();

        // Kirim data ke view antrian dokter
        $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        foreach ($antrianPoli as &$row) {
            if (isset($row['created_at']) && $row['created_at']) {
                $dt = new \DateTime($row['created_at'], new \DateTimeZone('UTC'));
                $dt->setTimezone(new \DateTimeZone('Asia/Jakarta'));
                $tgl = (int)$dt->format('d');
                $bln = $bulan[(int)$dt->format('m')];
                $thn = $dt->format('Y');
                $jam = $dt->format('H:i');
                $row['created_at'] = $tgl . ' ' . $bln . ' ' . $thn . ' ' . $jam . ' WIB';
            }
        }
        $data = [
            'title' => 'Antrian Poli - SIMRS',
            'pageTitle' => 'Antrian Poli',
            'antrianPoli' => $antrianPoli
        ];
        return view('dokter/antrian_dokter', $data);
    }

    //pemeriksaan dokter
    public function pemeriksaanDokter()
    {
        $data = [
            'title' => 'Pemeriksaan Dokter - SIMRS',
            'pageTitle' => 'Pemeriksaan Dokter',
        ];

        return view('dokter/pemeriksaan_soap', $data);
    }


    //hasil pemeriksaan dokter
    public function hasilPemeriksaanDokter()
    {
        $data = [
            'title' => 'Hasil Pemeriksaan Dokter - SIMRS',
            'pageTitle' => 'Hasil Pemeriksaan Dokter',
        ];
        return view('dokter/hasil_pemeriksaan_dokter', $data);
    }

}