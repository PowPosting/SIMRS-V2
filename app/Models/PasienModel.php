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
        'status_aktif',
        'tanggal_daftar',
        'tanggal_hapus',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'tempat_lahir' => 'required|max_length[50]',
        'tanggal_lahir' => 'required|valid_date',
        'no_rekam_medis' => 'required|is_unique[pasien.no_rekam_medis]',
        'nomor_identitas' => 'required|is_unique[pasien.nomor_identitas]',
        'email' => 'permit_empty|is_unique[pasien.email]',
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
}
