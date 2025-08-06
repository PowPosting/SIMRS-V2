<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanModel extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'no_rm',
        'no_resep',
        'total_biaya',
        'detail_tagihan',
        'status',
        'tanggal_tagihan',
        'tanggal_bayar',
        'metode_pembayaran',
        'kasir_id',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Ambil semua tagihan yang belum dibayar
    public function getTagihanBelumBayar()
    {
        return $this->where('status', 'pending')->orderBy('tanggal_tagihan', 'DESC')->findAll();
    }

    // Ambil detail tagihan berdasarkan id
    public function getDetailTagihan($id_tagihan)
    {
        return $this->find($id_tagihan);
    }

    // Update status tagihan menjadi paid
    public function bayarTagihan($id_tagihan, $data)
    {
        return $this->update($id_tagihan, $data);
    }
}
