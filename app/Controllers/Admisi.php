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
            log_message('error', 'Error saat mencari kode pos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari data kode pos'
            ]);
        }
    }

    // Step 1: Form pendaftaran awal
    public function registrasiPasien()
    {
        return view('admisi/registrasi_pasien');
    }

    // Simpan data step 1
    public function save()
    {
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
        log_message('debug', '[REGISTRASI] Mulai validasi file upload. Ada file? ' . ($file ? 'YA' : 'TIDAK'));
        if (!$file || !$file->isValid()) {
            log_message('error', '[REGISTRASI] File tidak valid atau tidak ada. isValid: ' . ($file ? ($file->isValid() ? 'YA' : 'TIDAK') : 'N/A'));
            $errors['foto-identitas'] = 'Dokumen identitas wajib diupload';
        } elseif (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'application/pdf'])) {
            log_message('error', '[REGISTRASI] Format file salah: ' . $file->getMimeType());
            $errors['foto-identitas'] = 'Format file harus JPG, PNG, atau PDF';
        } elseif ($file->getSize() > 2 * 1024 * 1024) { // 2MB
            log_message('error', '[REGISTRASI] File terlalu besar: ' . $file->getSize());
            $errors['foto-identitas'] = 'Ukuran file maksimal 2MB';
        } else {
            log_message('debug', '[REGISTRASI] File upload valid: ' . $file->getName() . ', size: ' . $file->getSize() . ', type: ' . $file->getMimeType());
        }
        if (!empty($errors)) {
            log_message('error', '[REGISTRASI] Error validasi file: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        log_message('debug', 'POST data step 1 after validation: ' . json_encode($this->request->getPost()));

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
            log_message('error', 'Validasi gagal step2: ' . json_encode($this->validator->getErrors()));
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

        log_message('debug', 'Step2 POST: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Step2 Data to session: ' . json_encode($data));

        $this->session->set('pasien_step2', $data);
        log_message('debug', 'Step2 session after set: ' . json_encode($this->session->get('pasien_step2')));
        return redirect()->to('admisi/registrasi-pasien/step3');
    }

    // Step 3: Form kartu pasien
    public function registrasiPasienStep3()
    {
        if (!$this->session->has('pasien_step2')) {
            return redirect()->to('admisi/registrasi-pasien/step2');
        }
        return view('admisi/registrasi_pasien_step3');
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

        return view('admisi/registrasi_pasien_step4');
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
        return view('admisi/registrasi_pasien_step5', $data);
    }

    // Finalisasi pendaftaran
    public function saveStep5()
    {
        // Debug: Print langsung ke output
        echo "Debug: Memulai proses saveStep5<br>";
        
        $rules = [
            'id_poli' => 'required|numeric',
            'konfirmasi_data' => 'required|in_list[1]'
        ];
        
        // Debug: Print POST data
        echo "Debug: POST data: " . json_encode($this->request->getPost()) . "<br>";

        // Log data yang diterima
        log_message('debug', '=== START SAVING STEP 5 ===');
        log_message('debug', 'POST Data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Session Data Step 1: ' . json_encode($this->session->get('pasien_step1')));
        log_message('debug', 'Session Data Step 2: ' . json_encode($this->session->get('pasien_step2')));
        log_message('debug', 'Session Data Step 3: ' . json_encode($this->session->get('pasien_step3')));
        log_message('debug', 'Session Data Step 4: ' . json_encode($this->session->get('pasien_step4')));

        if (!$this->validate($rules)) {
            log_message('error', 'Validation Errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Periksa apakah semua data session tersedia
        if (!$this->session->has('pasien_step1') || 
            !$this->session->has('pasien_step2') || 
            !$this->session->has('pasien_step3') || 
            !$this->session->has('pasien_step4')) {
            log_message('error', 'Missing session data. Available sessions: ' . 
                'step1=' . ($this->session->has('pasien_step1') ? 'yes' : 'no') . ', ' .
                'step2=' . ($this->session->has('pasien_step2') ? 'yes' : 'no') . ', ' .
                'step3=' . ($this->session->has('pasien_step3') ? 'yes' : 'no') . ', ' .
                'step4=' . ($this->session->has('pasien_step4') ? 'yes' : 'no'));
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
            
            // Debug output
            echo "Debug: Mencoba menyimpan data...<br>";
            
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

            // Log data yang akan disimpan
            log_message('debug', 'Data Pasien yang akan disimpan: ' . json_encode($dataPasien));
            log_message('debug', 'Data Alamat yang akan disimpan: ' . json_encode($alamatData));

            // Log data yang akan disimpan
            log_message('debug', 'Mencoba menyimpan data pasien: ' . json_encode($dataPasien));
            
            // 1. Simpan data pasien
            if (!$this->pasienModel->insert($dataPasien)) {
                $errorMessage = 'Gagal menyimpan data pasien: ' . json_encode($this->pasienModel->errors());
                log_message('error', $errorMessage);
                throw new \Exception($errorMessage);
            }
            
            // Ambil ID pasien yang baru saja disimpan
            $pasienId = $this->db->insertID();
            log_message('debug', 'Berhasil menyimpan data pasien dengan ID: ' . $pasienId);
            
            // 2. Simpan data alamat
            $alamatData['pasien_id'] = $pasienId;
            log_message('debug', 'Mencoba menyimpan data alamat: ' . json_encode($alamatData));
            
            if (!$this->alamatPasienModel->insert($alamatData)) {
                $errorMessage = 'Gagal menyimpan data alamat pasien: ' . json_encode($this->alamatPasienModel->errors());
                log_message('error', $errorMessage);
                throw new \Exception($errorMessage);
            }
            log_message('debug', 'Berhasil menyimpan data alamat');
            
            // Simpan info medis pasien
            $infoMedisData = [
                'pasien_id' => $pasienId,
                'golongan_darah' => $pasienData['golongan_darah'] ?? null
            ];
            $infoMedisModel = new \App\Models\InfoMedisPasienModel();
            if (!$infoMedisModel->insert($infoMedisData)) {
                $errorMessage = 'Gagal menyimpan info medis pasien: ' . json_encode($infoMedisModel->errors());
                log_message('error', $errorMessage);
                throw new \Exception($errorMessage);
            }
            log_message('debug', 'Berhasil insert info_medis_pasien');

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
                log_message('error', $errorMessage);
                throw new \Exception($errorMessage);
            }
            log_message('debug', 'Berhasil insert info_tambahan_pasien');

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
                log_message('error', $errorMessage);
                throw new \Exception($errorMessage);
            }

            // Generate nomor antrian
            $noAntrian = $this->generateNoAntrian($pasienData['id_poli']);
            if (empty($noAntrian)) {
                throw new \Exception('Gagal generate nomor antrian');
            }

            log_message('debug', 'Nomor antrian generated: ' . $noAntrian);

            // Masukkan ke antrian
            $antrianData = [
                'no_antrian' => $noAntrian,
                'no_rm' => $pasienData['no_rm'],
                'id_poli' => $pasienData['id_poli'],
                'status' => 'Menunggu Pemeriksaan'
            ];

            // Inisialisasi model antrian
            $antrianModel = new \App\Models\AntrianModel();

            if (!$antrianModel->insert($antrianData)) {
                $errorMessage = 'Gagal menyimpan data antrian: ' . json_encode($antrianModel->errors());
                log_message('error', $errorMessage);
                throw new \Exception($errorMessage);
            }
            
            // Commit transaction if all is well
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', 'Transaction Error: ' . json_encode($this->db->error()));
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
            log_message('error', 'Error detail: ' . $e->getMessage());
            log_message('error', 'Error trace: ' . $e->getTraceAsString());
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

    // Helper function untuk generate nomor RM
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
        $prefix = chr(64 + $idPoli); // A untuk poli 1, B untuk poli 2, dst
        $date = date('Y-m-d');
        $lastAntrian = $this->antrianModel->getLastAntrian($idPoli, $date);
        if ($lastAntrian) {
            $counter = (int)substr($lastAntrian, 1, 3); // Ambil 3 digit setelah prefix
        } else {
            $counter = 0;
        }
        return $prefix . str_pad($counter + 1, 3, '0', STR_PAD_LEFT);
    }
    public function pasienHariIni()
    {
        $today = date('Y-m-d');
        // Ambil semua pasien yang ada di antrian hari ini (registrasi baru & daftar ulang)
        $subquery = $this->db->table('antrian')
            ->select('no_rm, MAX(id) as max_id')
            ->where('DATE(created_at)', $today)
            ->groupBy('no_rm');

        $builder = $this->db->table('antrian')
            ->select('antrian.*, pasien.title, pasien.nama_lengkap, pasien.jenis_kelamin, pasien.tanggal_lahir, pasien.no_rekam_medis')
            ->join('pasien', 'pasien.no_rekam_medis = antrian.no_rm', 'left')
            ->join('(' . $subquery->getCompiledSelect() . ') as latest_antrian', 'latest_antrian.max_id = antrian.id', 'inner');
        $pasien_hari_ini = $builder->get()->getResultArray();

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
        // Cek apakah sudah terdaftar hari ini di poli yang sama atau ada data antrian dengan created_at NULL
        $cek = $this->antrianModel
            ->where('no_rm', $no_rm)
            ->where('id_poli', $id_poli)
            ->groupStart()
                ->where("DATE(created_at) = ", $today, false)
                ->orWhere('created_at IS NULL', null, false)
            ->groupEnd()
            ->first();
        if ($cek) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pasien sudah terdaftar di antrian hari ini pada poli ini.'
            ]);
        }
        // Generate nomor antrian untuk poli terpilih
        $no_antrian = $this->generateNoAntrian($id_poli);
        $antrianData = [
            'no_antrian' => $no_antrian,
            'no_rm' => $no_rm,
            'id_poli' => $id_poli,
            'status' => 'Menunggu Pemeriksaan',
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (!$this->antrianModel->insert($antrianData)) {
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

}

