<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AntrianModel;

class AntrianPoli extends Controller
{
    /**
     * Tampilkan detail antrian poli berdasarkan nomor antrian
     * GET /antrianpoli/detail/{no_antrian}
     */
    public function detail($no_antrian)
    {
        $antrianModel = model('App\Models\AntrianModel');
        $antrian = $antrianModel->where('no_antrian', $no_antrian)->first();
        if (!$antrian) {
            return $this->response->setStatusCode(404)->setBody('Data antrian tidak ditemukan');
        }
        // Kirim data ke view detail antrian poli
        return view('perawat/detail_antrian_poli', [
            'antrian' => $antrian
        ]);
    }

    /**
     * Tampilkan daftar semua antrian poli
     * GET /antrianpoli
     */
    public function index()
    {
        $antrianModel = model('App\Models\AntrianModel');
        $antrianPoli = $antrianModel->where('status', 'Menunggu Pemeriksaan')->findAll();
        return view('perawat/daftar_antrian_poli', [
            'antrianPoli' => $antrianPoli
        ]);
    }
}
