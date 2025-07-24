<?php

namespace App\Models;

use CodeIgniter\Model;

class KontakDaruratModel extends Model
{
    protected $table = 'kontak_darurat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'pasien_id', 'nama_kontak', 'hubungan',
        'nomor_hp', 'alamat'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi
    protected $validationRules = [
        'pasien_id' => 'required|numeric',
        'nama_kontak' => 'required',
        'hubungan' => 'required',
        'nomor_hp' => 'required|min_length[10]'
    ];

    // Relasi dengan tabel Pasien
    public function pasien()
    {
        return $this->belongsTo('App\Models\PasienModel', 'pasien_id', 'id');
    }
}
