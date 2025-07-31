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

        // Filter antrian hanya hari ini
        $today = date('Y-m-d');
        $builder->where('DATE(antrian_poli.created_at)', $today);

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
        return view('dokter/antrian_dokter', ['antrianPoli' => $antrianPoli]);
    }

    //pemeriksaan dokter
    public function pemeriksaanDokter($id = null)
    {
        $db = \Config\Database::connect();
        // Ambil data antrian berdasarkan ID
        $antrian = $db->table('antrian_poli')->where('id', $id)->get()->getFirstRow('array');
        if (!$antrian) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data antrian tidak ditemukan');
        }
        // Ambil data pasien dari no_rm di antrian
        $pasien = $db->table('pasien')->where('no_rekam_medis', $antrian['no_rm'])->get()->getFirstRow('array');
        // Ambil data poli dari id_poli di antrian
        $poli = $db->table('poliklinik')->where('id', $antrian['id_poli'])->get()->getFirstRow('array');
        // Ambil data dokter dari session
        $id_dokter = $this->session->get('id');
        $dokter = $db->table('users')->where('id', $id_dokter)->get()->getFirstRow('array');

        // Ambil data obat dari tabel obat
        $list_obat = $db->table('obat')->get()->getResultArray();
        $data = [
            'title' => 'Pemeriksaan Dokter - SIMRS',
            'pageTitle' => 'Pemeriksaan Dokter',
            'pasien' => $pasien,
            'poli' => $poli ? $poli['nama'] : '',
            'dokter' => $dokter ? $dokter['nama_lengkap'] : '',
            'waktu_pemeriksaan' => date('d-m-Y H:i'),
            'antrian' => $antrian,
            'list_obat' => $list_obat,
        ];

        return view('dokter/pemeriksaan_soap', $data);
    }


    //hasil pemeriksaan dokter
    public function hasilPemeriksaanDokter($id = null)
    {
        $db = \Config\Database::connect();
        // Ambil semua pemeriksaan SOAP hari ini (atau bisa filter sesuai kebutuhan)
        $builder = $db->table('pemeriksaan_soap');
        $builder->select('pemeriksaan_soap.id as id_pemeriksaan, pemeriksaan_soap.*, pasien.nama_lengkap, pasien.jenis_kelamin, pasien.tanggal_lahir, pasien.no_rekam_medis, users.nama_lengkap as dokter, poliklinik.nama as poli');
        $builder->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left');
        $builder->join('users', 'users.id = pemeriksaan_soap.id_dokter', 'left');
        $builder->join('antrian_poli', 'CAST(antrian_poli.no_rm AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left');
        $builder->join('poliklinik', 'poliklinik.id = antrian_poli.id_poli', 'left');
        $builder->where('DATE(pemeriksaan_soap.created_at)', date('Y-m-d'));
        $builder->orderBy('pemeriksaan_soap.created_at', 'desc');
        $list_pemeriksaan = $builder->get()->getResultArray();

        // Siapkan detail jika ada $id
        $pemeriksaan = null;
        if ($id) {
            $row = $db->table('pemeriksaan_soap')
                ->select('pemeriksaan_soap.id as id_pemeriksaan, pemeriksaan_soap.*, pasien.nama_lengkap, pasien.jenis_kelamin, pasien.tanggal_lahir, pasien.no_rekam_medis, users.nama_lengkap as dokter, poliklinik.nama as poli')
                ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left')
                ->join('users', 'users.id = pemeriksaan_soap.id_dokter', 'left')
                ->join('antrian_poli', 'CAST(antrian_poli.no_rm AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left')
                ->join('poliklinik', 'poliklinik.id = antrian_poli.id_poli', 'left')
                ->where('pemeriksaan_soap.id', $id)
                ->get()->getFirstRow('array');
            if ($row) {
                // Usia
                $row['usia'] = '-';
                $tglLahir = $row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null;
                if (!empty($tglLahir)) {
                    $lahir = new \DateTime($tglLahir);
                    $today = new \DateTime();
                    $row['usia'] = $today->diff($lahir)->y;
                }
                // Obat manual dan db (decode json)
                $obat_manual = !empty($row['obat_manual']) ? json_decode($row['obat_manual'], true) : [];
                $obat_db = !empty($row['obat_db']) ? json_decode($row['obat_db'], true) : [];
                $obat_list = [];
                if ($obat_manual && is_array($obat_manual)) {
                    foreach ($obat_manual as $o) {
                        if (trim($o)) $obat_list[] = $o;
                    }
                }
                // Ambil nama obat dari tabel obat jika ada id di $obat_db
                if ($obat_db && is_array($obat_db)) {
                    $obatRows = $db->table('obat')->whereIn('id_obat', $obat_db)->get()->getResultArray();
                    $obatMap = [];
                    foreach ($obatRows as $ob) {
                        $obatMap[$ob['id_obat']] = $ob['nama_obat'];
                    }
                    foreach ($obat_db as $oid) {
                        if (isset($obatMap[$oid])) $obat_list[] = $obatMap[$oid];
                    }
                }
                $row['obat_list'] = $obat_list;
                $pemeriksaan = $row;
            }
        }

        $data = [
            'title' => 'Hasil Pemeriksaan Dokter - SIMRS',
            'pageTitle' => 'Hasil Pemeriksaan Dokter',
            'list_pemeriksaan' => $list_pemeriksaan,
            'pemeriksaan' => $pemeriksaan,
        ];
        return view('dokter/hasil_pemeriksaan_dokter', $data);
    }


    // Simpan data pemeriksaan SOAP
    public function simpanPemeriksaanSoap()
    {

        log_message('debug', 'SOAP SUBMIT: method=' . $this->request->getMethod() . ' POST=' . json_encode($this->request->getPost()));
        if (strtolower($this->request->getMethod()) !== 'post') {
            log_message('error', 'SOAP ERROR: Metode tidak valid. Method=' . $this->request->getMethod() . ' URI=' . current_url());
            return $this->response->setStatusCode(405, 'Metode tidak valid');
        }

        $db = \Config\Database::connect();
        $session = $this->session;

        // Ambil data dari form
        $data = [
            'no_rm' => $this->request->getPost('no_rm'),
            'keluhan_utama' => $this->request->getPost('keluhan_utama'),
            'riwayat_penyakit' => $this->request->getPost('riwayat_penyakit'),
            'riwayat_alergi' => $this->request->getPost('riwayat_alergi'),
            'tekanan_darah' => $this->request->getPost('tekanan_darah'),
            'denyut_nadi' => $this->request->getPost('denyut_nadi'),
            'suhu_tubuh' => $this->request->getPost('suhu_tubuh'),
            'respirasi' => $this->request->getPost('respirasi'),
            'pemeriksaan_fisik' => $this->request->getPost('pemeriksaan_fisik'),
            'diagnosis' => $this->request->getPost('diagnosis'),
            'prognosis' => $this->request->getPost('prognosis'),
            'edukasi' => $this->request->getPost('edukasi'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Obat bisa multi, simpan sebagai JSON
        $obat_manual = $this->request->getPost('obat_manual');
        $obat_db = $this->request->getPost('obat_db');
        $data['obat_manual'] = $obat_manual ? json_encode($obat_manual) : null;
        $data['obat_db'] = $obat_db ? json_encode($obat_db) : null;

        // Simpan id dokter dari session jika ada
        $id_dokter = $session->get('id');
        if ($id_dokter) {
            $data['id_dokter'] = $id_dokter;
        }

        // Simpan ke database
        try {
            $db->table('pemeriksaan_soap')->insert($data);

            // Update status antrian jika id_antrian dikirim
            $id_antrian = $this->request->getPost('id_antrian');
            if ($id_antrian) {
                // Update di antrian_poli
                $db->table('antrian_poli')->where('id', $id_antrian)->update(['status' => 'Menunggu Kasir']);
                // Update juga di antrian utama jika ada (untuk sinkronisasi dashboard admisi)
                $antrianPoli = $db->table('antrian_poli')->where('id', $id_antrian)->get()->getFirstRow('array');
                if ($antrianPoli && isset($antrianPoli['no_rm'])) {
                    // Ambil antrian utama terbaru hari ini untuk no_rm tsb
                    $antrianUtama = $db->table('antrian')
                        ->where('no_rm', $antrianPoli['no_rm'])
                        ->where('DATE(created_at)', date('Y-m-d'))
                        ->orderBy('id', 'desc')
                        ->get(1)->getFirstRow('array');
                    if ($antrianUtama && in_array($antrianUtama['status'], ['Menunggu Dokter', 'Dalam Pemeriksaan', 'Menunggu Pemeriksaan'])) {
                        $db->table('antrian')
                            ->where('id', $antrianUtama['id'])
                            ->update(['status' => 'Menunggu Kasir']);
                    }
                }
            }

            // Redirect ke halaman sukses
            return redirect()->to('/dokter/pemeriksaasn_soap_sukses');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal menyimpan data: ' . $e->getMessage());
            return redirect()->back();
        }
    }


     // Detail pemeriksaan pasien (SOAP)
    public function detailPemeriksaanPasien($id = null)
    {
        $db = \Config\Database::connect();
        $pemeriksaan = null;
        // Debug: pastikan id yang diterima benar
        // log_message('debug', 'detailPemeriksaanPasien id: ' . print_r($id, true));
        if ($id) {
            $row = $db->table('pemeriksaan_soap')
                ->select('pemeriksaan_soap.id as id_pemeriksaan, pemeriksaan_soap.*, pasien.nama_lengkap, pasien.jenis_kelamin, pasien.tanggal_lahir, pasien.no_rekam_medis, users.nama_lengkap as dokter, poliklinik.nama as poli')
                ->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left')
                ->join('users', 'users.id = pemeriksaan_soap.id_dokter', 'left')
                ->join('antrian_poli', 'CAST(antrian_poli.no_rm AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left')
                ->join('poliklinik', 'poliklinik.id = antrian_poli.id_poli', 'left')
                ->where('pemeriksaan_soap.id', $id)
                ->get()->getFirstRow('array');
            if ($row) {
                // Usia
                $row['usia'] = '-';
                $tglLahir = $row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null;
                if (!empty($tglLahir)) {
                    $lahir = new \DateTime($tglLahir);
                    $today = new \DateTime();
                    $row['usia'] = $today->diff($lahir)->y;
                }
                // Obat manual dan db (decode json)
                $obat_manual = !empty($row['obat_manual']) ? json_decode($row['obat_manual'], true) : [];
                $obat_db = !empty($row['obat_db']) ? json_decode($row['obat_db'], true) : [];
                $obat_list = [];
                if ($obat_manual && is_array($obat_manual)) {
                    foreach ($obat_manual as $o) {
                        if (trim($o)) $obat_list[] = $o;
                    }
                }
                // Ambil nama obat dari tabel obat jika ada id di $obat_db
                if ($obat_db && is_array($obat_db)) {
                    $obatRows = $db->table('obat')->whereIn('id_obat', $obat_db)->get()->getResultArray();
                    $obatMap = [];
                    foreach ($obatRows as $ob) {
                        $obatMap[$ob['id_obat']] = $ob['nama_obat'];
                    }
                    foreach ($obat_db as $oid) {
                        if (isset($obatMap[$oid])) $obat_list[] = $obatMap[$oid];
                    }
                }
                $row['obat_list'] = $obat_list;
                $pemeriksaan = $row;
            }
        }
        $data = [
            'title' => 'Detail Pemeriksaan Pasien - SIMRS',
            'pageTitle' => 'Detail Pemeriksaan Pasien',
            'pemeriksaan' => $pemeriksaan,
        ];
        return view('dokter/detailpemeriksaanpasien', $data);
    }


}