<?php

namespace App\Models;

use CodeIgniter\Model;

class ResepModel extends Model
{
    protected $table = 'resep';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_pasien',
        'id_dokter',
        'id_rekam_medis',
        'id_obat',
        'nama_obat',
        'jumlah',
        'satuan',
        'instruksi',
        'dosis',
        'cara_pakai',
        'tanggal_resep',
        'tanggal_diproses',
        'tanggal_selesai',
        'status',
        'diproses_oleh',
        'diselesaikan_oleh',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_pasien' => 'required|integer',
        'id_dokter' => 'required|integer',
        'nama_obat' => 'required|max_length[100]',
        'jumlah' => 'required|integer|greater_than[0]',
        'status' => 'permit_empty|in_list[pending,processing,completed,cancelled]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get resep with related data (pasien, dokter, obat)
     */
    public function getResepWithDetails($where = null)
    {
        $builder = $this->db->table($this->table . ' r');
        $builder->select('r.*, p.no_rekam_medis as no_rm, p.nama_lengkap as nama_pasien, u.nama_lengkap as nama_dokter, o.satuan as satuan_obat, o.harga_jual, rk.diagnosis');
        $builder->join('pasien p', 'r.id_pasien = p.id', 'left');
        $builder->join('users u', 'r.id_dokter = u.id', 'left');
        $builder->join('obat o', 'r.id_obat = o.id_obat', 'left');
        $builder->join('pemeriksaan_soap rk', 'r.id_rekam_medis = rk.id', 'left');
        
        if ($where) {
            $builder->where($where);
        }
        
        $builder->orderBy('r.tanggal_resep', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get resep by status
     */
    public function getByStatus($status)
    {
        return $this->getResepWithDetails(['r.status' => $status]);
    }

    /**
     * Get pending resep (belum diproses)
     */
    public function getPendingResep()
    {
        return $this->getByStatus('pending');
    }

    /**
     * Get processing resep (sedang diproses)
     */
    public function getProcessingResep()
    {
        return $this->getByStatus('processing');
    }

    /**
     * Get completed resep (selesai)
     */
    public function getCompletedResep()
    {
        return $this->getByStatus('completed');
    }

    /**
     * Update status resep
     */
    public function updateStatus($id, $status, $additionalData = [])
    {
        $data = array_merge(['status' => $status], $additionalData);
        
        switch ($status) {
            case 'processing':
                $data['tanggal_diproses'] = date('Y-m-d H:i:s');
                break;
            case 'completed':
                $data['tanggal_selesai'] = date('Y-m-d H:i:s');
                break;
        }
        
        return $this->update($id, $data);
    }

    /**
     * Create resep from SOAP examination
     */
    public function createFromSOAP($soapData, $obatData)
    {
        $resepData = [
            'id_pasien' => $soapData['id_pasien'] ?? null,
            'id_dokter' => $soapData['id_dokter'] ?? null,
            'id_rekam_medis' => $soapData['id'] ?? null,
            'id_obat' => $obatData['id_obat'] ?? null,
            'nama_obat' => $obatData['nama_obat'] ?? '',
            'jumlah' => $obatData['jumlah'] ?? 0,
            'satuan' => $obatData['satuan'] ?? '',
            'instruksi' => $obatData['instruksi'] ?? '',
            'dosis' => $obatData['dosis'] ?? '',
            'cara_pakai' => $obatData['cara_pakai'] ?? '',
            'tanggal_resep' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ];
        
        return $this->insert($resepData);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        return [
            'total' => $this->countAll(),
            'pending' => $this->where('status', 'pending')->countAllResults(false),
            'processing' => $this->where('status', 'processing')->countAllResults(false),
            'completed' => $this->where('status', 'completed')->countAllResults(false)
        ];
    }

    /**
     * Ambil semua riwayat permintaan obat (urut terbaru)
     */
    public function getRiwayatPermintaan()
    {
        return $this->orderBy('tanggal_resep', 'DESC')->findAll();
    }
    /**
     * Ambil daftar tagihan pasien yang resepnya sudah completed (belum ada filter pembayaran)
     */
    public function getTagihanPasienBelumBayar()
    {
        $builder = $this->db->table($this->table . ' r');
        $builder->select('r.id_pasien, p.no_rekam_medis as no_rm, p.nama_lengkap as nama_pasien, DATE(r.tanggal_resep) as tanggal, SUM(o.harga_jual * r.jumlah) as total_obat');
        $builder->join('pasien p', 'r.id_pasien = p.id', 'left');
        $builder->join('obat o', 'r.id_obat = o.id_obat', 'left');
        $builder->where('r.status', 'completed');
        // Tambah kondisi untuk pastikan belum dibayar
        $builder->where('r.is_paid', 0); // Asumsi ada field is_paid di tabel resep
        $builder->groupBy(['r.id_pasien', 'tanggal']);
        $builder->orderBy('tanggal', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        // Tambahkan biaya standar ke setiap tagihan
        foreach ($results as &$tagihan) {
            $biaya_administrasi = 50000;  // Rp 50,000
            $biaya_nurs_station = 100000; // Rp 100,000
            $biaya_dokter = 250000;       // Rp 250,000
            $total_obat = $tagihan['total_obat'] ?? 0;
            
            $tagihan['biaya_administrasi'] = $biaya_administrasi;
            $tagihan['biaya_nurs_station'] = $biaya_nurs_station;
            $tagihan['biaya_dokter'] = $biaya_dokter;
            $tagihan['total_tagihan'] = $total_obat + $biaya_administrasi + $biaya_nurs_station + $biaya_dokter;
        }
        
        return $results;
    }
}
