<?php
namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk data antrian poli
 * @package App\Models
 */
class AntrianPoliModel extends Model
{
    protected $table = 'antrian_poli'; // Ganti dengan nama tabel yang sesuai
    protected $primaryKey = 'id'; // Ganti jika primary key berbeda
    protected $allowedFields = [
        'no_antrian',
        'no_rm',
        'id_poli',
        'id_antrian_perawat',
        'status',
        'created_at',
        'updated_at',
    ];
}
