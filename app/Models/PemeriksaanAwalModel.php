<?php

namespace App\Models;

use CodeIgniter\Model;

class PemeriksaanAwalModel extends Model
{
    protected $table = 'pemeriksaan_awal';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id_antrian', 'tekanan_darah', 'suhu_tubuh', 'berat_badan', 'tinggi_badan', 'keluhan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getPemeriksaanByIdAntrian($id_antrian)
    {
        return $this->where('id_antrian', $id_antrian)->first();
    }
}
