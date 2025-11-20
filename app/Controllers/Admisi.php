<?php

namespace App\Controllers;

class Admisi extends BaseController
{
    protected $session;
    protected $db;
    protected $validation;
    protected $postalCodeModel;
    protected $pasienModel;
    protected $alamatPasienModel;
    protected $antrianModel;
    protected $poliModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pasienModel = new \App\Models\PasienModel();
        $this->poliModel = new \App\Models\PoliklinikModel();
        $this->postalCodeModel = new \App\Models\PostalCodeModel();
        $this->alamatPasienModel = new \App\Models\AlamatPasienModel();
        $this->antrianModel = new \App\Models\AntrianModel();
        $this->db = \Config\Database::connect();
        
        // Check if user is logged in and has admisi role OR admin role
        $userRole = $this->session->get('role');
        if (!$this->session->get('isLoggedIn') || ($userRole !== 'admisi' && $userRole !== 'admin')) {
            // Redirect to login instead of throwing exception
            redirect()->to(base_url('login'))->send();
            exit();
        }
    }
    public function index()
    {
        // Redirect to the main admisi page
        $data = [
            'title' => 'Registrasi Pasien - SIMRS',
        ];
        return redirect()->to('admisi/registrasi-pasien');
    }

    public function cariKodepos()
    {
        $kodePos = $this->request->getGet('kode_pos');
        
        if (!$kodePos) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kode pos tidak valid'
            ]);
        }

        try {
            $data = $this->postalCodeModel->findByPostalCode($kodePos);
            

            // Mapping kode provinsi ke nama provinsi
            $provinsiMap = [
                '11' => 'Aceh',
                '12' => 'Sumatera Utara',
                '13' => 'Sumatera Barat',
                '14' => 'Riau',
                '15' => 'Jambi',
                '16' => 'Sumatera Selatan',
                '17' => 'Bengkulu',
                '18' => 'Lampung',
                '19' => 'Kepulauan Bangka Belitung',
                '21' => 'Kepulauan Riau',
                '31' => 'DKI Jakarta',
                '32' => 'Jawa Barat',
                '33' => 'Jawa Tengah',
                '34' => 'DI Yogyakarta',
                '35' => 'Jawa Timur',
                '36' => 'Banten',
                '51' => 'Bali',
                '52' => 'Nusa Tenggara Barat',
                '53' => 'Nusa Tenggara Timur',
                '61' => 'Kalimantan Barat',
                '62' => 'Kalimantan Tengah',
                '63' => 'Kalimantan Selatan',
                '64' => 'Kalimantan Timur',
                '65' => 'Kalimantan Utara',
                '72' => 'Sulawesi Tengah',
                '73' => 'Sulawesi Selatan',
                '74' => 'Sulawesi Tenggara',
                '75' => 'Gorontalo',
                '76' => 'Sulawesi Barat',
                '81' => 'Maluku',
                '82' => 'Maluku Utara',
                '91' => 'Papua',
                '92' => 'Papua Barat',
            ];

            if ($data) {
                // Tambahkan nama provinsi ke hasil
                $data['province_name'] = $provinsiMap[$data['province_code']] ?? $data['province_code'];
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data kode pos tidak ditemukan'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari data kode pos'
            ]);
        }
    }

    // Step 1: Form pendaftaran awal
    public function registrasiPasien()
    {
        // Hanya bersihkan session jika benar-benar memulai dari awal
        // Jangan hapus jika user kembali dari step berikutnya
        $fromReset = $this->request->getGet('reset');
        if ($fromReset === 'true') {
            $this->session->remove(['pasien_step1', 'pasien_step2', 'pasien_step3', 'pasien_step4', 'registration_data']);
        }
        
        $data['step1_data'] = $this->session->get('pasien_step1') ?? [];
        
        return view('admisi/registrasi_pasien', $data);
    }

    // Simpan data step 1
    public function save()
    {
        // Cek apakah nomor identitas sudah terdaftar
        $nomorIdentitas = $this->request->getPost('nomor_identitas');
        if ($nomorIdentitas) {
            $existingPasien = $this->pasienModel->where('nomor_identitas', $nomorIdentitas)->first();
            if ($existingPasien) {
                return redirect()->back()->withInput()->with('errors', [
                    'nomor_identitas' => 'Nomor identitas sudah terdaftar atas nama ' . $existingPasien['nama_lengkap'] . ' (No. RM: ' . $existingPasien['no_rekam_medis'] . '). Silakan gunakan fitur daftar ulang di menu Pasien Hari Ini jika ingin mendaftar ke poli lain.'
                ]);
            }
        }

        $rules = [
            'title' => 'required',
            'nama_lengkap' => 'required|min_length[3]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|valid_date',
            'status_perkawinan' => 'required',
            'nomor_identitas' => 'required',
        ];

        // Validasi file upload
        $file = $this->request->getFile('foto-identitas');
        $validation = \Config\Services::validation();
        $errors = [];
        if (!$file || !$file->isValid()) {
            $errors['foto-identitas'] = 'Dokumen identitas wajib diupload';
        } elseif (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'application/pdf'])) {
            $errors['foto-identitas'] = 'Format file harus JPG, PNG, atau PDF';
        } elseif ($file->getSize() > 2 * 1024 * 1024) { // 2MB
            $errors['foto-identitas'] = 'Ukuran file maksimal 2MB';
        }
        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan file ke folder writable/uploads
        $newName = uniqid('identitas_') . '.' . $file->getExtension();
        $uploadPath = FCPATH . 'uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $file->move($uploadPath, $newName);

        $data = [
            'title' => $this->request->getPost('title'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'status_perkawinan' => $this->request->getPost('status_perkawinan'),
            'nomor_identitas' => $this->request->getPost('nomor_identitas'),
            'email' => $this->request->getPost('email'),
            'nomor_hp' => $this->request->getPost('nomor_hp'),
            'foto_identitas' => $newName
        ];

        $this->session->set('pasien_step1', $data);
        return redirect()->to('admisi/registrasi-pasien/step2');
    }

    // Step 2: Form alamat
    public function registrasiPasienStep2()
    {
        if (!$this->session->has('pasien_step1')) {
            return redirect()->to('admisi/registrasi-pasien');
        }

        $data = [];
        
        // Ambil data dari session jika ada
        $data['pasien_data'] = $this->session->get('pasien_step1');
        $data['step2_data'] = $this->session->get('pasien_step2') ?? [];
        
        return view('admisi/registrasi_pasien_step2', $data);
    }

    // Simpan data step 2
    public function saveStep2()
    {
        $rules = [
            'alamat_lengkap' => 'required',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'kabupaten_kota' => 'required',
            'provinsi' => 'required',
            'kode_pos' => 'required|numeric|min_length[5]|max_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
            'kelurahan' => $this->request->getPost('kelurahan'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'kabupaten_kota' => $this->request->getPost('kabupaten_kota'),
            'provinsi' => $this->request->getPost('provinsi'),
            'kode_pos' => $this->request->getPost('kode_pos')
        ];

        $this->session->set('pasien_step2', $data);
        return redirect()->to('admisi/registrasi-pasien/step3');
    }

    // Step 3: Form kartu pasien
    public function registrasiPasienStep3()
    {
        if (!$this->session->has('pasien_step2')) {
            return redirect()->to('admisi/registrasi-pasien/step2');
        }
        
        $data['step3_data'] = $this->session->get('pasien_step3') ?? [];
        
        return view('admisi/registrasi_pasien_step3', $data);
    }

    // Simpan data step 3
    public function saveStep3()
    {
        $data = [
            'nama_di_kartu_pasien' => $this->request->getPost('nama_di_kartu_pasien'),
            'agama' => $this->request->getPost('agama'),
            'golongan_darah' => $this->request->getPost('golongan_darah'),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
            'kewarganegaraan' => $this->request->getPost('kewarganegaraan'),
            'suku' => $this->request->getPost('suku'),
            'pekerjaan' => $this->request->getPost('pekerjaan'),
            'no_telepon_rumah' => $this->request->getPost('no_telepon_rumah'),
            'status_pasien' => $this->request->getPost('status_pasien')
        ];

        $this->session->set('pasien_step3', $data);
        return redirect()->to('admisi/registrasi-pasien/step4');
    }

    // Step 4: Form kontak darurat
    public function registrasiPasienStep4()
    {
        if (!$this->session->has('pasien_step3')) {
            return redirect()->to('admisi/registrasi-pasien/step3');
        }

        $data['step4_data'] = $this->session->get('pasien_step4') ?? [];
        
        return view('admisi/registrasi_pasien_step4', $data);
    }

    // Simpan data step 4
    public function saveStep4()
    {
        $rules = [
            'nama_kontak' => 'required',
            'hubungan' => 'required',
            'nomor_hp' => 'required|numeric',
            'alamat' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_kontak' => $this->request->getPost('nama_kontak'),
            'hubungan' => $this->request->getPost('hubungan'),
            'nomor_hp' => $this->request->getPost('nomor_hp'),
            'alamat' => $this->request->getPost('alamat')
        ];

        $this->session->set('pasien_step4', $data);
        return redirect()->to('admisi/registrasi-pasien/step5');
    }

    // Step 5: Konfirmasi dan pilih poli
    public function registrasiPasienStep5()
    {
        if (!$this->session->has('pasien_step4')) {
            return redirect()->to('admisi/registrasi-pasien/step4');
        }

        $data['poli_list'] = $this->poliModel->select('id, nama, kode')->findAll();

        // Ambil semua dokter aktif (join ke users, role dokter)
        $db = \Config\Database::connect();
        $dokterRows = $db->table('dokter_jadwal')
            ->select('dokter_jadwal.dokter_id as id, users.nama_lengkap, users.spesialisasi')
            ->join('users', 'users.id = dokter_jadwal.dokter_id', 'left')
            ->where('users.role', 'dokter')
            ->groupBy('dokter_jadwal.dokter_id')
            ->get()->getResultArray();
        // Gunakan nama_lengkap saja, jika kosong fallback ke [Tanpa Nama]
        foreach ($dokterRows as &$dok) {
            $nama = isset($dok['nama_lengkap']) && !empty($dok['nama_lengkap']) ? $dok['nama_lengkap'] : '[Tanpa Nama]';
            $dok['nama'] = $nama;
            $dok['nama_lengkap'] = $nama;
        }
        unset($dok);
        $data['dokterList'] = $dokterRows;

        // Ambil jadwal praktik dokter (group by dokter dan poliklinik)
        $jadwalModel = new \App\Models\DokterJadwalModel();
        $jadwalAll = $jadwalModel->select('id, dokter_id, poliklinik_id, hari, jam_mulai, jam_selesai, keterangan')
            ->where('status', 1)
            ->findAll();
        $data['jadwalList'] = [];
        foreach ($jadwalAll as $jadwal) {
            $data['jadwalList'][$jadwal['dokter_id']][] = $jadwal;
        }

        // Tambahan: mapping jadwal per poliklinik untuk dropdown dokter
        $data['jadwalListByPoli'] = [];
        foreach ($jadwalAll as $jadwal) {
            $data['jadwalListByPoli'][$jadwal['poliklinik_id']][] = $jadwal;
        }

        return view('admisi/registrasi_pasien_step5', $data);
    }

    // Finalisasi pendaftaran
    public function saveStep5()
    {
        $rules = [
            'id_poli' => 'required|numeric',
            'konfirmasi_data' => 'required|in_list[1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    

        // Periksa apakah semua data session tersedia
        if (!$this->session->has('pasien_step1') || 
            !$this->session->has('pasien_step2') || 
            !$this->session->has('pasien_step3') || 
            !$this->session->has('pasien_step4')) {
            return redirect()->to('admisi/registrasi-pasien')->with('error', 'Data registrasi tidak lengkap. Silakan ulangi dari awal.');
        }

        // Gabungkan semua data dari session
        $pasienData = array_merge(
            $this->session->get('pasien_step1'),
            $this->session->get('pasien_step2'),
            $this->session->get('pasien_step3'),
            $this->session->get('pasien_step4'),
            ['id_poli' => $this->request->getPost('id_poli')]
        );

        // Generate nomor RM
        $pasienData['no_rm'] = $this->generateNoRM();
        
        // Validasi data wajib
        $requiredFields = ['title', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir'];
        foreach ($requiredFields as $field) {
            if (empty($pasienData[$field])) {
                throw new \Exception("Data {$field} tidak boleh kosong");
            }
        }
        
        // Simpan ke database
        try {
            $this->db->transStart();
            
            // 1. Data untuk tabel pasien
            $dataPasien = [
                'title' => $pasienData['title'],
                'nama_lengkap' => $pasienData['nama_lengkap'],
                'jenis_kelamin' => $pasienData['jenis_kelamin'],
                'tempat_lahir' => $pasienData['tempat_lahir'],
                'tanggal_lahir' => $pasienData['tanggal_lahir'],
                'no_rekam_medis' => $pasienData['no_rm'],
                'status_aktif' => 1,
                'tanggal_daftar' => date('Y-m-d H:i:s'),
                'status_perkawinan' => $pasienData['status_perkawinan'] ?? null,
                'nomor_identitas' => $pasienData['nomor_identitas'] ?? null,
                'email' => $pasienData['email'] ?? null,
                'nomor_hp' => $pasienData['nomor_hp'] ?? null,
                'foto_identitas' => $pasienData['foto_identitas'] ?? null
            ];

            // Tambahkan data alamat ke tabel terpisah
            $alamatData = [
                'alamat_lengkap' => $pasienData['alamat_lengkap'],
                'kelurahan' => $pasienData['kelurahan'],
                'kecamatan' => $pasienData['kecamatan'],
                'kabupaten_kota' => $pasienData['kabupaten_kota'],
                'provinsi' => $pasienData['provinsi'],
                'kode_pos' => $pasienData['kode_pos']
            ];

            // 1. Simpan data pasien
            if (!$this->pasienModel->insert($dataPasien)) {
                $errorMessage = 'Gagal menyimpan data pasien: ' . json_encode($this->pasienModel->errors());
                throw new \Exception($errorMessage);
            }
            
            // Ambil ID pasien yang baru saja disimpan
            $pasienId = $this->db->insertID();
            
            // 2. Simpan data alamat
            $alamatData['pasien_id'] = $pasienId;
            
            if (!$this->alamatPasienModel->insert($alamatData)) {
                $errorMessage = 'Gagal menyimpan data alamat pasien: ' . json_encode($this->alamatPasienModel->errors());
                throw new \Exception($errorMessage);
            }
            
            // Simpan info medis pasien
            $infoMedisData = [
                'pasien_id' => $pasienId,
                'golongan_darah' => $pasienData['golongan_darah'] ?? null
            ];
            $infoMedisModel = new \App\Models\InfoMedisPasienModel();
            if (!$infoMedisModel->insert($infoMedisData)) {
                $errorMessage = 'Gagal menyimpan info medis pasien: ' . json_encode($infoMedisModel->errors());
                throw new \Exception($errorMessage);
            }

            // Simpan info tambahan pasien
            $infoTambahanData = [
                'pasien_id' => $pasienId,
                'agama' => $pasienData['agama'] ?? null,
                'pendidikan_terakhir' => $pasienData['pendidikan_terakhir'] ?? null,
                'pekerjaan' => $pasienData['pekerjaan'] ?? null,
                'kewarganegaraan' => $pasienData['kewarganegaraan'] ?? 'Indonesia',
                'suku' => $pasienData['suku'] ?? null
            ];
            $infoTambahanModel = new \App\Models\InfoTambahanPasienModel();
            if (empty($infoTambahanData['kewarganegaraan'])) {
                $infoTambahanData['kewarganegaraan'] = 'Indonesia';
            }
            if (!$infoTambahanModel->insert($infoTambahanData)) {
                $errorMessage = 'Gagal menyimpan info tambahan pasien: ' . json_encode($infoTambahanModel->errors());
                throw new \Exception($errorMessage);
            }

            // Simpan data kontak darurat
            $kontakData = [
                'pasien_id' => $pasienId,
                'nama_kontak' => $pasienData['nama_kontak'],
                'hubungan' => $pasienData['hubungan'],
                'nomor_hp' => $pasienData['nomor_hp'],
                'alamat' => $pasienData['alamat']
            ];

            if (!$this->db->table('kontak_darurat')->insert($kontakData)) {
                $errorMessage = 'Gagal menyimpan data kontak darurat: ' . json_encode($this->db->error());
                throw new \Exception($errorMessage);
            }

            // Generate nomor antrian
            $noAntrian = $this->generateNoAntrian($pasienData['id_poli']);
            if (empty($noAntrian)) {
                throw new \Exception('Gagal generate nomor antrian');
            }

            // Gunakan timezone Asia/Jakarta untuk konsistensi
            $dateTime = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
            
            // Masukkan ke antrian
            $antrianData = [
                'no_antrian' => $noAntrian,
                'no_rm' => $pasienData['no_rm'],
                'id_poli' => $pasienData['id_poli'],
                'status' => 'Menunggu Pemeriksaan',
                'created_at' => $dateTime->format('Y-m-d H:i:s')
            ];

            // Inisialisasi model antrian
            $antrianModel = new \App\Models\AntrianModel();

            $insertResult = $antrianModel->insert($antrianData);
            
            if (!$insertResult) {
                $errorMessage = 'Gagal menyimpan data antrian: ' . json_encode($antrianModel->errors());
                throw new \Exception($errorMessage);
            }
            
            // Commit transaction if all is well
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                throw new \Exception('Database transaction failed');
            }
            $this->db->transCommit();
            
            // Set flash data for success
            $this->session->setFlashdata('registration_data', [
                'no_rm' => $pasienData['no_rm'],
                'no_antrian' => $noAntrian
            ]);

            // Hapus data session
            $this->session->remove(['pasien_step1', 'pasien_step2', 'pasien_step3', 'pasien_step4']);
            
            // Redirect ke halaman sukses
            return redirect()->to('admisi/registrasi-sukses');
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }


    // Halaman sukses registrasi
    public function registrasiSukses()
    {
        if (!$this->session->has('registration_data')) {
            return redirect()->to('admisi/registrasi-pasien');
        }

        $data = $this->session->get('registration_data');
        $this->session->remove('registration_data');
        
        return view('admisi/registrasi_sukses', $data);
    }

    // generate nomor RM
    private function generateNoRM()
    {
        $prefix = 'RM' . date('Ym');
        $lastRM = $this->pasienModel->getLastRM();
        $counter = 1;
        if (!empty($lastRM)) {
            $lastPrefix = substr($lastRM, 0, 8); // RM + YYYYMM
            if ($lastPrefix === $prefix) {
                $counter = (int)substr($lastRM, 8, 4) + 1;
            }
        }
        do {
            $newRM = $prefix . str_pad($counter, 4, '0', STR_PAD_LEFT);
            $exists = $this->pasienModel->where('no_rekam_medis', $newRM)->first();
            if (!$exists) {
                return $newRM;
            }
            $counter++;
        } while (true);
    }

    private function generateNoAntrian($idPoli)
    {
        $prefix = chr(64 + $idPoli); // 
        $date = date('Y-m-d');
        
        // Loop untuk memastikan nomor antrian unik
        $maxAttempts = 100;
        $attempt = 0;
        
        do {
            $lastAntrian = $this->antrianModel->getLastAntrian($idPoli, $date);
            
            if ($lastAntrian) {
                $counter = (int)substr($lastAntrian, 1, 3); 
            } else {
                $counter = 0;
            }
            
            $newNoAntrian = $prefix . str_pad($counter + 1, 3, '0', STR_PAD_LEFT);
            
            // Cek apakah nomor sudah ada di database hari ini
            $exists = $this->antrianModel
                ->where('no_antrian', $newNoAntrian)
                ->where('DATE(created_at)', $date)
                ->first();
            
            if (!$exists) {
                return $newNoAntrian;
            }
            
            $attempt++;
            
        } while ($attempt < $maxAttempts);
        
        // Jika gagal setelah banyak percobaan, gunakan timestamp
        $fallbackNo = $prefix . substr(time(), -3);
        return $fallbackNo;
    }
    public function pasienHariIni()
    {
        $today = date('Y-m-d');
        
        // Cek timezone database
        $timezoneQuery = $this->db->query("SELECT NOW() as now, CURDATE() as curdate, @@session.time_zone as tz");
        $timezoneInfo = $timezoneQuery->getRow();
        
        // Gunakan raw SQL untuk ambil antrian terbaru per pasien hari ini (dari tabel antrian saja)
        $sql = "SELECT 
                    a.id,
                    a.no_rm,
                    a.no_antrian,
                    a.id_poli,
                    a.status,
                    a.created_at,
                    p.title, 
                    p.nama_lengkap, 
                    p.jenis_kelamin, 
                    p.tanggal_lahir, 
                    p.no_rekam_medis
                FROM antrian a
                LEFT JOIN pasien p ON p.no_rekam_medis = a.no_rm
                INNER JOIN (
                    SELECT no_rm, MAX(id) as max_id
                    FROM antrian
                    WHERE DATE(created_at) = CURDATE()
                    GROUP BY no_rm
                ) latest_antrian ON latest_antrian.max_id = a.id
                ORDER BY a.created_at DESC";
        
        $query = $this->db->query($sql);
        $pasien_hari_ini = $query->getResultArray();
        
        // Summary cards
        $total_pasien_hari_ini = count($pasien_hari_ini);
        $pasien_laki_laki = 0;
        $pasien_perempuan = 0;
        $total_umur = 0;
        foreach ($pasien_hari_ini as $p) {
            if (isset($p['jenis_kelamin'])) {
                if ($p['jenis_kelamin'] === 'L') {
                    $pasien_laki_laki++;
                } elseif ($p['jenis_kelamin'] === 'P') {
                    $pasien_perempuan++;
                }
            }
            if (isset($p['tanggal_lahir']) && !empty($p['tanggal_lahir'])) {
                $umur = date_diff(date_create($p['tanggal_lahir']), date_create($today))->y;
                $total_umur += $umur;
            }
        }
        $rata_rata_umur = $total_pasien_hari_ini > 0 ? ($total_umur / $total_pasien_hari_ini) : 0;

        $data = [
            'title' => 'Pasien Hari Ini',
            'pageTitle' => 'Pasien Hari Ini',
            'pasien_hari_ini' => $pasien_hari_ini,
            'total_pasien_hari_ini' => $total_pasien_hari_ini,
            'pasien_laki_laki' => $pasien_laki_laki,
            'pasien_perempuan' => $pasien_perempuan,
            'rata_rata_umur' => $rata_rata_umur
        ];

        return view('admisi/pasien_terdaftar_hari_ini', $data);
    }
    // Tampilkan seluruh data pasien
    public function datapasien()
    {
        $pasien = $this->pasienModel->findAll();
        $data = [
            'title' => 'Data Pasien',
            'pageTitle' => 'Data Pasien',
            'pasien' => $pasien
        ];
        return view('admisi/datapasien', $data);
    }

    public function deletePasien($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid.'
            ]);
        }
        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pasien tidak ditemukan.'
            ]);
        }
        $pasien = $this->pasienModel->find($id);
        if (!$pasien) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.'
            ]);
        }
        // Hapus data pasien
        try {
            $this->db->transStart();
            $this->pasienModel->delete($id);
            // Hapus data terkait jika perlu (alamat, info medis, info tambahan, kontak darurat, antrian)
            $this->db->table('alamat_pasien')->where('pasien_id', $id)->delete();
            $this->db->table('info_medis_pasien')->where('pasien_id', $id)->delete();
            $this->db->table('info_tambahan_pasien')->where('pasien_id', $id)->delete();
            $this->db->table('kontak_darurat')->where('pasien_id', $id)->delete();
            $this->db->table('antrian')
                ->where('no_rm', $pasien['no_rekam_medis'])
                ->where('status', 'Menunggu Pemeriksaan')
                ->delete();
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data pasien.'
                ]);
            }
            $this->db->transCommit();
            return $this->response->setJSON([
                'success' => true
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function getDetailPasien($id)
    {
        $pasienModel = new \App\Models\PasienModel();
        $alamatModel = new \App\Models\AlamatPasienModel();
        $kontakModel = new \App\Models\KontakDaruratModel();
        $infoMedisModel = new \App\Models\InfoMedisPasienModel();
        $infoTambahanModel = new \App\Models\InfoTambahanPasienModel();

        $pasien = $pasienModel->find($id);
        $alamat = $alamatModel->where('pasien_id', $id)->findAll();
        $kontak = $kontakModel->where('pasien_id', $id)->findAll();
        $infoMedis = $infoMedisModel->where('pasien_id', $id)->findAll();
        $infoTambahan = $infoTambahanModel->where('pasien_id', $id)->findAll();

        return $this->response->setJSON([
            'pasien' => $pasien,
            'alamat' => $alamat,
            'kontak' => $kontak,
            'info_medis' => $infoMedis,
            'info_tambahan' => $infoTambahan
        ]);
    }


     // API: Daftarkan ulang pasien ke antrian hari ini
    public function daftarUlangPasien()
    {
        if (!$this->request->isAJAX() && !$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid.'
            ]);
        }
        $data = $this->request->getJSON(true);
        $no_rm = $data['no_rm'] ?? null;
        $id_poli = $data['id_poli'] ?? null;
        if (!$no_rm || !$id_poli) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No. RM atau poli tidak valid.'
            ]);
        }
        $today = date('Y-m-d');
        
        // Cek apakah sudah terdaftar hari ini di poli yang sama dengan status aktif
        $cek = $this->antrianModel
            ->where('no_rm', $no_rm)
            ->where('id_poli', $id_poli)
            ->where("DATE(created_at)", $today)
            ->whereNotIn('status', ['Selesai', 'Batal'])
            ->first();
            
        if ($cek) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pasien sudah memiliki antrian aktif hari ini di poli yang sama (No. Antrian: ' . $cek['no_antrian'] . '). Silakan tunggu hingga pemeriksaan selesai atau pilih poli lain jika diperlukan.'
            ]);
        }
        
        // Generate nomor antrian untuk poli terpilih
        $no_antrian = $this->generateNoAntrian($id_poli);
        
        // Gunakan timezone Asia/Jakarta untuk konsistensi
        $dateTime = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        
        $antrianData = [
            'no_antrian' => $no_antrian,
            'no_rm' => $no_rm,
            'id_poli' => $id_poli,
            'status' => 'Menunggu Pemeriksaan',
            'created_at' => $dateTime->format('Y-m-d H:i:s')
        ];
        
        $insertResult = $this->antrianModel->insert($antrianData);
        
        if (!$insertResult) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan data antrian.'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'no_antrian' => $no_antrian
        ]);
    }

    // API: Ambil daftar poli untuk dropdown daftar ulang
    public function getPoliList()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid.'
            ]);
        }
        $poli = $this->poliModel->select('id, nama, kode')->findAll();
        return $this->response->setJSON([
            'success' => true,
            'poli' => $poli
        ]);
    }

      // AJAX: Cek nomor identitas unik
    public function cekNomorIdentitas()
    {
        $nomorIdentitas = $this->request->getPost('nomor_identitas');
        $exists = false;
        if ($nomorIdentitas) {
            $exists = $this->pasienModel->where('nomor_identitas', $nomorIdentitas)->countAllResults() > 0;
        }
        return $this->response->setJSON(['exists' => $exists]);
    }

    // Edit Data Pasien
    public function editPasien($id)
    {
        // Load models
        $infoTambahanModel = new \App\Models\InfoTambahanPasienModel();
        $infoMedisModel = new \App\Models\InfoMedisPasienModel();
        $kontakDaruratModel = new \App\Models\KontakDaruratModel();

        // Get pasien data
        $pasien = $this->pasienModel->find($id);
        if (!$pasien) {
            return redirect()->to('admisi/datapasien')->with('error', 'Data pasien tidak ditemukan');
        }

        // Get related data
        $alamat = $this->alamatPasienModel->where('pasien_id', $id)->first();
        $info_tambahan = $infoTambahanModel->where('pasien_id', $id)->first();
        $info_medis = $infoMedisModel->where('pasien_id', $id)->first();
        $kontak_darurat = $kontakDaruratModel->where('pasien_id', $id)->first();

        $data = [
            'title' => 'Edit Data Pasien - SIMRS',
            'pasien' => $pasien,
            'alamat' => $alamat ?? [],
            'info_tambahan' => $info_tambahan ?? [],
            'info_medis' => $info_medis ?? [],
            'kontak_darurat' => $kontak_darurat ?? []
        ];

        return view('admisi/edit_pasien', $data);
    }

    /**
     * Update Data Pasien
     * 
     * Method untuk mengupdate data pasien beserta informasi terkait seperti alamat,
     * informasi tambahan, informasi medis, dan kontak darurat.
     * Menggunakan database transaction untuk menjaga integritas data.
     * 
     * @param int $id ID pasien yang akan diupdate
     * @return \CodeIgniter\HTTP\RedirectResponse
     * 
     * Tabel Database yang Terpengaruh:
     * - pasien: Data utama pasien (nama, identitas, kontak)
     * - alamat_pasien: Alamat lengkap pasien
     * - info_tambahan_pasien: Informasi demografis (agama, pendidikan, pekerjaan, dll)
     * - info_medis_pasien: Informasi medis (golongan darah)
     * - kontak_darurat: Informasi kontak darurat
     */
    public function updatePasien($id)
    {
        // Check if request is POST
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Invalid request method');
        }
        
        // Inisialisasi model yang diperlukan
        $infoTambahanModel = new \App\Models\InfoTambahanPasienModel();
        $infoMedisModel = new \App\Models\InfoMedisPasienModel();
        $kontakDaruratModel = new \App\Models\KontakDaruratModel();

        // Validasi input data
        if (!$this->validasiDataPasien()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Mulai database transaction
        $this->db->transStart();

        try {
            // Update data utama pasien
            if (!$this->updateDataUtamaPasien($id)) {
                $this->db->transRollback();
                $errors = $this->pasienModel->errors();
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data pasien: ' . (isset($errors['email']) ? $errors['email'] : 'Data tidak valid'));
            }
            
            // Update alamat pasien
            $this->updateAlamatPasien($id);
            
            // Update informasi tambahan pasien
            $this->updateInfoTambahanPasien($id, $infoTambahanModel);
            
            // Update informasi medis pasien
            $this->updateInfoMedisPasien($id, $infoMedisModel);
            
            // Update kontak darurat pasien
            $this->updateKontakDaruratPasien($id, $kontakDaruratModel);

            // Selesaikan transaction
            $this->db->transComplete();

            // Cek status transaction
            if ($this->db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data pasien. Silakan coba lagi.');
            }

            return redirect()->to('admisi/datapasien')->with('success', 'Data pasien berhasil diupdate');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Validasi Data Input Pasien
     * 
     * Memvalidasi seluruh input yang diperlukan untuk update data pasien
     * 
     * @return bool True jika validasi berhasil, false jika gagal
     */
    private function validasiDataPasien()
    {
        $rules = [
            'title' => 'required',
            'nama_lengkap' => 'required|min_length[3]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'nomor_identitas' => 'required',
            'nomor_hp' => 'required',
            'alamat_lengkap' => 'required'
        ];

        if (!$this->validate($rules)) {
            return false;
        }

        return true;
    }

    /**
     * Update Data Utama Pasien
     * 
     * Mengupdate data inti pasien di tabel 'pasien'
     * Termasuk: title, nama, jenis kelamin, tempat/tanggal lahir, identitas, kontak
     * 
     * @param int $id ID pasien
     * @return bool True jika berhasil, false jika gagal
     */
    private function updateDataUtamaPasien($id)
    {
        $pasienData = [
            'title' => $this->request->getPost('title'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'nomor_identitas' => $this->request->getPost('nomor_identitas'),
            'status_perkawinan' => $this->request->getPost('status_perkawinan'),
            'nomor_hp' => $this->request->getPost('nomor_hp'),
            'email' => $this->request->getPost('email')
        ];

        $result = $this->pasienModel->update($id, $pasienData);
        
        return $result;
    }

    /**
     * Update Alamat Pasien
     * 
     * Mengupdate atau membuat record baru alamat pasien di tabel 'alamat_pasien'
     * Termasuk: alamat lengkap, kelurahan, kecamatan, kabupaten/kota, provinsi, kode pos
     * 
     * @param int $id ID pasien
     * @return void
     */
    private function updateAlamatPasien($id)
    {
        $alamatData = [
            'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
            'kelurahan' => $this->request->getPost('kelurahan'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'kabupaten_kota' => $this->request->getPost('kabupaten_kota'),
            'provinsi' => $this->request->getPost('provinsi'),
            'kode_pos' => $this->request->getPost('kode_pos')
        ];

        $existingAlamat = $this->alamatPasienModel->where('pasien_id', $id)->first();
        
        if ($existingAlamat) {
            // Update data alamat yang sudah ada
            $this->alamatPasienModel->update($existingAlamat['id'], $alamatData);
        } else {
            // Insert data alamat baru
            $alamatData['pasien_id'] = $id;
            $this->alamatPasienModel->insert($alamatData);
        }
    }

    /**
     * Update Informasi Tambahan Pasien
     * 
     * Mengupdate atau membuat record informasi demografis di tabel 'info_tambahan_pasien'
     * Termasuk: agama, pendidikan terakhir, pekerjaan, kewarganegaraan, suku
     * 
     * @param int $id ID pasien
     * @param \App\Models\InfoTambahanPasienModel $model Instance dari InfoTambahanPasienModel
     * @return void
     */
    private function updateInfoTambahanPasien($id, $model)
    {
        $infoData = [
            'agama' => $this->request->getPost('agama'),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
            'pekerjaan' => $this->request->getPost('pekerjaan'),
            'kewarganegaraan' => $this->request->getPost('kewarganegaraan'),
            'suku' => $this->request->getPost('suku')
        ];

        $existingInfo = $model->where('pasien_id', $id)->first();
        
        if ($existingInfo) {
            // Update data info tambahan yang sudah ada
            $model->update($existingInfo['id'], $infoData);
        } else {
            // Insert data info tambahan baru
            $infoData['pasien_id'] = $id;
            $model->insert($infoData);
        }
    }

    /**
     * Update Informasi Medis Pasien
     * 
     * Mengupdate atau membuat record informasi medis di tabel 'info_medis_pasien'
     * Saat ini menangani: golongan darah
     * 
     * @param int $id ID pasien
     * @param \App\Models\InfoMedisPasienModel $model Instance dari InfoMedisPasienModel
     * @return void
     */
    private function updateInfoMedisPasien($id, $model)
    {
        $infoMedisData = [
            'golongan_darah' => $this->request->getPost('golongan_darah')
        ];

        $existingInfoMedis = $model->where('pasien_id', $id)->first();
        
        if ($existingInfoMedis) {
            // Update data info medis yang sudah ada
            $model->update($existingInfoMedis['id'], $infoMedisData);
        } else {
            // Insert data info medis baru
            $infoMedisData['pasien_id'] = $id;
            $model->insert($infoMedisData);
        }
    }

    /**
     * Update Kontak Darurat Pasien
     * 
     * Mengupdate atau membuat record kontak darurat di tabel 'kontak_darurat'
     * Termasuk: nama kontak, hubungan, nomor HP, alamat
     * 
     * @param int $id ID pasien
     * @param \App\Models\KontakDaruratModel $model Instance dari KontakDaruratModel
     * @return void
     */
    private function updateKontakDaruratPasien($id, $model)
    {
        $kontakData = [
            'nama_kontak' => $this->request->getPost('nama_kontak'),
            'hubungan' => $this->request->getPost('hubungan'),
            'nomor_hp' => $this->request->getPost('nomor_hp_darurat'),
            'alamat' => $this->request->getPost('alamat_darurat')
        ];

        $existingKontak = $model->where('pasien_id', $id)->first();
        
        if ($existingKontak) {
            // Update data kontak darurat yang sudah ada
            $model->update($existingKontak['id'], $kontakData);
        } else {
            // Insert data kontak darurat baru
            $kontakData['pasien_id'] = $id;
            $model->insert($kontakData);
        }
    }

    /**
     * Print Nomor Antrian
     */
    public function printAntrian($no_antrian)
    {
        $db = \Config\Database::connect();
        
        // Ambil data lengkap dari antrian (yang paling baru hari ini)
        $today = date('Y-m-d');
        $antrian = $db->table('antrian a')
            ->select('a.*, p.nama_lengkap, p.tanggal_lahir, p.jenis_kelamin, p.no_rekam_medis')
            ->join('pasien p', 'p.no_rekam_medis = a.no_rm', 'left')
            ->where('a.no_antrian', $no_antrian)
            ->where('DATE(a.created_at)', $today)
            ->orderBy('a.id', 'DESC')  // Ambil data terbaru jika ada duplikasi
            ->limit(1)
            ->get()
            ->getRowArray();

        if (!$antrian) {
            echo '<script>alert("Data antrian tidak ditemukan!"); window.close();</script>';
            return;
        }
        
        $data = [
            'no_antrian' => $no_antrian,
            'antrian' => $antrian
        ];
        
        return view('admisi/antrian_admisi', $data);
    }

    public function jadwalDokter()
    {
        // Get all doctors
        $db = \Config\Database::connect();
        
        $dokterList = $db->table('users')
            ->where('role', 'dokter')
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();
        
        // Get schedule for each doctor
        $jadwalList = [];
        foreach ($dokterList as $dokter) {
            $jadwal = $db->table('dokter_jadwal dj')
                ->select('dj.*, p.nama as nama_poli')
                ->join('poliklinik p', 'p.id = dj.poliklinik_id', 'left')
                ->where('dj.dokter_id', $dokter['id'])
                ->orderBy('
                    FIELD(dj.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")
                ')
                ->orderBy('dj.jam_mulai', 'ASC')
                ->get()
                ->getResultArray();
            
            $jadwalList[$dokter['id']] = $jadwal;
        }
        
        // Get poliklinik list for filter
        $poli_list = $db->table('poliklinik')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();
        
        $data = [
            'title' => 'Jadwal Praktik Dokter - SIMRS',
            'dokterList' => $dokterList,
            'jadwalList' => $jadwalList,
            'poli_list' => $poli_list
        ];
        
        return view('admisi/JadwalDokter', $data);
    }
}

