<?php

namespace App\Models;

use CodeIgniter\Model;

class InfoTambahanPasienModel extends Model
{
    protected $table = 'info_tambahan_pasien';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'pasien_id', 'agama', 'pendidikan_terakhir',
        'pekerjaan', 'kewarganegaraan', 'suku', 
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi
    protected $validationRules = [
        'pasien_id' => 'required|numeric'
    ];

    // Relasi dengan tabel Pasien
    public function pasien()
    {
        return $this->belongsTo('App\Models\PasienModel', 'pasien_id', 'id');
    }
}
