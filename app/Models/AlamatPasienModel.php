<?php

namespace App\Models;

use CodeIgniter\Model;

class AlamatPasienModel extends Model
{
    protected $table = 'alamat_pasien';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected bool $allowEmptyInserts = false;
    
    protected $allowedFields = [
        'pasien_id', 'alamat_lengkap',
        'kelurahan', 'kecamatan', 'kabupaten_kota',
        'provinsi', 'kode_pos'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi
    protected $validationRules = [
        'pasien_id' => 'required|numeric',
        'alamat_lengkap' => 'required',
        'kelurahan' => 'required',
        'kecamatan' => 'required',
        'kabupaten_kota' => 'required',
        'provinsi' => 'required'
    ];

    // Relasi dengan tabel Pasien
    public function pasien()
    {
        return $this->belongsTo('App\Models\PasienModel', 'pasien_id', 'id');
    }
}
