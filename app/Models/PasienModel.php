<?php

namespace App\Models;

use CodeIgniter\Model;

class PasienModel extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'title',
        'nama_lengkap',
        'no_rekam_medis',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'status_perkawinan',
        'nomor_identitas',
        'email',
        'nomor_hp',
        'foto_identitas',
        'status_aktif',
        'tanggal_daftar',
        'tanggal_hapus',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [];
    
    // Validation rules untuk insert (new record)
    protected $insertValidationRules = [
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'tempat_lahir' => 'required|max_length[50]',
        'tanggal_lahir' => 'required|valid_date',
        'no_rekam_medis' => 'required|is_unique[pasien.no_rekam_medis]',
        'nomor_identitas' => 'required|is_unique[pasien.nomor_identitas]',
        'email' => 'permit_empty|valid_email|is_unique[pasien.email]',
        'status_aktif' => 'permit_empty|in_list[0,1]'
    ];
    
    // Validation rules untuk update (existing record)
    protected $updateValidationRules = [
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'tempat_lahir' => 'required|max_length[50]',
        'tanggal_lahir' => 'required|valid_date',
        'status_aktif' => 'permit_empty|in_list[0,1]'
    ];

    public function getLastRM()
    {
        $lastPasien = $this->select('no_rekam_medis')
            ->where('no_rekam_medis IS NOT NULL')
            ->orderBy('no_rekam_medis', 'DESC')
            ->first();
        if (empty($lastPasien) || empty($lastPasien['no_rekam_medis'])) {
            return null;
        }
        return $lastPasien['no_rekam_medis'];
    }

    protected $validationMessages = [
        'nama_lengkap' => [
            'required' => 'Nama lengkap harus diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter',
            'max_length' => 'Nama lengkap maksimal 100 karakter'
        ],
        'nomor_identitas' => [
            'is_unique' => 'Nomor identitas sudah terdaftar'
        ],
        'email' => [
            'is_unique' => 'Email sudah terdaftar'
        ]
    ];

    // Relasi dengan alamat pasien
    public function alamat()
    {
        return $this->hasOne('App\Models\AlamatPasienModel', 'pasien_id', 'id');
    }

    // Relasi dengan kontak darurat
    public function kontakDarurat()
    {
        return $this->hasOne('App\Models\KontakDaruratModel', 'pasien_id', 'id');
    }

    // Relasi dengan info medis
    public function infoMedis()
    {
        return $this->hasOne('App\Models\InfoMedisPasienModel', 'pasien_id', 'id');
    }

 // Ambil data pasien lengkap berdasarkan no_rekam_medis (No. RM)
    public function getByNoRM($no_rm)
    {
        $pasien = $this->where('no_rekam_medis', $no_rm)->first();
        if (!$pasien) return null;

        // Ambil relasi alamat
        $alamatModel = model('App\\Models\\AlamatPasienModel');
        $alamat = $alamatModel->where('pasien_id', $pasien['id'])->first();

        // Ambil relasi kontak darurat
        $kontakModel = model('App\\Models\\KontakDaruratModel');
        $kontak = $kontakModel->where('pasien_id', $pasien['id'])->first();

        // Ambil relasi info medis
        $medisModel = model('App\\Models\\InfoMedisPasienModel');
        $medis = $medisModel->where('pasien_id', $pasien['id'])->first();

        // ambil relasi info tambahan
        $tambahanModel = model('App\\Models\\InfoTambahanPasienModel');
        $tambahan = $tambahanModel->where('pasien_id', $pasien['id'])->first();

        // Gabungkan data
        return [
            // pasien
            'no_rm' => $pasien['no_rekam_medis'] ?? '-',
            'nomor_identitas' => $pasien['nomor_identitas'] ?? '-',
            'nama_lengkap' => isset($pasien['nama_lengkap']) ? strtoupper($pasien['nama_lengkap']) : '-',
            'jenis_kelamin' => isset($pasien['jenis_kelamin']) ? strtoupper($pasien['jenis_kelamin'] == 'L' ? 'Laki-laki' : ($pasien['jenis_kelamin'] == 'P' ? 'Perempuan' : '-')) : '-',
            'ttl' => isset($pasien['tempat_lahir']) ? strtoupper($pasien['tempat_lahir']) . ', ' . ($pasien['tanggal_lahir'] ?? '-') : '-',
            'status_perkawinan' => isset($pasien['status_perkawinan']) ? strtoupper($pasien['status_perkawinan']) : '-',
            'nomor_hp' => $pasien['nomor_hp'] ?? '-',
            // alamat pasien
            'alamat' => isset($alamat['alamat_lengkap']) ? strtoupper($alamat['alamat_lengkap']) : '-',
            'kelurahan' => isset($alamat['kelurahan']) ? strtoupper($alamat['kelurahan']) : '-',
            'kecamatan' => isset($alamat['kecamatan']) ? strtoupper($alamat['kecamatan']) : '-',
            'kabupaten' => isset($alamat['kabupaten_kota']) ? strtoupper($alamat['kabupaten_kota']) : '-',
            'kabupaten_kota' => isset($alamat['kabupaten_kota']) ? strtoupper($alamat['kabupaten_kota']) : '-',
            'provinsi' => isset($alamat['provinsi']) ? strtoupper($alamat['provinsi']) : '-',
            'kode_pos' => $alamat['kode_pos'] ?? '-',
            //infor tambahan medis  
            'golongan_darah' => $medis['golongan_darah'] ?? '-',
            
            // info tambahan
            'agama' => $tambahan['agama'] ?? '-',
            'pendidikan' => $tambahan['pendidikan_terakhir'] ?? '-',
            'pekerjaan' => $tambahan['pekerjaan'] ?? '-',
            'kewarganegaraan' => $tambahan['kewarganegaraan'] ?? '-',
            'suku' => $tambahan['suku'] ?? '-',

            
        ];
    }
    
    /**
     * Override insert method untuk menggunakan insertValidationRules
     */
    public function insert($data = null, bool $returnID = true)
    {
        $this->validationRules = $this->insertValidationRules;
        return parent::insert($data, $returnID);
    }
    
    /**
     * Override update method untuk menggunakan updateValidationRules
     * dan mengecualikan record yang sedang diupdate dari uniqueness check
     */
    public function update($id = null, $data = null): bool
    {
        log_message('info', '[PasienModel::update] ID: ' . $id);
        log_message('info', '[PasienModel::update] Data: ' . json_encode($data));
        
        // Set validation rules untuk update (tanpa is_unique checks)
        $this->validationRules = $this->updateValidationRules;
        
        // Jika nomor_identitas berubah, cek uniqueness (kecuali untuk record ini)
        if (isset($data['nomor_identitas'])) {
            $existingData = $this->find($id);
            if ($existingData && $existingData['nomor_identitas'] !== $data['nomor_identitas']) {
                // Nomor identitas berubah, cek apakah sudah dipakai
                $check = $this->where('nomor_identitas', $data['nomor_identitas'])
                    ->where('id !=', $id)
                    ->first();
                if ($check) {
                    $this->errors = ['nomor_identitas' => 'Nomor identitas sudah terdaftar'];
                    return false;
                }
            }
        }
        
        // Jika email berubah, validasi dan cek uniqueness (kecuali untuk record ini)
        if (isset($data['email']) && !empty($data['email'])) {
            // Validasi format email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors = ['email' => 'Format email tidak valid'];
                log_message('error', '[PasienModel::update] Email tidak valid: ' . $data['email']);
                return false;
            }
            
            $existingData = $existingData ?? $this->find($id);
            if ($existingData && ($existingData['email'] ?? '') !== $data['email']) {
                // Email berubah, cek apakah sudah dipakai
                $check = $this->where('email', $data['email'])
                    ->where('id !=', $id)
                    ->first();
                if ($check) {
                    $this->errors = ['email' => 'Email sudah terdaftar'];
                    return false;
                }
            }
        }
        
        return parent::update($id, $data);
    }

}
