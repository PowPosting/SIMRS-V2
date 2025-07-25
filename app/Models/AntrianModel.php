<?php

namespace App\Models;

use CodeIgniter\Model;

class AntrianModel extends Model
    
    
{
    protected $table            = 'antrian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'no_antrian',
        'no_rm',
        'id_poli',
        'status',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setDefaultStatus'];
    /**
     * Callback untuk mengisi default status sebelum insert
     */
    protected function setDefaultStatus(array $data)
    {
        if (isset($data['data']) && empty($data['data']['status'])) {
            $data['data']['status'] = 'Menunggu Pemeriksaan';
        }
        return $data;
    }
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    public function getLastAntrian($idPoli, $date)
    {
        // Filter tanggal dengan format Y-m-d agar lebih akurat
        $startDate = $date . ' 00:00:00';
        $endDate = $date . ' 23:59:59';
        $result = $this->where('id_poli', $idPoli)
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->orderBy('no_antrian', 'DESC')
            ->first();
        return $result ? $result['no_antrian'] : null;
    }
}
