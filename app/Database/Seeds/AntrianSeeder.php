<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AntrianSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'no_antrian' => 'A001',
                'no_rm'      => 'RM001',
                'id_poli'    => 1,
                'status'     => 'Menunggu Pemeriksaan',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'no_antrian' => 'A002',
                'no_rm'      => 'RM002',
                'id_poli'    => 2,
                'status'     => 'Menunggu Pemeriksaan',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'no_antrian' => 'A003',
                'no_rm'      => 'RM003',
                'id_poli'    => 1,
                'status'     => 'Menunggu Pemeriksaan',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('antrian')->insertBatch($data);
    }
}
