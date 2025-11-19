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

        // Convert created_at to ISO 8601 for timeago.js
        // Data di database sudah dalam timezone Asia/Jakarta
        foreach ($antrianPoli as &$row) {
            if (isset($row['created_at']) && $row['created_at']) {
                // Parse sebagai Asia/Jakarta (sesuai dengan data di database)
                $dt = new \DateTime($row['created_at'], new \DateTimeZone('Asia/Jakarta'));
                // Format untuk timeago.js dengan timezone offset
                $row['created_at'] = $dt->format('c'); // ISO 8601 dengan timezone: 2025-11-04T18:23:00+07:00
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
        
        // Hitung usia pasien berdasarkan tanggal lahir
        if ($pasien && !empty($pasien['tanggal_lahir'])) {
            try {
                $lahir = new \DateTime($pasien['tanggal_lahir']);
                $today = new \DateTime();
                $pasien['usia'] = $today->diff($lahir)->y;
                $pasien['tgl_lahir'] = $pasien['tanggal_lahir']; 
            } catch (\Exception $e) {
                $pasien['usia'] = 0;
                $pasien['tgl_lahir'] = $pasien['tanggal_lahir'];
            }
        } else {
            $pasien['usia'] = 0;
            $pasien['tgl_lahir'] = '';
        }
        
        // Ambil data poli dari id_poli di antrian
        $poli = $db->table('poliklinik')->where('id', $antrian['id_poli'])->get()->getFirstRow('array');
        // Ambil data dokter dari session
        $id_dokter = $this->session->get('id');
        $dokter = $db->table('users')->where('id', $id_dokter)->get()->getFirstRow('array');

        // Ambil data obat dari tabel obat
        $list_obat = $db->table('obat')->get()->getResultArray();
        
        // Ambil data tanda vital dari pemeriksaan_awal berdasarkan id_antrian
        $tanda_vital = null;
        
        // Jika antrian poli punya id_antrian_perawat, gunakan itu
        if (isset($antrian['id_antrian_perawat']) && !empty($antrian['id_antrian_perawat'])) {
            $tanda_vital = $db->table('pemeriksaan_awal')
                ->where('id_antrian', $antrian['id_antrian_perawat'])
                ->get()
                ->getFirstRow('array');
        }
        
        // Jika tidak ada, coba cari berdasarkan no_rm dan tanggal hari ini
        if (!$tanda_vital) {
            $tanda_vital = $db->table('pemeriksaan_awal pa')
                ->join('antrian a', 'a.id = pa.id_antrian', 'left')
                ->where('a.no_rm', $antrian['no_rm'])
                ->where('DATE(pa.created_at)', date('Y-m-d'))
                ->select('pa.*')
                ->orderBy('pa.created_at', 'DESC')
                ->get()
                ->getFirstRow('array');
        }
        
        $data = [
            'title' => 'Pemeriksaan Dokter - SIMRS',
            'pageTitle' => 'Pemeriksaan Dokter',
            'pasien' => $pasien,
            'poli' => $poli ? $poli['nama'] : '',
            'dokter' => $dokter ? $dokter['nama_lengkap'] : '',
            'waktu_pemeriksaan' => date('d-m-Y H:i'),
            'antrian' => $antrian,
            'list_obat' => $list_obat,
            'tanda_vital' => $tanda_vital,
        ];

        return view('dokter/pemeriksaan_soap', $data);
    }


    //hasil pemeriksaan dokter
    public function hasilPemeriksaanDokter($id = null)
    {
        $db = \Config\Database::connect();
        
        // Ambil ID dan role user yang sedang login
        $id_dokter = $this->session->get('id');
        $userRole = $this->session->get('role');
        
        // Ambil semua pemeriksaan SOAP hari ini
        $builder = $db->table('pemeriksaan_soap');
        $builder->select('pemeriksaan_soap.id as id_pemeriksaan, pemeriksaan_soap.*, pasien.nama_lengkap, pasien.jenis_kelamin, pasien.tanggal_lahir, pasien.no_rekam_medis, users.nama_lengkap as dokter, poliklinik.nama as poli');
        $builder->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left');
        $builder->join('users', 'users.id = pemeriksaan_soap.id_dokter', 'left');
        // JOIN antrian_poli hanya yang hari ini untuk menghindari duplikasi
        $builder->join('antrian_poli', 'CAST(antrian_poli.no_rm AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR) AND DATE(antrian_poli.created_at) = DATE(pemeriksaan_soap.created_at)', 'left');
        $builder->join('poliklinik', 'poliklinik.id = antrian_poli.id_poli', 'left');
        
        
        if ($userRole === 'dokter') {
            $builder->where('pemeriksaan_soap.id_dokter', $id_dokter);
        }
        
        
        $builder->where('DATE(pemeriksaan_soap.created_at)', date('Y-m-d'));
        $builder->groupBy('pemeriksaan_soap.id'); // Hindari duplikasi
        $builder->orderBy('pemeriksaan_soap.created_at', 'desc');
        $list_pemeriksaan = $builder->get()->getResultArray();
        
        // Format waktu pemeriksaan untuk setiap record dengan timezone WIB
        foreach ($list_pemeriksaan as &$item) {
            if (!empty($item['created_at'])) {
                $datetime = new \DateTime($item['created_at']);
                $datetime->setTimezone(new \DateTimeZone('Asia/Jakarta'));
                $item['waktu_pemeriksaan'] = $datetime->format('d-m-Y H:i');
            } else {
                $item['waktu_pemeriksaan'] = '-';
            }
        }
        unset($item); // Hapus referensi

        // Siapkan detail jika ada $id
        $pemeriksaan = null;
        if ($id) {
            $detailBuilder = $db->table('pemeriksaan_soap');
            $detailBuilder->select('pemeriksaan_soap.id as id_pemeriksaan, pemeriksaan_soap.*, pasien.nama_lengkap, pasien.jenis_kelamin, pasien.tanggal_lahir, pasien.no_rekam_medis, users.nama_lengkap as dokter, poliklinik.nama as poli');
            $detailBuilder->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left');
            $detailBuilder->join('users', 'users.id = pemeriksaan_soap.id_dokter', 'left');
            $detailBuilder->join('antrian_poli', 'CAST(antrian_poli.no_rm AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left');
            $detailBuilder->join('poliklinik', 'poliklinik.id = antrian_poli.id_poli', 'left');
            $detailBuilder->where('pemeriksaan_soap.id', $id);
            
            // Filter berdasarkan role: Dokter hanya lihat pasiennya, Admin lihat semua
            if ($userRole === 'dokter') {
                $detailBuilder->where('pemeriksaan_soap.id_dokter', $id_dokter);
            }
            
            $row = $detailBuilder->get()->getFirstRow('array');
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
            $db->transStart();
            
            // Simpan pemeriksaan SOAP
            $db->table('pemeriksaan_soap')->insert($data);
            $id_pemeriksaan = $db->insertID();

            // Simpan resep obat ke tabel resep untuk farmasi
            $jumlahResep = $this->simpanResepObat($id_pemeriksaan, $data['no_rm'], $obat_manual, $obat_db, $id_dokter);

            // Tentukan status selanjutnya berdasarkan ada/tidaknya resep
            // Jika ada resep -> Menunggu Farmasi
            // Jika tidak ada resep -> Menunggu Kasir (langsung bayar)
            if ($jumlahResep > 0) {
                $statusSelanjutnya = 'Menunggu Farmasi';
            } else {
                $statusSelanjutnya = 'Menunggu Kasir';
            }

            // Update status antrian jika id_antrian dikirim
            $id_antrian = $this->request->getPost('id_antrian');
            log_message('info', '[Dokter::simpanPemeriksaanSoap] id_antrian: ' . ($id_antrian ?? 'NULL'));
            log_message('info', '[Dokter::simpanPemeriksaanSoap] Jumlah resep: ' . $jumlahResep);
            log_message('info', '[Dokter::simpanPemeriksaanSoap] Status selanjutnya: ' . $statusSelanjutnya);
            
            if ($id_antrian) {
                // Update di antrian_poli
                $db->table('antrian_poli')->where('id', $id_antrian)->update(['status' => $statusSelanjutnya]);
                log_message('info', '[Dokter::simpanPemeriksaanSoap] Updated antrian_poli id=' . $id_antrian);
                
                // Ambil id_antrian_perawat dari antrian_poli untuk update antrian utama
                $antrianPoli = $db->table('antrian_poli')->where('id', $id_antrian)->get()->getFirstRow('array');
                log_message('info', '[Dokter::simpanPemeriksaanSoap] Antrian poli data: ' . json_encode($antrianPoli));
                
                if ($antrianPoli && isset($antrianPoli['id_antrian_perawat'])) {
                    // Update antrian utama berdasarkan ID langsung (lebih reliable)
                    $updated = $db->table('antrian')
                        ->where('id', $antrianPoli['id_antrian_perawat'])
                        ->update(['status' => $statusSelanjutnya]);
                    
                    log_message('info', '[Dokter::simpanPemeriksaanSoap] Updated antrian id=' . $antrianPoli['id_antrian_perawat'] . ' to "' . $statusSelanjutnya . '", rows affected: ' . $updated);
                } else {
                    log_message('warning', '[Dokter::simpanPemeriksaanSoap] Antrian poli not found or id_antrian_perawat missing');
                }
            } else {
                log_message('warning', '[Dokter::simpanPemeriksaanSoap] id_antrian is NULL');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data ke database');
            }

            // Redirect ke halaman sukses
            return redirect()->to('/dokter/pemeriksaasn_soap_sukses');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal menyimpan data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // Method untuk menyimpan resep obat ke tabel resep
    private function simpanResepObat($id_pemeriksaan, $no_rm, $obat_manual, $obat_db, $id_dokter)
    {
        $db = \Config\Database::connect();
        $jumlahResepDisimpan = 0;
        
        // Ambil data pasien
        $pasien = $db->table('pasien')->where('no_rekam_medis', $no_rm)->get()->getFirstRow('array');
        if (!$pasien) {
            log_message('warning', 'Pasien tidak ditemukan untuk no_rm: ' . $no_rm);
            return 0;
        }

        // Ambil data tambahan dari POST
        $jumlah_obat = $this->request->getPost('jumlah_obat');
        $instruksi_obat = $this->request->getPost('instruksi_obat');

        // Simpan obat manual
        if (!empty($obat_manual) && is_array($obat_manual)) {
            foreach ($obat_manual as $index => $obat) {
                if (trim($obat)) {
                    $jumlah = isset($jumlah_obat[$index]) && $jumlah_obat[$index] ? $jumlah_obat[$index] : 1;
                    $instruksi = isset($instruksi_obat[$index]) && $instruksi_obat[$index] ? $instruksi_obat[$index] : 'Sesuai instruksi dokter';
                    
                    $resepData = [
                        'id_pasien' => $pasien['id'],
                        'id_dokter' => $id_dokter,
                        'id_obat' => null, // Manual obat tidak punya id_obat
                        'nama_obat' => trim($obat),
                        'jumlah' => $jumlah,
                        'satuan' => 'pcs',
                        'instruksi' => $instruksi,
                        'status' => 'pending',
                        'tanggal_resep' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $db->table('resep')->insert($resepData);
                    $jumlahResepDisimpan++;
                }
            }
        }

        // Simpan obat dari database
        if (!empty($obat_db) && is_array($obat_db)) {
            foreach ($obat_db as $index => $id_obat) {
                if ($id_obat) {
                    // Ambil data obat
                    $obat = $db->table('obat')->where('id_obat', $id_obat)->get()->getFirstRow('array');
                    if ($obat) {
                        $jumlah = isset($jumlah_obat[$index]) && $jumlah_obat[$index] ? $jumlah_obat[$index] : 1;
                        $instruksi = isset($instruksi_obat[$index]) && $instruksi_obat[$index] ? $instruksi_obat[$index] : 'Sesuai instruksi dokter';
                        
                        $resepData = [
                            'id_pasien' => $pasien['id'],
                            'id_dokter' => $id_dokter,
                            'id_obat' => $id_obat,
                            'nama_obat' => $obat['nama_obat'],
                            'jumlah' => $jumlah,
                            'satuan' => $obat['satuan'] ?? 'pcs',
                            'instruksi' => $instruksi,
                            'status' => 'pending',
                            'tanggal_resep' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        $db->table('resep')->insert($resepData);
                        $jumlahResepDisimpan++;
                    }
                }
            }
        }

        return $jumlahResepDisimpan;
    }


     // Detail pemeriksaan pasien (SOAP)
    public function detailPemeriksaanPasien($id = null)
    {
        $db = \Config\Database::connect();
        $pemeriksaan = null;
        
        // Ambil ID dan role user yang sedang login
        $id_dokter = $this->session->get('id');
        $userRole = $this->session->get('role');
        
        // Debug: pastikan id yang diterima benar
        // log_message('debug', 'detailPemeriksaanPasien id: ' . print_r($id, true));
        if ($id) {
            $builder = $db->table('pemeriksaan_soap');
            $builder->select('pemeriksaan_soap.id as id_pemeriksaan, pemeriksaan_soap.*, pasien.nama_lengkap, pasien.jenis_kelamin, pasien.tanggal_lahir, pasien.no_rekam_medis, users.nama_lengkap as dokter, poliklinik.nama as poli');
            $builder->join('pasien', 'CAST(pasien.no_rekam_medis AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left');
            $builder->join('users', 'users.id = pemeriksaan_soap.id_dokter', 'left');
            $builder->join('antrian_poli', 'CAST(antrian_poli.no_rm AS CHAR) = CAST(pemeriksaan_soap.no_rm AS CHAR)', 'left');
            $builder->join('poliklinik', 'poliklinik.id = antrian_poli.id_poli', 'left');
            $builder->where('pemeriksaan_soap.id', $id);
            
            // Filter berdasarkan role: Dokter hanya lihat pasiennya, Admin lihat semua
            if ($userRole === 'dokter') {
                $builder->where('pemeriksaan_soap.id_dokter', $id_dokter);
            }
            
            $row = $builder->get()->getFirstRow('array');
            if ($row) {
                // Usia
                $row['usia'] = '-';
                $tglLahir = $row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null;
                if (!empty($tglLahir)) {
                    $lahir = new \DateTime($tglLahir);
                    $today = new \DateTime();
                    $row['usia'] = $today->diff($lahir)->y;
                }
                
                // Format waktu pemeriksaan dengan timezone WIB
                if (!empty($row['created_at'])) {
                    $datetime = new \DateTime($row['created_at']);
                    $datetime->setTimezone(new \DateTimeZone('Asia/Jakarta'));
                    $row['waktu_pemeriksaan'] = $datetime->format('d-m-Y H:i');
                } else {
                    $row['waktu_pemeriksaan'] = '-';
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

    public function catatanPemeriksaan()
    {
        $db = \Config\Database::connect();
        
        // Query untuk mengambil data pemeriksaan_awal
        $builder = $db->table('pemeriksaan_awal pa');
        $builder->select('pa.*, a.no_rm, p.nama_lengkap');
        $builder->join('antrian a', 'a.id = pa.id_antrian', 'left');
        $builder->join('pasien p', 'LOWER(p.no_rekam_medis) = LOWER(a.no_rm)', 'left');
        $builder->orderBy('pa.created_at', 'DESC');
        
        $list_pemeriksaan = $builder->get()->getResultArray();
        
        $data = [
            'title' => 'Catatan Pemeriksaan Pasien - SIMRS',
            'pageTitle' => 'Catatan Pemeriksaan Pasien',
            'list_pemeriksaan' => $list_pemeriksaan,
        ];
        
        return view('dokter/catatan_pemeriksaan', $data);
    }

    public function detailPemeriksaan($id = null)
    {
        if (!$id) {
            return '<div class="alert alert-danger">ID tidak valid</div>';
        }
        
        $db = \Config\Database::connect();
        
        // Ambil detail pemeriksaan_awal
        $pemeriksaan = $db->table('pemeriksaan_awal pa')
            ->select('pa.*, a.no_rm as no_rekam_medis, p.nama_lengkap')
            ->join('antrian a', 'a.id = pa.id_antrian', 'left')
            ->join('pasien p', 'LOWER(p.no_rekam_medis) = LOWER(a.no_rm)', 'left')
            ->where('pa.id', $id)
            ->get()->getRowArray();
        
        if (!$pemeriksaan) {
            return '<div class="alert alert-danger">Data tidak ditemukan</div>';
        }
        
        $data = [
            'pemeriksaan' => $pemeriksaan,
        ];
        
        return view('dokter/detail_pemeriksaan_partial', $data);
    }


}