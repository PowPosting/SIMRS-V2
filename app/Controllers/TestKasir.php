<?php

namespace App\Controllers;

use App\Models\TagihanModel;

class TestKasir extends BaseController {
    
    public function index()
    {
        // Ambil data tagihan dari table tagihan tanpa auth check
        $tagihanModel = new TagihanModel();
        
        // Ambil tagihan yang belum dibayar dengan join ke pasien untuk nama
        $tagihan_list = $tagihanModel->builder()
            ->select('tagihan.*, pasien.nama_lengkap as nama_pasien')
            ->join('pasien', 'pasien.no_rekam_medis = tagihan.no_rm', 'left')
            ->where('tagihan.status', 'pending')
            ->orderBy('tagihan.tanggal_tagihan', 'DESC')
            ->get()
            ->getResultArray();
        
        // Debug raw output
        echo "<h2>Debug Data Tagihan</h2>";
        echo "<pre>";
        echo "Jumlah data: " . count($tagihan_list) . "\n\n";
        
        foreach ($tagihan_list as $tagihan) {
            echo "ID Tagihan: " . $tagihan['id_tagihan'] . "\n";
            echo "No RM: " . $tagihan['no_rm'] . "\n";
            echo "Nama Pasien: " . ($tagihan['nama_pasien'] ?? 'NULL') . "\n";
            echo "Total Biaya: " . $tagihan['total_biaya'] . "\n";
            echo "Status: " . $tagihan['status'] . "\n";
            echo "Tanggal: " . $tagihan['tanggal_tagihan'] . "\n";
            echo "---\n";
        }
        echo "</pre>";
        
        // Format data untuk view
        foreach ($tagihan_list as &$tagihan) {
            $tagihan['tanggal'] = date('Y-m-d', strtotime($tagihan['tanggal_tagihan']));
            $tagihan['id_pasien'] = $tagihan['no_rm']; 
            $tagihan['total_tagihan'] = $tagihan['total_biaya'];
            
            // Debug: pastikan nama_pasien ada
            if (empty($tagihan['nama_pasien'])) {
                $tagihan['nama_pasien'] = '(Nama tidak ditemukan)';
            }
        }

        echo "<h2>Data Setelah Format:</h2>";
        echo "<pre>";
        print_r($tagihan_list);
        echo "</pre>";
        
        return "Debug selesai - lihat output di atas";
    }
}
