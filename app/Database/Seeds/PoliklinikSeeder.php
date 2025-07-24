<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PoliklinikSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'         => 1,
                'nama'       => 'Poli Umum',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 2,
                'nama'       => 'Poli Gigi',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 3,
                'nama'       => 'Poli Anak',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('poliklinik')->insertBatch($data);
    }
}
