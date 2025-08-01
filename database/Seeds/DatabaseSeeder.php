<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('ObatSeeder');
        
        echo "\n=== SEEDING COMPLETED ===\n";
        echo "Semua data dummy berhasil ditambahkan ke database.\n";
    }
}
